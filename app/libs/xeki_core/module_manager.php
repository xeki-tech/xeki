<?php
/**
 * CORE xeki - MODULE MANAGER
 * This class manage import and inicializate of modules
 * Firts Creation
 * User: Luis Eduardo
 * Date: 2/27/2016
 * Time: 9:04 PM
 */

namespace xeki;


/**
 * Class module_manager
 * @package xeki
 */
class module_manager
{

    /**
     * @var array
     */
    private static $cache_modules = array();


    /**
     * module_manager constructor.
     */
    public function __construct()
    {

    }


    /**
     * Error for xeki_module_error();
     * Just run something
     */
    private static function xeki_module_error($module_name)
    {
        d('Module dont finded ' . $module_name);
        die();
    }

    /**
     * @param $PATH
     */
    public static function xeki_load_core($PATH)
    {
        global $AG_HTTP_REQUEST;
        global $AG_MODULES;
        global $html;

        require_once "$PATH/url.php";
        require_once "$PATH/main.php";
        require_once "$PATH/action_methods.php";

    }


    /**
     * @param $module_name
     * @param string $module_config
     * @return bool
     */
    ##
    /**
     * Alias of import_module
     * @param $module_name
     * @param string $module_config
     * @return bool
     */
    public static function xeki_module_import($module_name, $module_config = "main")
    {
        return self::import_module($module_name, $module_config);
    }

    /**
     * Import a module
     * @param $module_name
     * @param string $module_config
     * @return bool
     */

    public static function import_module($module_name, $module_config = "main", $custom_variables = array())
    {
        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        $_PATH_MODULES_VENDOR = dirname(__FILE__) . "/../../libs/vendor";
        $_PATH_CORE = dirname(__FILE__) . "/../../core/modules_config";

        // this help to ux of code :)
        if (is_array($module_config)) {
            // enable custom variables and module config is main
            $custom_variables = $module_config;
            $module_config = "main";
        }

        // check folders and auto complete
        // cache system for recycle initialised modules
        $var_loaded_modules = self::$cache_modules;
        if (!isset($var_loaded_modules[$module_name])) self::$cache_modules[$module_name] = array();

        // if module was inicializate return module
        if (isset($var_loaded_modules[$module_name][$module_config])) {
            return $var_loaded_modules[$module_name][$module_config];
        }

        // if #modules_config is array run inline config 7
        // load config dfault or custom and rewrite with array gived

        $_MAIN_MODULE = "$_PATH_MODULES/$module_name/main.php";
        $_MAIN_MODULE_TRY_1 = "$_PATH_MODULES_VENDOR/xeki-tech/$module_name/main.php";
        $_MAIN_MODULE_TRY_2 = "$_PATH_MODULES_VENDOR/module/main.php";

        // get config of module
        $MODULE_DATA_CONFIG = self::get_config_array($module_name, $module_config, $custom_variables);


        // exist mail module
        $object = false;
        if (file_exists($_MAIN_MODULE)) {
            $AG_MAIN = self::getMainModule($module_name, $_MAIN_MODULE, $MODULE_DATA_CONFIG);
            $object = $AG_MAIN->getObject();
        } else if (file_exists($_MAIN_MODULE_TRY_1)) {
            $AG_MAIN = self::getMainModule($module_name, $_MAIN_MODULE_TRY_1, $MODULE_DATA_CONFIG);
            $object = $AG_MAIN->getObject();
        } else if (file_exists($_MAIN_MODULE_TRY_2)) {
            $AG_MAIN = self::getMainModule($module_name, $_MAIN_MODULE_TRY_2, $MODULE_DATA_CONFIG);
            $object = $AG_MAIN->getObject();
        }

        $_MAIN_MODULE_COMMON = '';
        if (!$object) {
            if (file_exists($_MAIN_MODULE_COMMON)) {
                $AG_MAIN = '';
                require($_MAIN_MODULE);
                $is_ok = false;
                if ($AG_MAIN->check())
                    if ($AG_MAIN->init($module_config))
                        $is_ok = true;

                if (!$is_ok) {
                    self::xeki_module_error($module_name . " no init");
                }
            }
        }

        // not found the module launch error
        if (!$object) {
            self::xeki_module_error($module_name . " no object");
        }

        // save cache module info
        self::$cache_modules[$module_name][$module_config] = $object;
        return $object;
    }


