<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */

namespace xeki_auth;


require_once dirname(__FILE__) . "/core/models/xeki_auth.php";

class main
{
    public static $object = null;
    public $folder_pages = '';
    public $folder_base = '';
    private $default_pages = true;
    private $config = array();

    function __construct()
    {
    }

    function init($config)
    {
        // validate params v1 y should do this better :) 
        $required_items = array();


        foreach ($required_items as $value) {
            if (!isset($config[$value])) {
                echo "ERROR CONFIG MODULE auth<br>";
                echo "$value<br>";
                die();
            }

        }
        # for set custom folder pages
        $this->default_pages = $config['default_pages'];
        $this->folder_base = $config['folder_base'];
        $this->config = $config;
        return true;
    }


    function getObject()
    {
        if (self::$object == null) {
            self::$object = new xeki_auth($this->config);
        }
//        d(self::$sql);
//        $info = self::$sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return self::$object;
    }

    function check()
    {
        return true;
    }

    function set_up_pages()
    {


    }


}