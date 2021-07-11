<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */
namespace db_sql;
require_once dirname(__FILE__) . "/core/mysql.php";

class main
{
    public $sql = null;
    public $host = '';
    public $user = '';
    public $pass = '';
    public $db = '';

    function init($config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->db = $config['db'];
        return $this->getObject();
    }
    function getObject(){
        if ($this->sql == null) {
            $connection_information = array(
                'host' => $this->host,
                'user' => $this->user,
                'pass' => $this->pass,
                'db' => $this->db
            );

            $this->sql = new mysql($connection_information);
        }
//        d($this->sql);
//        $info = $this->sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return $this->sql;
    }
    
    function check()
    {
        return true;
    }
}
