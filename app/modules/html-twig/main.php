<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */

namespace xeki_html_twig;
require_once dirname(__FILE__) . "/core/core.php";

class main
{
    public static $sql = null;
    public $config = array();
    public $user = '';
    public $pass = '';
    public $db = '';

    function init($config)
    {
        $this->config = $config;
        return true;
    }

    function getObject()
    {
        if (self::$sql == null) {

            if(empty($this->config['pages_folder'])){
                d("html-twig module page folder not setted");die();
            }
            if(empty($this->config['cache_folder'])){
                d("html-twig module cache folder not setted");die();
            }

            $path_html = \xeki\core::$SYSTEM_PATH_BASE.'/'.$this->config['pages_folder'];## this update by modules
            $path_cache = \xeki\core::$SYSTEM_PATH_BASE.'/'.$this->config['cache_folder'];## this update by modules
            $this->config['pages_folder']=$path_html;
            $this->config['cache_folder']=$path_cache;
            self::$sql = new xeki_html_twig( $this->config);
        }
//        d(self::$sql);
//        $info = self::$sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return self::$sql;
    }

    function check()
    {
        return true;
    }
}