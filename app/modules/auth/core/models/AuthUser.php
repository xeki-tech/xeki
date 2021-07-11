<?php

namespace xeki_auth;


/**
 * Class auth-module
 * @package xeki_auth
 * version 1
 */
class User
{
    public $id;
    public $user_identifier;
    public $array_info;


    private $sql;
    private $encryption_method = "sha256";
    private $local_config;

    function __construct($local_config)
    {
        $this->local_config = $local_config;
        $this->sql = \xeki\module_manager::import_module('db-sql', $local_config['db_config']);
        $this->encryption_method = $local_config['encryption_method'];
    }

    function load_by_id($id)
    {
        $query = "select * from auth_auth where id='{$id}'";
        $user = $this->sql->query($query);
        $user = $user[0];
        $this->id = $user['id'];
        $this->array_info = $user;
    }

    function load_by_identifier($user_identifier)
    {
        $query = "select * from auth_auth where {$this->user_identifier}='{$user_identifier}'";
        $user = $this->sql->query($query);
        $user = $user[0];
        $this->id = $user['id'];
        $this->user_identifier = $user[$this->user_identifier];
        $this->array_info = $user;
    }

    public function load_info($info)
    {
        $this->id = $info['id'];
        $this->user_identifier = $info[$this->user_identifier];
        $this->array_info = $info;

    }

    public function load_groups_permissions()
    {

    }

    public function get($info)
    {
        $array_info = $this->array_info;
        return isset($array_info[$info]) ? $array_info[$info] : false;

    }

    public function get_info()
    {
        return $this->array_info;
    }

    public function set($field, $value)
    {
        $value = $this->sql->sanitize($value);
        $data = [
            $field => $value
        ];
        $res = $this->sql->update("auth_user", $data, " id = {$this->id} ");
        return $res;

    }

    public function update($array)
    {
        return $this->sql->update("auth_user", $array, " id = {$this->id}");

    }

    public function set_password($password)
    {
        $password = hash($this->encryption_method, $password);
        return $this->set_password_encrypted($password);

    }

    public function set_password_encrypted($password)
    {
        $data = [
            "password" => $password
        ];
        return $this->sql->update("auth_user", $data, " id = {$this->id}");

    }

    public function group_add($code_group)
    {
        // get group
        $group = new Group($this->local_config);
        $error = $group->load_code($code_group);

        if (\xeki\core::is_error($error)) {
            return $error;
        }
        //
        // check is exist relation
        $query = "Select * from auth_user_group where user_ref='{$this->id}' and group_ref='{$group->id}'";
        $res = $this->sql->query($query);

        if (is_array($res)) {
            if (count($res) > 0) {
                // handling error
                return new \xeki\error("group_already_added");
            }
        } else {
            // handing error sql error
////            d($this->sql->error());
            return new \xeki\error("group_validate_error_sql");
        }

        // add group

        $data = [
            "user_ref" => $this->id,
            "group_ref" => $group->id
        ];
//        d($data);
        $res = $this->sql->insert('auth_user_group', $data);
        if ($res) {
            return true;
        } else {
            // handing error sql error
////            d($this->sql->error());
            return new \xeki\error("group_add_error_sql");
        }


    }

    public function group_remove($code_group)
    {
        // get group
        $group = new Group($this->local_config);
        $error = $group->load_code($code_group);

        if (\xeki\core::is_error($error)) {
            return new \xeki\error("group ");
        }
        //
        // check is exist relation
        $query = "Select * from auth_user_group where user_ref='{$this->id}' and group_ref='{$group->id}'";
        $res = $this->sql->query($query);
        if (is_array($res)) {
            if (count($res) == 0) {
                // handling error
                return new \xeki\error("group_not_added");
            }
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("group_validate_error_sql");
        }

        // remove  group

        $res = $res = $this->sql->delete("auth_user_group", "user_ref='{$this->id}' and group_ref='{$group->id}'");
        if ($res) {
            return true;
        } else {
            // handing error sql error
            return new \xeki\error("sql_error");
        }
    }

    public function group_clear()
    {
        $res = $res = $this->sql->delete("auth_user_group", "user_ref='{$this->id}'");
        if ($res) {
            return true;
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("group_remove_error_sql");
        }
    }