    /**
     * For http request get the main input FOR urls
     * @param $module_name
     * @param string $module_config
     * @return bool
     */
    public static function xeki_module_get_main($module_name, $module_config = "main", $custom_variables = array())
    {
        // global methods
        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        $_PATH_CORE = dirname(__FILE__) . "/../../core/modules_config";
        $_MAIN_MODULE = "$_PATH_MODULES/$module_name/main.php";

        //
        // config block TODO create new method
        //


        $MODULE_DATA_CONFIG = self::get_config_array($module_name, $module_config, $custom_variables);

        //
        // End config block
        //

        //        d($_MAIN_MODULE);
        //        d($_MAIN_MODULE_CONFIG);
        $AG_MAIN = false;
        if (file_exists($_MAIN_MODULE)) {
            $AG_MAIN = self::getMainModule($module_name, $_MAIN_MODULE, $MODULE_DATA_CONFIG);
        }

        if (!$AG_MAIN) {
            self::xeki_module_error($module_name . "no main config");
        }

        return $AG_MAIN;

    }


    /**
     * @param $module_name
     * @param $_MAIN_MODULE
     * @param $_MAIN_MODULE_CONFIG
     * @param $module_config
     * @return mixed
     */
    public static function getMainModule($module_name, $_MAIN_MODULE, $MODULE_DATA_CONFIG)
    {
        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        $_PATH_MODULES_VENDOR = dirname(__FILE__) . "/../../libs/vendor";

        // import config

        $__module_name = false;
        $__config_path = false;
        $__name_space = false;
        $__modules_required = false;

        $_config = "$_PATH_MODULES/$module_name/_module.php";
        $_config_TRY_1 = "$_PATH_MODULES_VENDOR/xeki-tech/$module_name/_module.php";
        $_config_TRY_2 = "$_PATH_MODULES_VENDOR/module/_module.php";


        $object = false;
        if (file_exists($_config)) {
            require($_config);
        } else if (file_exists($_config_TRY_1)) {
            require($_config_TRY_1);
        }
        else if (file_exists($_config_TRY_2)) {
            require($_config_TRY_2);
        }

        if (!class_exists("$__name_space\main", false)) {
            require_once $_MAIN_MODULE;
        }
        $class_name = "$__name_space\main";
        $AG_MAIN = new $class_name;

        $is_ok = false;
        if ($AG_MAIN->check()) {
            ## TODO for errors here create a handling
        }
        if ($AG_MAIN->init($MODULE_DATA_CONFIG))
            $is_ok = true;

        if (!$is_ok)
            self::xeki_module_error($module_name . "_ no init 242");

        return $AG_MAIN;
    }


    /*
    * Return array of config
    */
    public static function get_config_array($module_name, $module_config, $custom_variables)
    {

        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        $_PATH_CORE = dirname(__FILE__) . "/../../core/modules_config";

        // get module config
        $MODULE_DATA_CONFIG = array();
        $_MAIN_MODULE_CONFIG = false;

        if (!$_MAIN_MODULE_CONFIG) {
            $file_route_module = "{$_PATH_CORE}/{$module_name}/html-twig.php";
            if (file_exists($file_route_module)) {
                $_MAIN_MODULE_CONFIG = $file_route_module;
            }
        }


        if (!$_MAIN_MODULE_CONFIG) {
            $file_route_module = "{$_PATH_CORE}/{$module_name}-module/html-twig.php";
            if (file_exists($file_route_module)) {
                $_MAIN_MODULE_CONFIG = $file_route_module;
            }
        }


        if ($_MAIN_MODULE_CONFIG !== false) {
            require($_MAIN_MODULE_CONFIG);
        } else {
            d("Config not found {$module_name}<br> Run php index.php setup, <br>More details https://xeki.io/php/setup");
            die();
        }


//      OLD MERGE configs default and inner module
//        $TEMP_MAIN_MODULE = $MODULE_DATA_CONFIG;
//        $TEMP_MAIN_MODULE = $TEMP_MAIN_MODULE["main"];
//
//        #default path custom
//
//
//        #custom TODO create this for
//        if (file_exists("{$_PATH_CORE}/{$module_name}/html-twig.php")) {
//            $_MAIN_MODULE_CONFIG = "{$_PATH_CORE}/{$module_name}/html-twig.php";
//            require($_MAIN_MODULE_CONFIG);
//        }
//
//
//        $MODULE_DATA_CONFIG = $MODULE_DATA_CONFIG[$modules_config];
//
//
//        $MODULE_DATA_CONFIG = array_merge($TEMP_MAIN_MODULE, $MODULE_DATA_CONFIG);
        // d($MODULE_DATA_CONFIG);

        $MODULE_DATA_CONFIG = $MODULE_DATA_CONFIG[$module_config];
        // load array of custom config
        foreach ($custom_variables as $key => $item) {
            $MODULE_DATA_CONFIG[$key] = $item;
        }


        return $MODULE_DATA_CONFIG;
    }

