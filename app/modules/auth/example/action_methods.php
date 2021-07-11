<?php


//example url post
\xeki\routes::post('example/post/request', function () {
    $_POST;
    d($_POST);
    d("..hi!!");
    \xeki\core::redirect("/");
});

// action run by
\xeki\routes::action('create_user', function () {
    $_POST;
    d($_POST);
    d("..hi!! create user ");
});


// action run by
\xeki\routes::action('auth::login', function () {

    $auth = \xeki\module_manager::import_module('auth');
    $_POST;
    d($_POST);
    $user_identier = $_POST['user_identier'];
    $password = $_POST['password'];

    $res = $auth->login($user_identier, $password);
    if ($res instanceof \xeki\error) {
        d($res->code);
        if ($res->code == "invalid_pass") {
            // 
        }
        if ($res->code == "not_user_exit") {

        }

        if ($res->code == "sql_error") {

        }
    }

    if (\xeki\core::is_error($res)) {
        d($res->code);
        if ($res->code == "invalid_pass") {
            // 
        }
        if ($res->code == "not_user_exit") {

        }

        if ($res->code == "sql_error") {

        }

    }

});