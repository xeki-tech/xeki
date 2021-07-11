<?php
// Setup cli v0.1
// Setup raw with classes \script module
// This file is loaded by require in cli command
// If need some module, first setup this module like sql
// php index.php setup xeki_db_sql

// Force cli run this
// Check is is cli
if (!is_cli()) {
    die();
}
$name_module = "auth";
$name_module_full = "php-$name_module-module";


// Check if have config
$config_folder = \xeki\core::$SYSTEM_PATH_BASE . "/core/modules_config/$name_module";
$config_folder_file = $config_folder . "/config.php";


// Create folder
if (!file_exists($config_folder)) {
    mkdir($config_folder, 0777, true);
}

$config_default = \xeki\core::$SYSTEM_PATH_BASE . "/modules/$name_module_full/setup/default_config.php";
if (!file_exists($config_folder_file)) {
    copy($config_default, $config_folder_file);
    d("Default config copied");
} else {
    d("Exist config yet, for setup the default delete and run again setup");
}

// generate db
try {
    $sql = \xeki\module_manager::import_module('db-sql');


    require(dirname(__FILE__) . "/setup/setup_db.php");
} catch (Exception $e) {
    d($e);
    d("Import and init sql first and try again");
}



