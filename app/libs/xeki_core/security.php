<?php

namespace xeki;


class security
{
    public static function init(){

//       session_start();
    }

    public static function full_session_destroy(){
        // 
        session_destroy(); 
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }
    public static function check_request(){

    }
}


