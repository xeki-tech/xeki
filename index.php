<?php

/**
 * xeki FRAMEWORK : Main INDEX
 * Version 0.12
 */
// cli ARGS



$_DEBUG_MODE = true;
$_DEFAULT_PAGE_ERROR = '_default_error.php';
$_SYSTEM_PATH_BASE = dirname(__FILE__);


// load base library
require_once('libs/xeki_util_methods.php');

require_once('libs/xeki_core/main_core.php');

\xeki\core::$SYSTEM_PATH_BASE = $_SYSTEM_PATH_BASE;

// load security library

require_once('libs/xeki_core/security.php');

\xeki\security::init();


\xeki\core::init();
// die();


set_error_handler('errorHandlexeki');
register_shutdown_function('errorHandlexeki');
function errorHandlexeki()
{
    global $_DEBUG_MODE;
    global $_DEFAULT_PAGE_ERROR;
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($_DEBUG_MODE && isset($error['type'])) {
        if ($error['line'] != 0) {
            d($error);
        }
    }

    if ($error['type'] === 64) {## handle errors
        require("core/controllers/$_DEFAULT_PAGE_ERROR");
        die();
    }
}

//trigger_error("Cannot divide by zero", E_USER_ERROR);

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Bogota');

// option origin valid


## CORS for WS comment this for security
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day

    if(isset($_GET['__amp_source_origin'])){
        header('AMP-Access-Control-Allow-Source-Origin: '.urldecode($_GET['__amp_source_origin']));
    }
    else{
        header("AMP-Access-Control-Allow-Source-Origin: {$_SERVER['HTTP_ORIGIN']}");
    }
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


## global config
//ini_set('session.gc_maxlifetime', 400000);
//ini_set('session.cookie_lifetime', 4000000);

$_ARRAY_RUN_END=array();
## general project
require_once('core/config.php');

## CHECK FORCE SSL
// if is not ssl and
if ($AG_FORCE_SSL){
    // d($_SERVER);
    $redirect_to_ssl = false;
    if(isset($_SERVER['HTTP_CF_VISITOR'])){ #for cloudflare ssl
        $info_cf = json_decode($_SERVER['HTTP_CF_VISITOR'],true);
        if($info_cf['scheme']=="http"){
            $redirect_to_ssl = true;
        }
    }
    else if ( !isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
        $redirect_to_ssl = true;
    }
    // valid domain
    if($redirect_to_ssl){
        $temp_len = count($AG_SSL_DOMAINS);
        if($temp_len>0){
            $redirect_to_ssl=false;
            foreach($AG_SSL_DOMAINS as $item){
                if($_SERVER['HTTP_HOST']==$item)$redirect_to_ssl=true;
            }
        }
    }
    if($redirect_to_ssl){
        $to="https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: " . $to);
        echo '<meta http-equiv="refresh" content="0;URL="' . $to . '"/>';
        echo '<script>window.location.replace("' . $to . '");</script>';
        exit();
    }
}

## Check compress for domains
$temp_len = count($COMPRESS_DOMAIN);
if($temp_len > 0){
    foreach($COMPRESS_DOMAIN as $item){
        if($_SERVER['HTTP_HOST']==$item){
            $_DEBUG_MODE=false;
        }
    }
}


define("DEBUG_MODE", $_DEBUG_MODE, true);

// Enable error reporting from config
if ($_DEBUG_MODE) error_reporting(E_ALL);
else error_reporting(0);

## is like a print but for web


##


### url analyzer ----------------------------------
// URL
require_once('libs/xeki_core/http_request.php');
require_once('libs/xeki_core/routes.php');
$AG_HTTP_REQUEST = new \xeki\http_request();

$path_html = "$_SYSTEM_PATH_BASE/core/pages/";## this update by modules
$path_cache = "$_SYSTEM_PATH_BASE/cache/pages/";## this update by modules

//  check auto load 

if(!file_exists('libs/vendor/autoload.php')){
    d("Run composer, <br>More details https://xeki.io/php/composer");
    die();
}
require_once('libs/vendor/autoload.php');
require_once('libs/xeki_core/html_manager.php');
$AG_HTML = new \xeki\html_manager($path_html,$path_cache);

// load Module
require_once('libs/xeki_core/module_manager.php');

$MODULE_CORE_PATH = "$_SYSTEM_PATH_BASE/core/";


if(isset($argv)){
//    var_dump($argv);

    if(isset($argv[1]))$type=$argv[1];else $type=false;
    if(isset($argv[2]))$type_2=$argv[2];else $type_2=false;

    if($type=='setup'){

        if($type_2=='full' || !$type_2){
            \xeki\module_manager::setup_cli();
        }
    }
    else if($type=='add'){

        if(empty($type_2)){
            d("empty module");
        }
        else{
            $repo = "https://github.com/xeki-framework/{$type_2}.git";

            $type_2 = str_replace("-module","",$type_2);
            $type_2 = str_replace("php-","",$type_2);
            exec("git clone $repo modules/$type_2");
            \xeki\module_manager::setup_cli($type_2);
        }

    }
    else if($type=='run'){
        d("Xeki php server testing: no use for production");
        d("Server start");
        d("http://localhost:8080");
        $debug = exec("php -S localhost:8080");
        d("Server end");
        d($debug);

    }
    else{
        d("no valid command type help");
    }


    die();
}



// Global params for controllers
$AG_BASE = $AG_HTML->AG_BASE;
$AG_BASE_COMPLETE = $AG_HTML->AG_BASE_COMPLETE;
$AG_PARAMS = $AG_HTML->AG_PARAMS;
$AG_L_PARAM = $AG_HTML->AG_L_PARAM;


// End Global params

if ($_RUN_START_MODULES) $AG_MODULES->run_start();
if (is_array($_ARRAY_RUN_START))
    foreach ($_ARRAY_RUN_START as $item) {
        require_once "modules/$item/run_start.php";
    }


// script loop run start

// script loop run end

//d($AG_BASE);
//d($AG_PARAMS);
//d($AG_L_PARAM);
//d($AG_BASE_COMPLETE);
// $routes = $AG_HTTP_REQUEST->process_request();// deprecated action methods works like urls 


foreach ($GLOBAL_VARS as $key => $value) {
    \xeki\html_manager::add_extra_data( $key , $value);
}

// auto ( default )
// cached
// Set base static files 


if($_STATIC_FILES=='auto' || empty($_STATIC_FILES)){
    $route =\xeki\core::set_static_files_route();
    \xeki\html_manager::add_extra_data( "url_static_files" , $route);
}
else{
    \xeki\html_manager::add_extra_data( "url_static_files" , $_STATIC_FILES);
}

\xeki\module_manager::xeki_load_core($MODULE_CORE_PATH);
\xeki\module_manager::load_modules_url();
## launch request;
//$routes = $AG_HTTP_REQUEST->getRoutes();
//d($routes);
// fix match

$match = \xeki\routes::process_actions();
//$match = $AG_HTTP_REQUEST->launch_controller();
$match = \xeki\routes::process_routes();
// load core
// load modules

if ($_RUN_END_MODULES) $AG_MODULES->run_end();
if (is_array($_ARRAY_RUN_END))
    foreach ($_ARRAY_RUN_END as $item) {
        require_once "modules/$item/run_end.php";
    }


## launch error page if not redered some page

// please move this to core
if (!\xeki\html_manager::$done_render) {

    require_once("./core/controllers/$_DEFAULT_PAGE_ERROR");

}

## libs

### urls analyzer  FINISH -----------------------------------

### POST analyzer  FINISH -----------------------------------

