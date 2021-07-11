<?php
namespace xeki_csrf;


/**
 * Class csrf-module
 * @package xeki_csrf
 * version 1
 */

class xeki_csrf
{
    private $encryption_method = 'sha256';


    private $sql;
    private $user=null;
    private $db_config="main";

    function get_value_param($key)
    {
        if (!isset($this->config_params[$key])) {
            \xeki\core::fatal_error("ERROR value $key not found check config of xeki_csrf");
        }
        return $this->config_params[$key];
    }

    function __construct($config)
    {
        $this->config_params = $config;

        $lifetime = 3600000;
        ini_set('session.name', 'session_id');
        ini_set('session.gc_maxlifetime', $lifetime);
        session_set_cookie_params(time()+$lifetime);

        // set params

        if (isset($config['encryption_method'])) $this->encryption_method = $config['encryption_method'];
        if (isset($config['db_config'])) $this->db_config = $config['db_config'];


        $this->local_config =
        [
            "encryption_method"=>$this->encryption_method,
            "db_config"=>$this->db_config,
        ];

        $this->sql = \xeki\module_manager::import_module('db-sql', $this->db_config);
        // Create a new session if necessary

        // TODO handling better this error
        @session_start() or die(); // Don't output anything on invalid cookie forgery attempts


        // check if csrf_cookie is empty
        if (empty($_COOKIE['csrf_cookie'])){

            $sessionKey = $this->generate_session_key();


            setcookie("csrf_cookie", $sessionKey, time()+$lifetime,\xeki\core::$URL_BASE);
            $_SESSION['csrf_cookie'] = $sessionKey;



//            $res = setcookie("csrf_cookie", $sessionKey, time()+$lifetime, \xeki\core::$URL_BASE, $_SERVER['REQUEST_URI'], 1);
//            d($res);

        }
        else{
            // renew cookie
            setcookie("csrf_cookie", $_COOKIE['csrf_cookie'], time()+$lifetime,\xeki\core::$URL_BASE);
        }
    }

    function generate_session_key(){
        // Generate a random lowercase alphanumeric string
        $sessionKey = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 8)), 0, 80);
        $sessionKey = time().$sessionKey;
        return $sessionKey;
    }

    function get_token(){
        $token = hash($this->encryption_method,time()+"_"+$_SERVER['DOCUMENT_ROOT']);
        $data = [
            "token"=>$token,
            "cookie"=>$_COOKIE['csrf_cookie']
        ];
        $this->sql->insert('csrf_token',$data);
        return $token;
    }

    function get_token_html(){
        $time = time();
        $token = hash($this->encryption_method,+"{$time}_{$_SERVER['DOCUMENT_ROOT']}");
        $data = [
            "token"=>$token,
            "cookie"=>$_COOKIE['csrf_cookie']
        ];
        $this->sql->insert('csrf_token',$data);
        $token_html = <<<HTML
            <input name="csrf_token" value="{$token}" type="hidden">
HTML;

        return $token_html;
    }

    function validate_token($token=false){
        if($token===false){
            if(isset($_POST['csrf_token'])){
                $token=$_POST['csrf_token'];
            }
            if(isset($_GET['csrf_token'])){
                $token=$_POST['csrf_token'];
            }
        }
        $token = $this->sql->sanitize($token);
        $cookie = $this->sql->sanitize($_COOKIE['csrf_cookie']);
        $query = "select * from csrf_token where token='{$token}' and cookie='{$cookie}'";

        if(is_array($res = $this->sql->query($query))){
            if(count($res)>0){
                return true;
            }
        }
        else{
            return false;
        }
    }



}