    public function permission_add($code_permission)
    {
        // get group
        $permission = new Permission($this->local_config);
        $error = $permission->load_code($code_permission);

        if (\xeki\core::is_error($error)) {
            return new \xeki\error("permission dont exist ");
        }

        // check is exist relation
        $query = "Select * from auth_user_permission where user_ref='{$this->id}' and permission_ref='{$permission->id}'";
        $res = $this->sql->query($query);
        if (is_array($res)) {
            if (count($res) > 0) {
                // handling error
                return new \xeki\error("permission_already_added");
            }
        } else {
            // handing error sql error
            d($this->sql->error());
            return new \xeki\error("permission_add_error_sql");
        }

        // add group

        $data = [
            "user_ref" => $this->id,
            "permission_ref" => $permission->id
        ];
        $this->sql->insert('auth_user_permission', $data);

        return true;

    }

    public function permission_remove($code_permission)
    {
        // get permission
        $permission = new permission($this->local_config);
        $error = $permission->load_code($code_permission);

        if (\xeki\core::is_error($error)) {
            return new \xeki\error("permission ");
        }
        //
        // check is exist relation
        $query = "Select * from auth_user_permission where user_ref='{$this->id}' and permission_ref='{$permission->id}'";
        $res = $this->sql->query($query);
        if (is_array($res)) {
            if (count($res) == 0) {
                // handling error
                return new \xeki\error("permission_not_added");
            }
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("permission_validate_error_sql");
        }

        // remove  permission
        $res = $res = $this->sql->delete("auth_user_permission", "user_ref='{$this->id}' and permission_ref='{$permission->id}'");
        if ($res) {
            return true;
        } else {
            // handing error sql error
            return new \xeki\error("permission_remove_error_sql");
        }


    }

    public function permission_clear()
    {
        $res = $res = $this->sql->delete("auth_user_permission", "user_ref='{$this->id}'");
        if ($res) {
            return true;
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("group_remove_error_sql");
        }
    }

    public function get_groups()
    {
        $query = "Select * from auth_group, auth_user_group where auth_user_group.group_ref = auth_group.id and user_ref='{$this->id}'";
        $res = $this->sql->query($query, true);
        if (is_array($res)) {
            // process groups
            $groups = [];
            foreach ($res as $item) {
                // create groups
                $group = new Group($this->local_config);
                $group->load_info($item['auth_group']);
                array_push($groups, $group);
            }
            return $groups;
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("sql_error");
        }
    }

    public function has_group($code)
    {
        $query = "Select * from auth_group, auth_user_group where auth_user_group.group_ref = auth_group.id and user_ref='{$this->id}' and auth_group.code='{$code}'";
        $res = $this->sql->query($query, true);
        if (is_array($res)) {
            // process groups
            if (count($res) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            // handing error sql error
//            d($this->sql->error());
            return new \xeki\error("sql_error");
        }
    }

    public function get_permissions()
    {
        $query = "Select * from auth_permission,auth_user_permission where auth_permission.id = auth_user_permission.permission_ref and user_ref='{$this->id}'";
        $res = $this->sql->query($query, true);

        if (!is_array($res)) {
            return new \xeki\error("sql_error");
        }

        $permissions = [];
        // parse permissions
        foreach ($res as $item) {
            // create groups
            $permission = new Permission($this->local_config);
            $permission->load_info($item['auth_permission']);
            array_push($permissions, $permission);
        }

        // add merge permissions groups
        return $permissions;
    }

    public function has_permission($code_permission)
    {

        $permission = new permission($this->local_config);
        $error = $permission->load_code($code_permission);

        if (\xeki\core::is_error($error)) {
            return new \xeki\error("permission ");
        }

        $query = "Select * from auth_user_permission where user_ref='{$this->id}' and permission_ref='{$permission->id}'";
        $res = $this->sql->query($query);

        // validate groups permissions

        if (!is_array($res)) {
            return new \xeki\error("sql_error");
        }

        if (count($res) > 0) {
            return true;
        }

        // check groups
        $query = " SELECT * FROM
                    auth_group,
                    auth_group_permission,
                    auth_permission,
                    auth_user_group
                    WHERE
                    1
                    AND auth_group.id = auth_group_permission.group_ref
                    AND auth_permission.id = auth_group_permission.permission_ref
                    AND auth_user_group.user_ref = {$this->id}
                    AND auth_permission.id = {$permission->id}";
        $res = $this->sql->query($query, true);

        // validate groups permissions
        if (!is_array($res)) {
            return new \xeki\error("sql_error");
        }

        if (count($res) > 0) {
            return true;
        } else {
            return false;
        }
        // add merge permissions groups
    }

    public function is_super_user()
    {
        if ($this->array_info['is_superuser'] == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function is_staff()
    {
        if ($this->array_info['is_staff'] == 'yes') {
            return true;
        } else {
            return false;
        }
    }


}