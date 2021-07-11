<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */

namespace xeki_mail;
require_once dirname(__FILE__) . "/core/mail.php";

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

            self::$sql = new mail($this->config);
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