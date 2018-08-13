<?php
/**
 * Created by PhpStorm.
 * User: Luis Eduardo
 * Date: 2/25/2016
 * Time: 6:21 AM
 */

namespace xeki;


class http_request
{
    public static $main_object = null;

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
    protected $routes = array();

    /**
     * @var array Array of all named routes.
     */
    protected $namedRoutes = array();

    /**
     * @var string Can be used to ignore leading part of the Request URL (if main file lives in subdirectory of host)
     */
    protected $basePath = '';

    /**
     * @var array Array of default match types (regex helpers)
     */
    protected $matchTypes = array(
        'i' => '[0-9]++',
        'a' => '[0-9A-Za-z]++',
        'h' => '[0-9A-Fa-f]++',
        '*' => '.+?',
        '**' => '.++',
        '' => '[^/\.]++'
    );

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

    /**
     * Add named match types. It uses array_merge so keys can be overwritten.
     *
     * @param array $matchTypes The key is the name and the value is the regex.
     */
    public function addMatchTypes($matchTypes)
    {
        $this->matchTypes = array_merge($this->matchTypes, $matchTypes);
    }

    /**
     * Map a route to a target
     *
     * @param string $method One of 5 HTTP Methods, or a pipe-separated list of multiple HTTP Methods (GET|POST|PATCH|PUT|DELETE)
     * @param string $route The route regex, custom regex must start with an @. You can use multiple pre-set regex filters, like [i:id]
     * @param mixed $target The target where this route should point to. Can be anything.
     * @param string $name Optional name of this route. Supply if you want to reverse route this url in your application.
     * @throws Exception
     */
    public function map($method, $route, $target, $name = null)
    {
        $this->routes[] = array($method, $route, $target, $name);
        if ($name) {
            if (isset($this->namedRoutes[$name])) {
                throw new \Exception("Can not redeclare route '{$name}'");
            } else {
                $this->namedRoutes[$name] = $route;
            }
        }
        return;
    }


    /**
     * Reversed routing
     *
     * Generate the URL for a named route. Replace regexes with supplied parameters
     *
     * @param string $routeName The name of the route.
     * @param array @params Associative array of parameters to replace placeholders with.
     * @return string The URL of the route with named parameters in place.
     * @throws Exception
     */
    public function generate($routeName, array $params = array())
    {

        // Check if named route exists
        if (!isset($this->namedRoutes[$routeName])) {
            throw new \Exception("Route '{$routeName}' does not exist.");
        }

        // Replace named parameters
        $route = $this->namedRoutes[$routeName];

        // prepend base path to route url again
        $url = $this->basePath . $route;

        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {

            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;

                if ($pre) {
                    $block = substr($block, 1);
                }

                if (isset($params[$param])) {
                    $url = str_replace($block, $params[$param], $url);
                } elseif ($optional) {
                    $url = str_replace($pre . $block, '', $url);
                }
            }


        }

