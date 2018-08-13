# xeki FRAMEWORK

## Url
## Pages
## Controllers

### SEO
 
 SEO SCRIPT
 
```php

$title = "Portada ";
$description = "¿Estás buscando un vehículo y no sabes cuál elegir? Aquí te damos excelentes pautas, ¡Entra ya!";

$keyWords = "";
\xeki\html_manager::set_seo($title,$description,$keyWords,true);

```

SOCIAL META TAGS

```php
$meta_data = array(
    "google" => array(
        "name" => "Nice TITLE",
        "description" => "",
        "image" => "https://domain.url/image.png",#absolute url
    ),
    "twitter" => array(
        "card" => "",
        "site" => "",
        "title" => "Nice TITLE",
        "description" => "",
        "creator" => "",
        "image" => "https://domain.url/image.png",
    ),
    "facebook" => array(
        "title" => "Nice TITLE",
        "type" => "website", # website / article / product
        "image" => "https://domain.url/image.png",#absolute url
        "description" => "",
        "fb:admins" => "",
        "article:published_time" => "",
        "article:modified_time" => "",
        "article:section" => "",
        "article:tag" => "",
        "price:amount" => "",
        "price:currency" => "",
    ),
);
$AG_HTML->set_social_meta_tags($meta_data);
```

## MODULES