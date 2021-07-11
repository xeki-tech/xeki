<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        /*
         * Encryption method hash
         * default sha256s
         * */
        "encryption_method" => "sha256",

        /*
         * db-sql config
         * default main
         * */
        "db_config" => "main",

        /*
        * field db for user login
        * default main
        * */
        "field_identifier" => "email",
    ),
//    "secondary" => array(
//    ),
);