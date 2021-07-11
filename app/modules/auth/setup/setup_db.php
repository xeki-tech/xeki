<?php
error_reporting(E_ALL);
$auth = \xeki\module_manager::import_module('auth');
// script for create data base 


## get main number of config db

// user permissions
$table = array(
    'table' => 'auth_group',
    'elements' => array(
        'code' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
        'name' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table);

$table = array(
    'table' => 'auth_group_permission',
    'elements' => array(
        'group_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
        'permission_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table);

$table = array(
    'table' => 'auth_permission',
    'elements' => array(
        'code' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
        'name' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);

$sql->create_table_array($table);


// user
$user_table = array(
    'table' => 'auth_user',
    'elements' => array(
        'password' => [
            'type_field' => 'text',
            'null' => 'allow', // allow,

        ],
        'last_login' => [
            'type_field' => 'timestamp',
            'null' => 'allow', // allow, not_allow
        ],
        'date_joined' => [
            'type_field' => 'timestamp',
            'null' => 'allow', // allow, not_allow
        ],
        'is_superuser' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
            'value_default' => 'no',
        ],

        'first_name' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
        'last_name' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],

        'username' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
        'email' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],

        'is_staff' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
            'value_default' => 'no',
        ],
        'is_active' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
            'value_default' => 'yes',
        ],
    ),
);

$items = $auth->get_value_param("extra_fields_user");
d($items);
if (is_array($items)) {
    $user_table['elements'] = array_merge($user_table['elements'], $items);
}
d($user_table['elements']);
$sql->create_table_array($user_table);


$table_ref = array(
    'table' => 'auth_user_group',
    'elements' => array(
        'user_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
        'group_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table_ref);

$table_ref = array(
    'table' => 'auth_user_permission',
    'elements' => array(
        'user_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
        'permission_ref' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table_ref);


$table_ref = array(
    'table' => 'auth_user_sessions',
    'elements' => array(
        'sk_2' => [
            'type_field' => 'text_long',
            'null' => 'allow', // allow, not_allow
        ],
        'user_id' => [
            'type_field' => 'number',
            'null' => 'allow', // allow, not_allow
        ],
        'date_creation' => [
            'type_field' => 'timestamp',
            'null' => 'allow', // allow, not_allow
        ],
        'last_use' => [
            'type_field' => 'text',
            'null' => 'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table_ref);

