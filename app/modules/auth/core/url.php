<?php

// Works only form main module 
$auth_module = \xeki\module_manager::import_module('xeki_auth');
$enable_controllers = $auth_module->get_value_param("use_module_controllers");
// d($enable_controllers);
if ($enable_controllers) {

    ## load urls for config file
    $login_url = $auth_module->get_value_param("login_page_url");
    $register_url = $auth_module->get_value_param("register_page_url");
    $logout_url = $auth_module->get_value_param("logout_page_url");
    $recover_url = $auth_module->get_value_param("recover_pass_page_url");

    $confirm_account_route_url = $auth_module->get_value_param("confirm_account_route_url");


    // d($login_url);
    // d($register_url);
    // d($logout_url);
    // d($recover_url);


    \xeki\routes::any("$login_url", 'auth_login', "xeki_auth");
    \xeki\routes::any("$login_url/", 'auth_login', "xeki_auth");


    \xeki\routes::any("$register_url", 'auth_register', "xeki_auth");
    \xeki\routes::any("$register_url/", 'auth_register', "xeki_auth");

    \xeki\routes::any("$logout_url", 'auth_logout', "xeki_auth");
    \xeki\routes::any("$logout_url/", 'auth_logout', "xeki_auth");

    \xeki\routes::any("$recover_url", 'auth_recover_pass', "xeki_auth");
    \xeki\routes::any("$recover_url/", 'auth_recover_pass', "xeki_auth");

    \xeki\routes::any("$recover_url/{code}", 'auth_recover_pass_update', "xeki_auth");


    // confirm account
    \xeki\routes::any("$confirm_account_route_url", 'auth_confirm_account', "xeki_auth");
    \xeki\routes::any("$confirm_account_route_url/{code}", 'auth_confirm_account', "xeki_auth");


    // facebook
    $facebook_login = $auth_module->get_value_param("facebook_login");
    if ($facebook_login) {
        $facebook_auth_page = $auth_module->get_value_param("facebook_auth_page");
        \xeki\routes::any("$facebook_auth_page", 'auth_facebook', "xeki_auth");
        \xeki\routes::any("$facebook_auth_page/", 'auth_facebook', "xeki_auth");

        $facebook_call_back_url = $auth_module->get_value_param("facebook_call_back_url");
        \xeki\routes::any("$facebook_call_back_url", "auth_facebook_callback", "xeki_auth");
        \xeki\routes::any("$facebook_call_back_url/", "auth_facebook_callback", "xeki_auth");
    }
}
