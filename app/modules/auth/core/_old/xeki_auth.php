<?php

namespace auth;

/**
 * Class xeki_auth
 * @package xeki_auth
 * version 0.1
 */
/**
 * Class xeki_auth
 * @package xeki_auth
 */

/**
 * Class xeki_auth
 * @package xeki_auth
 */
class xeki_auth
{
    /**
     * @var string
     */
    private $encryption_method = 'sha256';
    /**
     * @var string
     */
    private $table_user = 'user';
    /**
     * @var string
     */
    private $table_name_space = 'user_namespace';

    /**
     * @var string
     */
    private $table_name_space_ref = 'user_namespace_table_ref';

    /**
     * @var string
     */
    private $table_permissions = 'user_permissions';

    /**
     * @var string
     */
    private $table_permissions_ref = 'user_permissions_table_ref';

    /**
     * @var string
     */
    private $field_id = 'id';
    /**
     * @var string
     */
    private $field_user = 'email';
    /**
     * @var string
     */
    private $field_password = 'password';
    /**
     * @var string
     */
    private $set_password = 'set_password';
    /**
     * @var string
     */
    private $field_recover_code = 'recover';

    #db info user_temp
    /**
     * @var string
     */
    private $table_user_temp = 'customer_temp';
    /**
     * @var string
     */
    private $field_id_temp = 'id';
    /**
     * @var string
     */
    private $login_page = 'login';

    /**
     * @var string
     */
    private $register_page = 'register';

    /**
     * @var string
     */
    public $logged_page = 'dashboard';


    /**
     * @var bool
     */
    private $logged = false;

    /**
     * @var array
     */
    private $user = array();
    /**
     * @var array|int
     */
    private $id = array();

    /**
     * @var null
     */
    private $sql = null;

    /**
     * @var string
     */
    private $name_space = "default";

    private $db_config = "main";
    /**
     * @var string
     */
    public $folder_pages = '';
    /**
     * @var string
     */
    public $folder_base = '';
    /**
     * @var bool
     */
    public $default_pages = true;
    private $loaded_data = false;

    /**
     * @return string
     */
    function get_folder()
    {
        if ($this->default_pages) return "";
        return $this->folder_pages;
    }

    /**
     * @var array
     */
    public $config_params = array();


    /**
     * This method obtein value config param of config.php
     * @param $key
     * @return mixed
     */
    function get_value_param($key)
    {
        if (!isset($this->config_params[$key])) {
            \xeki\core::fatal_error("ERROR value $key not found check config of xeki_auth");
        }
        return $this->config_params[$key];
    }

    /**
     * xeki_auth constructor.
     * @param $config
     * @param $sql
     */
    function __construct($config)
    {
        $this->config_params = $config;
        $this->sql = $sql = \xeki\module_manager::import_module('db-sql', $this->db_config);
        if (!$this->is_session_started()) {
            ini_set('session.gc_maxlifetime', 36000);
            session_set_cookie_params(36000);
            session_start();
        }


        // LOAD CONFIG FILE
        if (isset($config['encryption_method'])) $this->encryption_method = $config['encryption_method'];
        if (isset($config['table_user'])) $this->table_user = $config['table_user'];
        if (isset($config['field_id'])) $this->field_id = $config['field_id'];
        if (isset($config['field_user'])) $this->field_user = $config['field_user'];
        if (isset($config['field_password'])) $this->field_password = $config['field_password'];
        if (isset($config['field_recover_code'])) $this->field_recover_code = $config['field_recover_code'];
        if (isset($config['table_user_temp'])) $this->table_user_temp = $config['table_user_temp'];
        if (isset($config['field_id_temp'])) $this->field_id_temp = $config['field_id_temp'];
        if (isset($config['login_page'])) $this->login_page = $config['login_page'];
        if (isset($config['logged_page'])) $this->logged_page = $config['logged_page'];
        if (isset($config['register_page'])) $this->register_page = $config['register_page'];
        if (isset($config['logged'])) $this->logged = $config['logged'];
        if (isset($config['user'])) $this->user = $config['user'];
        if (isset($config['id'])) $this->id = $config['id'];

        if (isset($config['folder_pages'])) $this->folder_pages = $config['folder_pages'];
        if (isset($config['folder_base'])) $this->folder_base = $config['folder_base'];
        if (isset($config['default_pages'])) $this->default_pages = $config['default_pages'];


    }

