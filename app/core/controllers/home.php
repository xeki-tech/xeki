<?php
//$html = \xeki\module_manager::import_module('html-twig');
//$html->render('home.twig', []);

\xeki\core::PrintJson(["response"=>'ok',"date"=>date("Y-m-d")]);