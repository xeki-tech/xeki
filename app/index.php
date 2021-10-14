<?php
// Set path of base
$_SYSTEM_PATH_BASE = dirname(__FILE__);

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


