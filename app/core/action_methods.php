<?php


//example url post
\xeki\routes::post('example/post/request', function(){
    $_POST;
    d($_POST);
    d("..hi!!");
    \xeki\core::redirect("/");
});

// action run by
\xeki\routes::action('create_user', function(){
    $_POST;
    d($_POST);
    d("..hi!! create user ");
});
