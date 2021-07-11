<?php


$auth = \xeki\module_manager::import_module('auth');

$status = $auth->is_logged();
if ($status) {
    // is logged
    $user = $auth->get_user();
    $user_info = $user->get_info(); // return array

    \xeki\html_manager::add_extra_data("auth_user_info", $user_info);

    \xeki\html_manager::add_extra_data("last_name", $user->get("last_name"));
    \xeki\html_manager::add_extra_data("email", $user->get("email"));
    \xeki\html_manager::add_extra_data("first_name", $user->get("first_name"));
}

\xeki\html_manager::add_extra_data("somedata", "This is spartaa");