    function check_data()
    {
        if (!$this->loaded_data) {
            $this->load_info_session();

            $this->loaded_data = true;
        }

    }

    /**
     *
     */
    function load_info_session()
    {


        // load info if exist


        if (isset($_SESSION['xeki_auth']['config']['name_space'])) {
            $this->name_space = $_SESSION['xeki_auth']['config']['name_space'];
        }
        if (!isset($_SESSION['xeki_auth']['id_user'])) {
            $_SESSION['xeki_auth']['logged'] = false;
            $_SESSION['xeki_auth']['id_user'] = -1;
            $_SESSION['xeki_auth']['created'] = time();
            $_SESSION['xeki_auth']['last_view'] = time();
            $_SESSION['xeki_auth']['user_info'] = array();

        }

        if (!isset($_SESSION['xeki_auth']['config'])) {
            $_SESSION['xeki_auth']['config'] = array();
        }

        if (!isset($_SESSION['xeki_auth']['config']['name_space'])) {
            $_SESSION['xeki_auth']['config']['name_space'] = $this->name_space;
        }


        if ($_SESSION['xeki_auth']['logged']) {

            $query = "SELECT * FROM {$this->table_user} WHERE id='" . $_SESSION['xeki_auth']['user_info']['id'] . "'";
            $info = $this->sql->query($query);
            $valid_user = $this->valid_info_from_db($info[0]);

            if ($valid_user) {
                // check and update user

                $this->logged = true;
                $this->user = $_SESSION['xeki_auth']['user_info'];
                $this->id = $_SESSION['xeki_auth']['id_user'];
                $_SESSION['xeki_auth']['last_view'] = time();
            }

        }

//        if()


    }

