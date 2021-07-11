# csrf Module

csrf module is a autentication module for xeki framework :D.

## Quick Start

Initialize db run module set up 

```bash
php modules/module_set_up.php
```

##### Copy folder config

```bash
mkdir core/modules_config/xeki_csrf/
cp modules/xeki_csrf/config.php core/modules_config/xeki_csrf/
```

##### Copy template folder
```bash
mkdir core/modules_config/xeki_csrf/
cp modules/xeki_csrf/config.php core/modules_config/xeki_csrf/
```


##### Activate urls of module 

```php
$_ARRAY_MODULES_TO_LOAD_URLS = array(
    "....",
    "....",
    "xeki_csrf",
); 
```

For add info to render info 

$_ARRAY_RUN_START = array(
    #modules_names
   'xeki_csrf'
);


## Use in controllers
We can use this methods for create secure zones in you implementation.

#### General Example use
```php
// import module 
$xeki_csrf =  \xeki\module_manager::import_module('xeki_csrf');
// check if is logged, if is not logged is redirected to login page
$xeki_csrf->check_logged();
```

#### Advance Example use
```php
// import module 
$xeki_csrf =  \xeki\module_manager::import_module('xeki_csrf');

// set name space of csrf
$xeki_csrf->set_name_space("my_name_space_application");

// set login page
$csrf_module->set_logged_page("user/my_login_page");

// check if is logged, if is not logged is redirected to login page
$xeki_csrf->check_logged();

if($xeki_csrf->is_logged()){
    // do something
}

// check if have permision for some action
$xeki_csrf->check_permission('my_action_code');

if($xeki_csrf->have_permission()){
    // do something
}

```

#### All Methods
##### Import module

```php
$xeki_csrf =  \xeki\module_manager::import_module('xeki_csrf');
```

##### Check csrf
```php

// if not login redirect to login page

$csrf_module->set_logged_page("my login custom");
$xeki_csrf->check_logged();

// just return login or not 
$is_csrf = $xeki_csrf->check_csrf();

```

##### Check by Name spaces
We can have diferentes name spaces for each system or 
```php

$xeki_csrf->set_name_space("my_name_space_application");

$xeki_csrf->check_logged();

$xeki_csrf->check_permission('my_action_code');


```


##### Check Action Permision
```php

// if not login redirect to not permision page
$xeki_csrf->check_logged();

// just return login or not 
$is_csrf = $xeki_csrf->check_csrf("action_permission_code");

```

##### Other methods

Get info of user
```php
$user_info = $xeki_csrf->get_user_info();

$user_info = $xeki_csrf->get_persmissions_info();
```


