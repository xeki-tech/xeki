<?php

namespace xeki_auth;

/**
 * Class auth-module
 * @package xeki_auth
 * version 1
 */
class Permission
{
    public $id;
    public $code;
    public $name;


    private $sql;

    function __construct($local_config)
    {
        $this->sql = \xeki\module_manager::import_module('db-sql', $local_config['db_config']);
    }


    function load_id($id)
    {
        $query = "select * from auth_permission where id='{$id}'";
        $permission = $this->sql->query($query);
        $permission = $permission[0];
        $this->id = $permission['id'];
        $this->code = $permission['code'];
        $this->name = $permission['name'];

    }

    function load_code($code)
    {
        $query = "select * from auth_permission where code='{$code}'";
        $permission = $this->sql->query($query);
        $permission = $permission[0];
        $this->id = $permission['id'];
        $this->code = $permission['code'];
        $this->name = $permission['name'];

    }

    function load_info($info)
    {
        $this->id = $info['id'];
        $this->code = $info['code'];
        $this->name = $info['name'];
    }

}