    /**
     *
     * @return bool
     */
    function is_session_started()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }


    /**
     *  deprecated
     */
    function loadDefaultVars()
    {

        global $_DEFAULT_AUTH_LOGIN_PAGE;
        if (!empty($_DEFAULT_AUTH_LOGIN_PAGE)) {
            $this->login_page = $_DEFAULT_AUTH_LOGIN_PAGE;
        }
    }

    /**
     * @return array
     */
    function getUserInfo()
    {
        $this->check_data();
        return $this->user;
    }


    /**
     * @return array
     */
    function get_user_info()
    {
        return $this->getUserInfo();
    }

    /**
     * @return string
     */
    function getFieldUser()
    {
        return $this->field_user;
    }

    /**
     * @return string
     */
    function getTableUser()
    {
        return $this->table_user;
    }

    /**
     * @return array
     */
    function updateUserInfo()
    {
        $info = $this->sql->query("SELECT * FROM {$this->table_user} WHERE {$this->field_id}='{$this->id}'");
        $this->user = $info[0];
        $_SESSION['xeki_auth']['user_info'] = $this->user;
        return $this->user;
    }

    /**
     * @param $user
     * @return bool
     */
    function check_by_user($user)
    {
        $user = strtolower($user);
        $_SESSION['xeki_auth']['xeki_auth::temp_check_user'] = $user;
        $query = "SELECT * FROM {$this->table_user} WHERE {$this->field_user}='{$user}'";

        $info = $this->sql->query($query);


        if (count($info) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $text
     * @return string
     */
    function encrypt($text)
    {
        return hash($this->encryption_method, $text);
    }


    function valid_info_from_db($info)
    {
        $valid_login = false;
        if ($this->name_space == "default") {
            $valid_login = true;
        } else {
            // check if user have active name_space
            if ($this->check_name_space_by_user_id($info['id'], $this->name_space)) {
                $valid_login = true;
            }

        }

        if ($valid_login) {
            $this->user = $info;

            if (!isset($_SESSION['xeki_auth'])) $_SESSION['xeki_auth'] = array();
            $_SESSION['xeki_auth']['logged'] = true;
            $_SESSION['xeki_auth']['id_user'] = $this->user['id'];
            $_SESSION['xeki_auth']['last_view'] = time();
            $_SESSION['xeki_auth']['user_info'] = $this->user;

            // check if is not activated


            return true;
        }

        return false;
    }


    /**
     * @param $user
     * @param $pass
     * @param bool|false $cleanPass
     * @return bool
     */
    function login($user, $pass, $cleanPass = false)
    {
        if (!$cleanPass)
            $pass = hash($this->encryption_method, $pass);

        // this is an ugly algorithm this work in this way for security :D
        // load info of user if exist
        $user = strtolower($user);
        $query = "SELECT * FROM {$this->table_user} WHERE {$this->field_user}='" . $user . "'";

        $info = $this->sql->query($query);
        // for check and debug


        // check if exist
        $valid_login = false;
        if ($info) if (count($info) > 0) {
            $info = $info[0];
            // check password            

//            d($info[$this->field_password]);
//            d($pass);
//            die();
            if ($info[$this->field_password] != $pass) {
                // check name_space
                return false;
            }
        }
        if ($this->valid_info_from_db($info)) {
            if (isset($_SESSION['xeki_auth']['config']['xeki_auth::post_login']))
                $this->logged_page = $_SESSION['xeki_auth']['config']['xeki_auth::post_login'];

            \xeki\core::redirect($this->logged_page);
        }

        return false;

    }

    function check_permission($permission_code)
    {
        $this->check_data();
        $has_valid_permission = false;
        // check if permission exits if not exists create
        $query = "SELECT * FROM {$this->table_permissions} WHERE code='{$permission_code}'";
        $res = $this->sql->query($query);
        $id_permission = -1;
        if (count($res) > 0) {
            $id_permission = $res[0]['id'];
        } else {
            // create permission
            $data = array(
                "code" => $permission_code,
            );
            $id_permission = $this->sql->insert($this->table_permissions, $data);
        }

        // check if user has permission


        $query = "SELECT * FROM {$this->table_permissions_ref} WHERE user_id='{$this->user['id']}' and user_permissions_id='{$id_permission}'";
        $res = $this->sql->query($query);
//        d($res);

        if (count($res) > 0) {
            $has_valid_permission = true;
        }

        // super user
//        d($this->user);
//        d($this->user['xeki_super_admin']);
        if ($this->user['xeki_super_admin'] == "on") {
            return true;
        }

        return $has_valid_permission;
    }

    /**
     * @param $name_space_name
     */
    function set_name_space($name_space_name)
    {
        $_SESSION['xeki_auth']['config']['name_space'] = $name_space_name;
        $this->name_space = $name_space_name;
    }


    /**
     * @param int $user_id
     * @param $name_space_code
     * @return bool
     */
    function check_name_space_by_user_id($user_id = -1, $name_space_code)
    {
        // valid inputs
        if ($user_id == -1) {
            \xeki\core::fatal_error("no valid id");
        }

        $name_spaces = $this->get_name_spaces_by_user_id($user_id);

        // check if user have name_space

        $id_name_space = $this->get_id_name_space($name_space_code);

        $query = "SELECT * FROM {$this->table_name_space_ref} where user_namespace_id = '{$id_name_space}' and user_id = '{$user_id}'";

        $res = $this->sql->query($query);


        if (count($res) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $user_id
     * @return array of name spaces
     */
    function get_name_spaces_by_user_id($user_id = -1)
    {
        // valid inputs
        if ($user_id == -1) {
            \xeki\core::fatal_error("no valid id");
        }

        $query = "SELECT * FROM {$this->table_name_space} where user_ref = {$user_id}";

        $name_spaces = $this->sql->query($query);
        return $name_spaces;
    }


    /**
     * @param $name_space_code
     * @return mixed
     */
    function get_id_name_space($name_space_code)
    {
        if ($name_space_code == -1) {
            \xeki\core::fatal_error("no valid name code");
        }

        $query = "SELECT * FROM {$this->table_name_space} where code = '{$name_space_code}'";
        $name_spaces = $this->sql->query($query);
        if (count($name_spaces) > 0) {
            return $name_spaces[0]['id'];
        } else {
            $data = array(
                "code" => $name_space_code,
            );
            $id = $this->sql->insert($this->table_name_space, $data);
            return $id;

        }
    }

    /**
     * @param $user
     * @param $pass
     * @param array $extra_data
     * @return bool|int|string
     */
    function secure_register($user, $pass, $extra_data = array())
    {

        // valid if user exist
        $user = strtolower($user);
        $data = array(
            $this->field_user => $user,
            $this->field_password => hash($this->encryption_method, $pass),
        );
        $data = array_merge($data, $extra_data);

//        $data['activated'] = "off";
        $confirm_account = $this->get_value_param("confirm_account");
        if ($confirm_account) {
            $_CODE = hash($this->encryption_method, rand(-9999999, 999999));
            $data['confirm_code'] = $_CODE;
            $data['activated'] = "off";

            // send email
            $this->send_email_confirm_account($user, $_CODE, $data);
        } else {
            $data['confirm_code'] = "confirmed";
        }

        $welcome_account = $this->get_value_param("welcome_account");
        if ($welcome_account) {

            $this->send_email_welcome($user, $data);
        }


        // check if user exist

        // if not exist

        $res = $this->sql->insert($this->table_user, $data);
        if ($res) return $res;
        return false;
    }


    function send_email_welcome($email, $extra_data = array())
    {


        $mail = \xeki\module_manager::import_module("xeki_mail");
        $xeki_i18n = \xeki\module_manager::import_module('xeki_i18n');

//            $_CODE
        // TODO CLEAN THIS create path absolute
        $mail_confirm_account_route = $this->get_value_param("welcome_route_mail");
        $path_file = dirname(__FILE__) . "/../../../../$mail_confirm_account_route";

        if (!file_exists($path_file)) {
            d($path_file);
            d("not exist");
            die();

        }

        $content = file_get_contents($path_file);

        $url_base = \xeki\core::$URL_BASE_COMPLETE;

        $subject = $this->get_value_param("welcome_subject");
        $subject = $xeki_i18n->process_i18n_info($subject);
        $copies = $this->get_value_param("welcome_copies");
        $copies['subject'] = $subject;


        $copies = $xeki_i18n->process_i18n_info($copies);
        $extra_data = array_merge($extra_data, $copies);


        $mail->send_email($email, $copies['subject'], $content, $extra_data);
        return true;


    }


    function send_email_confirm_account($email, $code, $extra_data = array())
    {

        $confirm_account_url = $this->get_value_param("confirm_account_route_url");
        $mail = \xeki\module_manager::import_module("xeki_mail");

//            $_CODE
        // TODO CLEAN THIS create path absolute
        $mail_confirm_account_route = $this->get_value_param("confirm_account_route_mail");
        $path_file = dirname(__FILE__) . "/../../../../$mail_confirm_account_route";

        if (!file_exists($path_file)) {
            d($path_file);
            d("not exist");
            die();
            $path_file = dirname(__FILE__) . "/pages/mail/recover_pass.html";
        }

        $content = file_get_contents($path_file);

        $url_base = \xeki\core::$URL_BASE_COMPLETE;
        $extra_data['url'] = $url_base . $confirm_account_url . '/' . $code;

        $subject = $this->get_value_param("confirm_account_subject");
        $copies = $this->get_value_param("confirm_account_copies");
        $copies['subject'] = $subject;

        $xeki_i18n = \xeki\module_manager::import_module('xeki_i18n');
        $copies = $xeki_i18n->process_i18n_info($copies);
        $extra_data = array_merge($extra_data, $copies);

        $mail->send_email($email, $copies['subject'], $content, $extra_data);
        return true;

    }

    /**
     * @param $user
     * @return mixed
     */
    public function get_info_by_email($user)
    {
        $res = $this->sql->query("SELECT * FROM {$this->table_user} where {$this->field_user}='{$user}'");
        return $res[0];
    }

    /**
     * @param $user
     * @return string
     */
    public function set_code_recover($user)
    {
        $_CODE = hash($this->encryption_method, rand(-9999999, 999999));
        $data = array(
            $this->field_recover_code => $_CODE,
        );
        $res = $this->sql->update($this->table_user, $data, "$this->field_user='$user'");
        return $_CODE;
    }

    /**
     * @param $user
     * @return string
     */
    public function set_pass_by_code_recover($code_recover, $password)
    {
        // get user by code
        $new_pass = hash($this->encryption_method, $password);

        $query = "SELECT {$this->field_user} FROM {$this->table_user} WHERE {$this->field_recover_code}='$code_recover'";
        $res = $this->sql->query($query);
        if (!$res) {
            return false;
        }
        $user = $res[0];

        $data = array(
            $this->field_password => $new_pass,
            $this->field_recover_code => ''
        );
        $res = $this->sql->update($this->table_user, $data, " {$this->field_user} = '{$user[$this->field_user]}'");
        if (!$res) {
            return false;
        }
        return true;
    }


    #return if islogged and redirect to $this->logged_page if is logged

    /**
     * @param $user
     * @param $pass
     * @return bool
     */
    function secure_set_pass($user, $pass)
    {
        $data = array(
            $this->field_user => $user,
            $this->field_password => hash($this->encryption_method, $pass),
            $this->set_password => 'on'
        );
        $res = $this->sql->update($this->table_user, $data, "$this->field_user='$user'");
        return $res;
    }

    /**
     * @param int $level
     * @return bool
     */
    function check_auth($action = "")
    {
        $this->check_data();
        // todo validate $action
        if ($action != "") {

        }

        // last check for only logged
        if ($this->logged) {
//            d("logged");
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    function checkLogin()
    {
        $this->check_data();

        // check redirect page


        if ($this->check_auth()) return true;
        else {

            if (strpos($_SERVER['REDIRECT_URL'], $this->login_page) !== false) {
                // loop redirect
                \xeki\core::redirect('');
            } else {
                \xeki\core::redirect($this->login_page . '?from=' . $this->logged_page);
            }


        }
    }


    // alias for checkLogin

    /**
     * @return bool
     */
    function check_login()
    {
        return $this->checkLogin();
    }

    /**
     * @return bool
     */
    function check_logged()
    {
        return $this->checkLogin();
    }


    /**
     * @param string $action
     */
    function is_auth($action = "")
    {
        $this->check_auth();
    }

    /**
     * @param int $level
     * @return bool
     */
    function pageLoginCheck($level = 1)
    {
        $this->check_data();
        $this->logged_page;
        if ($this->logged) \xeki\core::redirect($this->logged_page);
        return false;

    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        return $this->logged;
    }


    /**
     * @param $item
     * @param $value
     */
    public function setValue($item, $value)
    {
        $_SESSION['xeki_auth'][$item] = $value;
    }

    /**
     * @param $item
     * @return string
     */
    public function getValue($item)
    {
        return empty($_SESSION['xeki_auth'][$item]) ? '' : $_SESSION['xeki_auth'][$item];
    }

    /**
     *
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * @param $table_user
     */
    public function change_table_user($table_user)
    {
        $this->table_user = $table_user;
    }

    /**
     * @param $field_id
     */
    public function change_field_id($field_id)
    {
        $this->field_id = $field_id;
    }

    /**
     * @param $field_user
     */
    public function change_field_user($field_user)
    {
        $this->field_user = $field_user;
    }

    /**
     * @param string $url_login
     */
    public function change_login_url($url_login = "")
    {
        // valid method
        if ($url_login == '') return;
        $this->logged_page = $url_login;
    }

    /**
     * @param $page
     */
    public function set_logged_page($url_post_login)
    {
        if ($url_post_login != '') {
            $_SESSION['xeki_auth']['config']['xeki_auth::post_login'] = $url_post_login;
            $this->logged_page = $url_post_login;
        }
    }

    /**
     * @param $page
     */
    public function set_login_page($page)
    {
        $this->login_page = $page;
    }

    /**
     * @param string $url_post_login
     */
    public function go_to_login()
    {
        \xeki\core::redirect($this->login_page);
    }


    /**
     * @param string $url_post_login
     */
    public function go_to_logged()
    {
        \xeki\core::redirect($this->logged_page);
    }


    /**
     * @param string $url_post_login
     */
    public function go_to_register($url_post_login = "")
    {
        if ($url_post_login != '') {
            $_SESSION['xeki_auth']['config']['xeki_auth::post_login'] = $url_post_login;
            $this->login_page = $url_post_login;
        }
        \xeki\core::redirect($this->register_page);
    }

    public function is_super_admin()
    {
        $this->check_data();
        if ($this->user['xeki_super_admin'] == "on") {
            return true;
        } else {
            return false;
        }
    }
}