<?php
// script for create data base 
require_once dirname(__FILE__).'/../../../libs/xeki_util_methods.php';
require_once dirname(__FILE__).'/../../../libs/xeki_core/module_manager.php';

## get main number of config db
$sql = \xeki\module_manager::import_module("xeki_db_sql","main");
