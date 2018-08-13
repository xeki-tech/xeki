<?php

namespace xeki;

use FastRoute;// lib of routes

class routes
{
    const separator = "-:)(:-";// is a ridiculous separator for not generate config with other tags

    public static $main_object = null;
    private static $vars;

    public $REQUEST = false;

    public $AG_SERVER = array();


    public $xeki_action = "";
    public $IS_xeki_action = "";
    public $AG_VALUES = "";


    public $AG_ROUTING_DICTIONARY = array();

    public $AG_REQUEST_DICTIONARY = array();

    /**
     * @var array Array of all routes (incl. named routes).
     */
    protected static $routes = array();

    /**
     * @var array Array of all actions (incl. named actions).
     */
    protected static $actions = array();

    /**
     * @var array Array of all named routes.
     */
    protected $namedRoutes = array();

    /**
     * @var string Can be used to ignore leading part of the Request URL (if main file lives in subdirectory of host)
     */
    protected $basePath = '';

    /**
     * http_request constructor.
     */
    public function __construct()
    {

        global $_BASE_PATH;
        $basePath = $_BASE_PATH;
        if (self::$main_object == null) {
            $this->AG_SERVER = $_SERVER;
            ## Analyze url
            $this->setBasePath($basePath);

            ## Analyze request
            self::$main_object = $this;
        } else {
            $temp = self::$main_object;

        }
    }

    /**
     * Set the base path.
     * Useful if you are running your application from a subdirectory.
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }



    public static function addRoute($method,$url,$handler,$module=''){
        // save to array
        $new_item = array(
            'method'=>$method,
            'url'=>$url,
            'handler'=>$handler,
            'module'=>$module,
        );
        array_push(self::$routes,$new_item);
    }
    public static function process_actions(){

        // check if have $_POST['xeki_action'];
        if(isset($_POST['xeki_action'])){
            $action_code = $_POST['xeki_action'];
            unset($_POST['xeki_action']);
            foreach (self::$actions as $action){
                // check
                if($action_code == $action['action']){
                    // run handler
                    $action['handler']();
                }

            }
        }

    }
    public static function process_routes(){

        // load active modules routes

        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            // load saved routes
            foreach (self::$routes as $route){
                //
                if(is_array($route['method'])){
                    $new_method = array();
                    foreach ($route['method'] as $inner_method){
                        $inner_method = strtoupper($inner_method);
                        array_push($new_method,$inner_method);
                    }
                    $route['method'] = $new_method;
                }
                else{
                    $route['method'] = strtoupper($route['method']);
                }

                if($route['module']!=''){
                    $route['handler']=$route['module'].self::separator.$route['handler'];
                }
                try{
                    $r->addRoute($route['method'], $route['url'], $route['handler']);
                }
                catch (FastRoute\BadRouteException $e){
                    d("duplicate url ");
                    d($route['method']);
                    d($route['url']);
                    d($route['module']);

                    d($e);
                }
                catch (Exception $e) {
                    d($e);
                }

            }
        });

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = \xeki\core::$URL_REQUEST;

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }


        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
//        die();
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                // set module
                $vars = $routeInfo[2];
                $controller=$handler;
                $module = 'core';

                if (is_callable ( $handler )){
                    $controller=$handler;
                }
                elseif(strpos($handler,self::separator)!==false){
                    $items=explode(self::separator,$handler);
                    $controller=$items[1];
                    $module=$items[0];
                }
                self::run_handler($controller,$vars,$module);
                // ... call $handler with $vars
                break;
        }
//        die();
    }

    private function run_handler($handler,$vars,$module){
        // is controller
//        d($handler);
//        d($vars);
//        die();
        if (is_callable ( $handler )){
//
            $handler($vars);
        }
        else{
            self::run_controller($handler,$vars,$module);
        }


        // is function

    }
    private function run_controller($handler,$vars,$module){
        $_SYSTEM_PATH_BASE=\xeki\core::$SYSTEM_PATH_BASE;


        // process info $route
        $not_found = false;## found by main urls;
        $controller = $handler;
        $module = $module;

        // controllers


        if($module==""){
            $module="core";
        }
        if ($module != "core"){
            $main_module = \xeki\module_manager::xeki_module_get_main($module);
            $main_module->set_up_pages();
            $module = "modules/$module/core";
        }


        if (strpos($controller, '.php') == false)
            $controller .= ".php";

        \xeki\routes::$vars=$vars;
        $vars = $vars;
        require_once("$_SYSTEM_PATH_BASE/$module/controllers/$controller");

        if ($not_found) {
            $module = "core";
            $file_not_controller_core = "$_SYSTEM_PATH_BASE/$module/controllers/_not_controller_pages.php";
            if (file_exists($file_not_controller_core)) {
                require_once($file_not_controller_core);
            }
        }

        //modules inners urls

        if ($not_found ) {
            $this->routes = array(); # reset routes
            \xeki\module_manager::load_modules_url();
//            d($this->routes);
            $match = $this->match();
            if ($match) {
                $not_found = false;## found by main urls;
                $controller = $match['target']['controller'];
                $module = $match['target']['module'];
//                d($controller);
                //    d($module);
                $main_module = \xeki\module_manager::xeki_module_get_main($module);
                $main_module->set_up_pages();

                if ($module != "core")
                    $module = "modules/$module/core";

                if (strpos($controller, '.php') == false)
                    $controller .= ".php";
                // object of module


//                d("$_SYSTEM_PATH_BASE/$module/controllers/$controller");

                require_once("$_SYSTEM_PATH_BASE/$module/controllers/$controller");
            }
        }
    }

    public static function any($url,$handler,$module=''){
        self::addRoute(['GET', 'POST',"DELETE","PUT","PATCH"],$url,$handler,$module);
    }
    public static function get($url,$handler,$module=''){
        self::addRoute("GET",$url,$handler,$module);
    }

    public static function post($url,$handler,$module=''){
        self::addRoute("POST",$url,$handler,$module);
    }
    public static function delete($url,$handler,$module=''){
        self::addRoute("DELETE",$url,$handler,$module);
    }
    public static function put($url,$handler,$module=''){
        self::addRoute("PUT",$url,$handler,$module);
    }
    public static function patch($url,$handler,$module=''){
        self::addRoute("PATCH",$url,$handler,$module);
    }


    public static function head($url,$handler,$module=''){

    }

    public static function options($url,$handler,$module=''){

    }

    public static function action($action,$handler){

        // add to list action
        $new_item = array(
            'action'=>$action,
            'handler'=>$handler,
        );
        array_push(self::$actions,$new_item);

    }







    
}