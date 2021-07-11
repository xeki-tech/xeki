<?php
// use example;

$title = "Home";
$description = "";


// User
#  email:  liuspatt@gmail.com
#  pass:   p4ssw0rd
#

// Group
#  Name: Testing group
#  code: testing_group

// Permision
#  Name: Create testing item
#  code: create_testing_item

// Ruotine
//
// Delete group
// Delete user
// Delete permission

$auth = \xeki\module_manager::import_module('auth');

$auth->remove_user("liuspatt@gmail.com");
$auth->remove_permission("create_testing_item");
$auth->remove_group("testing_group");

$auth->create_group("Testing Group", "testing_group");
$auth->create_permission("Create testing item", "create_testing_item");

//
$auth->add_permission_to_group("testing_group", "create_testing_item");

$group = $auth->get_group("testing_group");
$group->add_permission("create_testing_item");

// Create user
$additional_data = [];
$auth->create_user("liuspatt@gmail.com", "p4ssw0rd", $additional_data);
//$auth->create_user_encrypted_pass("liuspatt@gmail.com","48d2a5bbcf422ccd1b69e2a82fb90bafb52384953e77e304bef856084be052b6",$additional_data);

$auth->user_exist("liuspatt@gmail.com");

$auth->login("liuspatt@gmail.com", "p4ssw0rd");
$auth->login_encrypted("liuspatt@gmail.com", "48d2a5bbcf422ccd1b69e2a82fb90bafb52384953e77e304bef856084be052b6");


$status = $auth->login_status();
d($status);

$user = $auth->get_user();
d($user);
d($user->get("lastname"));
d($user->get("email"));
d($user->get("name"));

$user = $auth->get_info(); // return array
d($user);

// update info
$user->set("lastname", "My last name");

$array = [
    'lastname' => 'My last name',
    'phone' => 'some_code',
];
$user->update("lastname");
$user->set_password("password");
$user->set_password_encrypted("48d2a5bbcf422ccd1b69e2a82fb90bafb52384953e77e304bef856084be052b6");


\xeki\html_manager::set_seo($title, $description, true);

// $query = "SELECT * from slider";
// $slider_home = $sql->query($query);
// $slider_home= $slider_home[0];
// d($slider_home);


$items_to_print = array();

\xeki\html_manager::render('home.html', $items_to_print);