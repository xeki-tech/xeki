<?php
// use example;

$title = "Home";
$description = "";

\xeki\html_manager::set_seo($title, $description, true);

// $query = "SELECT * from slider";
// $slider_home = $sql->query($query);
// $slider_home= $slider_home[0];
// d($slider_home);


$items_to_print = array();

\xeki\html_manager::render('home.html', $items_to_print);