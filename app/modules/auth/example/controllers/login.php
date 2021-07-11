<?php

$auth = \xeki\module_manager::import_module('auth');

$res = $auth->login("liuspatt@gmail.com", "p4ssw0rd");
d($res);