<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        "type_sender" => "smtp", // smtp,local,mailgun,ses

        "default_from" => "My company <contact@company.com>",

        // mailgun ::config
        "mailgun_key" => '',
        "mailgun_domain" => '',

        // smtp config
        "smtp_domain" => "smtp.gmail.com",
        "smtp_email" => "agent.rldocjsi@gmail.com",
        "smtp_pass" => "P4ssW0rd122#",

        "smtp_port" => "587",
        "smtp_secure" => "tls",


        #aws
        'aws_key' => 'KEY',
        'aws_secret' => 'SECRET', # iam
        'aws_region' => 'us-west-2' //http://docs.aws.amazon.com/general/latest/gr/rande.html


    ),

);
