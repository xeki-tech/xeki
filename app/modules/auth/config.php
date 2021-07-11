<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        //default configuration pages
//        "default_pages" => true,

        //custom configuration pages
        "default_pages" => true,
        "folder_base" => "core/pages",
        "folder_pages" => "/module_user_zone",


        "mail_recover_pass_route" => "default",
        "mail_recover_pass_route" => "pages/mail/recover_pass.html",


        // Use this for {{key} copies in template email, is you need use i18n, set name as i18n_code_item
        "main_recover_copies" => array(
            "example_key" => "Example copie",
            "other_example" => "i18n_example",

        ),

        //custom configuration email // for cusmon uncomment this 
        // "default_emails" => false,
        // "default_email_base" => "core/pages",

        //default configuration


        // config urls
        "use_module_controllers" => true, // for custom urls set false
        "login_page_url" => "login",
        "register_page_url" => "register",
        "logout_page_url" => "logout",
        "recover_pass_page_url" => "recover-pass",

        "confirm_account" => true, // for custom urls set false
        "confirm_account_route_mail" => "default",
        "confirm_account_route_mail" => "pages/mail/confirm_account.html",

        "confirm_account_route_url" => "confirm-account",

        "confirm_account_copies" => array(
            "example_key" => "Example copie",
            "other_example" => "i18n_example",

        ),

        // db_info
        "table_user" => "user",
        "field_id" => "id",
        "field_user" => "email",
        "field_password" => "password",
        "field_recover_code" => "recover_code",
        "table_user_temp" => "customer_temp",
        "temp_field_id" => "id",
        "logged_page" => "",

        // config facebook
        "facebook_login" => false,  # for activate facebook login
        "app_id" => false,
        "app_secret" => false,
        "facebook_auth_page" => "auth_facebook",
        "facebook_call_back_url" => "auth_facebook_callback",


        //
        "encryption_method" => "sha256",
        "ultra_secure" => true,

        // messages text
        "alerts_copies" => array(
            "msg_no_valid_user" => "User or password not valid.",
            "msg_new_user" => "The user has been created.",
            "msg_user_exist" => "User already exists",
        ),
    ),
//    "secondary" => array(
//    ),
);