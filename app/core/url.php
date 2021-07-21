<?php
 \xeki\routes::any('', 'home');

 \xeki\routes::any('demo-function', function(){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "date"=>date("Y-m-d")
        ]
    );
 });


\xeki\routes::post('url/with/vars/{regexVar:[1-9]}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "regexVar"=>$var['regexVar']
        ]
    );
});


\xeki\routes::any('url/with/vars/{nameVar:.+}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "var"=>$var['nameVar']
        ]
    );
});


\xeki\routes::post('url/with/vars/{numberVar:\d+}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "var"=>$var['numberVar']
        ]
    );
});


