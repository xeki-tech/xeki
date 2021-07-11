<?php
// script for create data base 

## get main number of config db

// user permissions
$table = array(
    'table' => 'csrf_token',
    'elements' => array(
        'cookie'  => [
            'type_field'=>'text_long',
            'null'=>'not_allow', // allow, not_allow
        ],
        'token'  => [
            'type_field'=>'text_long',
            'null'=>'not_allow', // allow, not_allow
        ],
        'date_creation'  => [
            'type_field'=>'timestamp',
            'null'=>'allow', // allow, not_allow
        ],
    ),
);
$sql->create_table_array($table);