    /**
     *
     */
    public function launch_xeki_action_method()
    {
        global $AG_MODULES;
        global $html;
        global $URL_BASE_COMPLETE;
        global $AG_L_PARAM;
        global $URL_BASE;


        $_PATH_MODULES = "core";
        $_MAIN_MODULE = "$_PATH_MODULES/action_methods.php";
        $_MAIN_MODULE_CONFIG = "$_PATH_MODULES/html-twig.php";

        $object = false;
        if (file_exists($_MAIN_MODULE)) {
            $AG_MAIN = '';
            require($_MAIN_MODULE);
        }
        // action_methods
        $this->run_files_pattern_modules("/core/action_methods.php");


    }


    /**
     * @param $file
     */
    public static function run_files_pattern_modules($file)
    {
        global $AG_MODULES;
        global $html;
        global $AG_HTTP_REQUEST;
        global $URL_BASE_COMPLETE;
        global $AG_L_PARAM;
        global $URL_BASE;


        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        $folders_modules = scandir($_PATH_MODULES);
        foreach ($folders_modules as $folder) {
            if ($folder !== '.' && $folder !== '..' && $folder !== '_common')
                $file_objective = "{$_PATH_MODULES}/{$folder}/{$file}";
            if (file_exists($file_objective)) {
                require($file_objective);
            }
        }

    }

    /**
     *  run
     */
    public function run_start()
    {

        $this->run_files_pattern_modules("run_start.php");
        // load files in modules
    }

    /**
     *
     */
    public function run_setup_db()
    {

        $this->run_files_pattern_modules("setup_db.php");
        // load files in modules
    }

    /**
     *
     */
    public function run_end()
    {
        $this->run_files_pattern_modules("run_end.php");
        // load files in modules
    }

    /**
     *
     */
    public static function load_modules_url()
    {
        global $_ARRAY_MODULES_TO_LOAD_URLS;
        global $AG_MODULES;
        global $html;
        global $URL_BASE_COMPLETE;
        global $AG_L_PARAM;
        global $URL_BASE;
        global $AG_HTTP_REQUEST;

        $_PATH_MODULES = dirname(__FILE__) . "/../../modules";
        // d($_ARRAY_MODULES_TO_LOAD_URLS);
        // get if we have
        // load urls all
        if (is_array($_ARRAY_MODULES_TO_LOAD_URLS)) {
            if (count($_ARRAY_MODULES_TO_LOAD_URLS) > 0) {
                foreach ($_ARRAY_MODULES_TO_LOAD_URLS as $item) {

                    // load urls
                    $file_objective = "{$_PATH_MODULES}/{$item}/core/url.php";
                    // d($file_objective);
                    if (file_exists($file_objective)) {
                        // d("run start $file_objective");
                        require($file_objective);
                    }
                    // load actions
                    $file_objective = "{$_PATH_MODULES}/{$item}/core/action_methods.php";
                    // d($file_objective);
                    if (file_exists($file_objective)) {
                        // d("run start $file_objective");
                        require($file_objective);
                    }
                }
            } else {
                self::run_files_pattern_modules("/core/url.php");
            }
        }

    }

    public static function setup_cli($module = false)
    {
        $_PATH_MODULES = dirname(__FILE__) . "/../../modules/";
        $folders_modules = scandir($_PATH_MODULES);
        foreach ($folders_modules as $folder) {
            if ($folder !== '.' && $folder !== '..' && $folder !== '_common') {

                if ($module == false || $module == $folder) {
                    $file_objective = "{$_PATH_MODULES}/{$folder}/cli_setup.php";
                    d("Setup Module: " . $folder);
                    if (file_exists($file_objective)) {
                        require($file_objective);
                    } else {
                        d("-- No file setup");
                    }
                }


            }

        }

    }
}