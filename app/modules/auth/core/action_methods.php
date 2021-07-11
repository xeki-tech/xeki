<?php

// Works only form main module 
$auth_module = \xeki\module_manager::import_module('xeki_auth');
$enable_controllers = $auth_module->get_value_param("use_module_controllers");
// d($enable_controllers);
if ($enable_controllers) {

}