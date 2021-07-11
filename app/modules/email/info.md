# Mail guide

## Import Module
+ Import module
```php
$mail =  \xeki\module_manager::import_module('email_module');

```

## Quick Use

+ Min Full code for send a email TODO

```php
$mail =  \xeki\module_manager::import_module('email_module');
    $to = $vl_values['email'];
    $subject = "Confirmacion Contacto";
    $path = dirname(__FILE__)."/pages/mail/"."contacto.html";
    $html = file_get_contents($path); // like top email body
    $codes_html = <<<HTML
    <b>Nombre: </b>{$vl_values['name']}<br>
    <b>Email: </b>{$vl_values['email']}<br>
    <b>Asunto: </b>{$vl_values['subject']}<br>
    <b>Mensaje:</b><br>{$vl_values['message']}<br>
HTML;

    $array_info = array(
        "email_from" => "info@xeki.com", #opcional
        // example info
        "codes_html" =>$codes_html,
        'name' => $vl_values['name'],
        'email' => $vl_values['email'],
        'subject' => $vl_values['subject'],
        'message' => $vl_values['message'],
    );
    $mail->send_email($to,$subject,$html,$array_info);
    
```

## Config File
+ Explicar como se configura 
```php
$MODULE_DATA_CONFIG = array(
    "main" => array(
        "type_sender" => "mailgun", // smtp,local,mailgun, ses

        // mailgun ::config
        "mailgun_key"=>'mailgun Key', //key-XXXXXXXXXXXXXX
        "mailgun_domain"=>'domain.com', //domain.coim
        "default_from" => "MAIL <no-reply@domain.com>", //A shop <no-reply@domain.com>
       

        // smtp config
        "smtp_domain" =>"smtp.gmail.com",
        "smtp_email" =>"xeki.rldocjsi@gmail.com",
        "smtp_pass" =>"P4ssW0rd122#",
        
        "smtp_port" =>"587",
        "smtp_secure" =>"tls",
        
        #aws
        'aws_key'    => 'key',
        'aws_secret' => 'secret-key',
        'aws_region' => 'us-west-2' //http://docs.aws.amazon.com/general/latest/gr/rande.html
    ),

);
```


## Examples uses
+ Contact form TODO
+ Emply search form TODO
+ Poner todos los casos que pueden ser utiles

html
 ```html
 <body email>
 <p>{{name}}</p>
 <p>{{last_name}}</p>
 <p>{{email}}</p>
 </body>
 ```

sender
```php

$to = "emailto@mail.com";
$subject = "cute_subject_for email";
$html = read_html(); // like top email body
$array_info = array(
 "email_from" => "info@xeki.com", #opcional
 // example info
 "name" => "Luis Eduardo",
 "last_name" => "Patt",
 "email" => "email",
);
$mail->send_email($to,$subject,$html,$array_info);
```

complete

```php
    $to = 'liuspatt@gmail.com';
    $subject = "{$_POST['query']}  - Busqueda Vacia - VYC";
    $html = 'Hermosa hay una busqueda con ' . count($data) . ' resultados <br> la consulta es:' . $_POST['query'];
    $array_info = array(
        "email_from" => "Contacto@vainillaycanela.co", #opcional
        // example info
        "name" => "Luis Eduardo",
        "last_name" => "Patt",
        "email" => "email",
    );

    $to = 'liuspatt@gmail.com';
    $mail->send_email($to, $subject, $html, $array_info);

    $to = 'nmejia.89@gmail.com';
    $mail->send_email($to, $subject, $html, $array_info);

```

## Documentation Methods 

### send_email

$to, $subject, $html, $array_info
+ $array_info
  + array of list of info ...
+ $array_info