        return $url;
    }

    /**
     * Match a given Request Url against stored routes
     * @param string $requestUrl
     * @param string $requestMethod
     * @return array|boolean Array with route information on success, false on failure (no match).
     */
    public function match($requestUrl = null, $requestMethod = null)
    {

        $params = array();
        $match = false;

        // set Request Url if it isn't passed as parameter
        if ($requestUrl === null) {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }
        $scriptName = $this->AG_SERVER['SCRIPT_NAME'];
        $scriptName = str_replace('/index.php', '', $scriptName);

        $requestUrl = str_replace($scriptName, '', $requestUrl);

        // strip base path from request url
        $requestUrl = substr($requestUrl, strlen($this->basePath));

        // fix urls like {{URL_BASE}}/slug
        $requestUrl = (strlen($requestUrl) > 1 && substr($requestUrl, 0, 1) == "/") ? substr($requestUrl, 1) : $requestUrl;
        // set / for empty request
        


        // Strip query string (?a=b) from Request Url
        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }

        $requestUrl = $requestUrl != "" ? $requestUrl : "/";
        // set Request Method if it isn't passed as a parameter
        if ($requestMethod === null) {
            $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }
        foreach ($this->routes as $handler) {
            list($method, $_route, $target, $name) = $handler;

            $methods = explode('|', $method);
            $method_match = false;

            // Check if request method matches. If not, abandon early. (CHEAP)
            foreach ($methods as $method) {
                if (strcasecmp($requestMethod, $method) === 0) {
                    $method_match = true;
                    break;
                }
            }

            // Method did not match, continue to next route.
            if (!$method_match) continue;

            // Check for a wildcard (matches all)
            if ($_route === '*') {
                $match = true;
            } elseif (isset($_route[0]) && $_route[0] === '@') {
                $pattern = '`' . substr($_route, 1) . '`u';
                $match = preg_match($pattern, $requestUrl, $params);
            } else {
                $route = null;
                $regex = false;
                $j = 0;
                $n = isset($_route[0]) ? $_route[0] : null;
                $i = 0;

                // Find the longest non-regex substring and match it against the URI
                while (true) {
                    if (!isset($_route[$i])) {
                        break;
                    } elseif (false === $regex) {
                        $c = $n;
                        $regex = $c === '[' || $c === '(' || $c === '.';
                        if (false === $regex && false !== isset($_route[$i + 1])) {
                            $n = $_route[$i + 1];
                            $regex = $n === '?' || $n === '+' || $n === '*' || $n === '{';
                        }
                        if (false === $regex && $c !== '/' && (!isset($requestUrl[$j]) || $c !== $requestUrl[$j])) {
                            continue 2;
                        }
                        $j++;
                    }
                    $route .= $_route[$i++];
                }

                $regex = $this->compileRoute($route);

                $match = preg_match($regex, $requestUrl, $params);
            }

            if (($match == true || $match > 0)) {
                if ($params) {
                    foreach ($params as $key => $value) {
                        if (is_numeric($key)) unset($params[$key]);
                    }
                }

                return array(
                    'target' => $target,
                    'params' => $params,
                    'name' => $name
                );
            }
        }
        return false;
    }

    /**
     * Compile the regex for a given route (EXPENSIVE)
     */
    private function compileRoute($route)
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {

            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;

                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }

                //Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                    . ($pre !== '' ? $pre : null)
                    . '('
                    . ($param !== '' ? "?P<$param>" : null)
                    . $type
                    . '))'
                    . ($optional !== '' ? '?' : null);

                $route = str_replace($block, $pattern, $route);
            }

        }
        return "`^$route$`u";
    }

    /**
     * Retrieves all routes.
     * Useful if you want to process or display routes.
     * @return array All routes.
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add multiple routes at once from array in the following format:
     *
     *   $routes = array(
     *      array($method, $route, $target, $name)
     *   );
     *
     * @param array $routes
     * @return void
     * @author Koen Punt
     * @throws Exception
     */
    public function addRoutes($routes)
    {
        if (!is_array($routes) && !$routes instanceof Traversable) {
            throw new \Exception('Routes should be an array or an instance of Traversable');
        }
        foreach ($routes as $route) {
            call_user_func_array(array($this, 'map'), $route);
        }
    }

    public function register_url($url = "/", $controller = "main", $module = "core")
    {
        $this->map('GET|POST|PUT|OPTIONS', $url, array('controller' => $controller, 'module' => $module));

//        d($url);
//        d($controller);
//        d($module);
    }


    // deprecated action methods works like urls 
    // TODO REMOVE
    public function process_request()
    {
        global $AG_MODULES;
        GLOBAL $AG_BASE_COMPLETE;
        GLOBAL $AG_L_PARAM;
        GLOBAL $AG_BASE;
        // Analyce xeki_action

        $xeki_action = '';

        ### action
        $AG_POST_ACTION = false;
        if (!empty($_POST['xeki_action'])) {
            $xeki_action = $_POST['xeki_action'];
            $AG_POST_ACTION = true;
        }

        if($AG_POST_ACTION){
            // Launch xeki_action_methods core
            $AG_MODULES->launch_xeki_action_method($xeki_action);
            //// Launch xeki_action_methods core
        }

    }

    public function register_request($method = "POST", $url = "/", $function = "main")
    {
        $this->map($method, $url, $function);
//        d($url);
//        d($controller);
//        d($module);
    }


    public function launch_request()
    {

    }

    public function launch_controller()
    {
        // BGlobal variables
        global $AG_HTML;
        global $AG_MODULES;
        global $_SYSTEM_PATH_BASE;
        global $_DEFAULT_PAGE_ERROR;
        global $AG_PARAMS;
        global $AG_L_PARAM;

        $url_to_require = '';

        $not_found = true;
        $match = $this->match();
        if ($match) {
            $not_found = false;## found by main urls;
            $controller = $match['target']['controller'];
            $module = $match['target']['module'];
            if ($module != "core"){
                $main_module = $AG_MODULES->xeki_module_get_main($module);
                d($main_module);
                $main_module->set_up_pages();
                $module = "modules/$module/core";
            }


            if (strpos($controller, '.php') == false)
                $controller .= ".php";

            require_once("$_SYSTEM_PATH_BASE/$module/controllers/$controller");
        }

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
            $AG_MODULES->load_modules_url();
//            d($this->routes);
            $match = $this->match();
            if ($match) {
                $not_found = false;## found by main urls;
                $controller = $match['target']['controller'];
                $module = $match['target']['module'];
//                d($controller);
            //    d($module);
                $main_module = $AG_MODULES->xeki_module_get_main($module);
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

        // modulos inners urls end

        // no more availeble here
//        if ($not_found && !\xeki\html_manager::$done_render) {
//            require_once("$_SYSTEM_PATH_BASE/core/controllers/$_DEFAULT_PAGE_ERROR");
//        }

    }


    public function register_url_Old($url = "*", $controller = "main", $module = "core")
    {
//        d('register Url');
//        d($url);
        $method_assigned = "$module/controllers/$controller";
        // Get pattern
        if (strpos($controller, '.php') == false)
            $controller .= ".php";

        if ($url === '' || $url === '*') { // this is the base, this base can be change
            $this->AG_ROUTING_DICTIONARY['*'] = $method_assigned;
        }

        if (strpos($controller, '{slug}') == false) {
            $items_url = explode('/', $url);
            $position = 0;
            $base = array();

            $indexes = array();
            $new_dictionary = $this->AG_ROUTING_DICTIONARY;
            d('items');
            d(count($items_url));
            for ($i = 0; $i < count($items_url); $i++) {
                array_push($indexes, "{$items_url[$i]}"); // php is rare this push in
            }
            array_push($indexes, "*");
            array_push($indexes, $method_assigned);
        } else { ## slug handling

        }
        $this->AG_ROUTING_DICTIONARY = $new_dictionary;
    }

    function get_array_value($array, $indexes)
    {
        if (count($indexes) == 1)
            return $array[$indexes[0]];

        $index = array_shift($indexes);
        if (!isset($array[$index])) return false;
        return $this->get_array_value($array[$index], $indexes);
    }

    // recursive method to generate
    function put_array_value($array, $indexes, $value)
    {
        if (count($indexes) == 1)
            return $array[$indexes[0]];
        $index = array_shift($indexes);
        if (!isset($array[$index])) $array[$index] = array();
        return $this->get_array_value($array[$index], $indexes);
    }
}