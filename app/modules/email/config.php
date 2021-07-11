<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        "type_sender" => "smtp", // smtp,local,mailgun

        // mailgun ::config
        "mailgun_key" => '',
        "mailgun_domain" => '',

        // smtp config
        "smtp_domain" => "smtp.gmail.com",
        "smtp_email" => "xeki.rldocjsi@gmail.com",
        "smtp_pass" => "P4ssW0rd122#",

        "smtp_port" => "587",
        "smtp_secure" => "tls",

        "default_from" => "mail@mail.com",

        #aws
        'aws_key' => 'key',
        'aws_secret' => 'secret-key',
        'aws_region' => 'us-west-2' //http://docs.aws.amazon.com/general/latest/gr/rande.html

    ),

);