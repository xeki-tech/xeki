<?php
 \xeki\routes::any('', 'home');
 \xeki\routes::any('main', 'home');



\xeki\routes::any('example_list', function($vars){

    // query
    $books = [];


});

\xeki\routes::get('ws_example', function($vars){
    $ag_config = \xeki\module_manager::import_module('xeki_config');
    $config_data=$ag_config->get_data();
});





