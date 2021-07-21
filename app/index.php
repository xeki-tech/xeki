<?php
// Set path of base
$_SYSTEM_PATH_BASE = dirname(__FILE__);

// Headers custom 

## CORS for WS comment this for security
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day

    if (isset($_GET['__amp_source_origin'])) {
        header('AMP-Access-Control-Allow-Source-Origin: ' . urldecode($_GET['__amp_source_origin']));
    } else {
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


// check if core installed
$initScript = "./libs/vendor/xeki-tech/core/init.php";
$renderScript = "./libs/vendor/xeki-tech/core/render.php";
if (!file_exists("$initScript") && !file_exists($renderScript)) {
    echo "run first composer require xeki-tech/core";
}

// Load scripts settings
require_once "{$initScript}";
require_once "./core/url.php";
require_once "./core/main.php";
require_once "./core/action_methods.php";
require_once "{$renderScript}";


