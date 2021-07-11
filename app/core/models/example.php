<?php
class example
{
    // Configurable variables

    private $sql = null;
    private $xeki_auth = null;

    private $logged_user = false;


    function __construct()
    {
        
        // You need first install the modules
        $this->xeki_auth = \xeki\module_manager::import_module("xeki_auth");
        $this->sql=\xeki\module_manager::import_module("xeki_db_sql");

        if($this->xeki_auth->check_auth()) {
            $this->logged_user = true;
        }
        $result = $this->sql->query("Select * from myTable");
        foreach($result as $item){
            
        }
        
    }

}