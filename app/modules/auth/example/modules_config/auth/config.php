<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        //default configuration pages
//        "default_pages" => true,

        //custom configuration pages
        "default_pages" => false,
        "folder_base" => "core/pages",
        "folder_pages" => "/module_user_zone",


        "mail_recover_pass_route" => "default",
        "mail_recover_pass_route" => "core/pages/module_user_zone/mail/recover_pass.html",


        //custom configuration email // for cusmon uncomment this
        // "default_emails" => false,
        // "default_email_base" => "core/pages",

        //default configuration

        // config urls
        "use_module_controllers" => true, // for custom urls set false
        "login_page_url" => "login",
        "register_page_url" => "sign-up",
        "logout_page_url" => "logout",
        "recover_pass_page_url" => "recover-pass",
        "recover_url" => "recover-pass",


        // db_info
        "table_user" => "user",
        "field_id" => "id",
        "field_user" => "email",
        "field_password" => "password",
        "field_recover_code" => "recover_code",
        "table_user_temp" => "customer_temp",
        "temp_field_id" => "id",
        "logged_page" => "dashboard",

        // config facebook
        "facebook_login" => false,  # for activate facebook login
        "app_id" => false,
        "app_secret" => false,
        "facebook_auth_page" => "auth_facebook",
        "facebook_call_back_url" => "auth_facebook_callback",


        "confirm_account" => false,
        "welcome_account" => false,
        //
        "encryption_method" => "sha256",
        "ultra_secure" => true,


        "recover_alert_ok" => "We have sent an email with the instructions to recover your password.",
        "mail_recover_subject" => "Recover pass Uris",
        "mail_recover_copies" => [],

        "recover_alert_updated" => "Your password has been updated",
        "recover_alert_code_not_valid" => "Your code is not valid",
        "recover_alert_fail" => 'Email not valid',

        // messages text
        "msg_no_valid_user" => "User or password not valid.",
        "msg_new_user" => "The user has been created.",
        "msg_user_exist" => "User already exists",
    ),
//    "secondary" => array(
//    ),
);