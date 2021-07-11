# Auth Module

Auth module is a autentication module for xeki framework :D.

## Quick Start

Initialize db run module set up 

```bash
php modules/module_set_up.php
```

##### Copy folder config

```bash
mkdir core/modules_config/xeki_auth/
cp modules/xeki_auth/config.php core/modules_config/xeki_auth/
```

##### Copy template folder
```bash
mkdir core/modules_config/xeki_auth/
cp modules/xeki_auth/config.php core/modules_config/xeki_auth/
```


##### Activate urls of module 

```php
$_ARRAY_MODULES_TO_LOAD_URLS = array(
    "....",
    "....",
    "xeki_auth",
); 
```

For add info to render info 

$_ARRAY_RUN_START = array(
    #modules_names
   'xeki_auth'
);


## Use in controllers
We can use this methods for create secure zones in you implementation.

#### General Example use
```php
// import module 
$xeki_auth =  \xeki\module_manager::import_module('xeki_auth');
// check if is logged, if is not logged is redirected to login page
$xeki_auth->check_logged();
```

#### Advance Example use
```php
// import module 
$xeki_auth =  \xeki\module_manager::import_module('xeki_auth');

// set name space of auth
$xeki_auth->set_name_space("my_name_space_application");

// set login page
$auth_module->set_logged_page("user/my_login_page");

// check if is logged, if is not logged is redirected to login page
$xeki_auth->check_logged();

if($xeki_auth->is_logged()){
    // do something
}

// check if have permision for some action
$xeki_auth->check_permission('my_action_code');

if($xeki_auth->have_permission()){
    // do something
}

```

#### All Methods
##### Import module

```php
$xeki_auth =  \xeki\module_manager::import_module('xeki_auth');
```

##### Check Auth
```php

// if not login redirect to login page

$auth_module->set_logged_page("my login custom");
$xeki_auth->check_logged();

// just return login or not 
$is_auth = $xeki_auth->check_auth();

```

##### Check by Name spaces
We can have diferentes name spaces for each system or 
```php

$xeki_auth->set_name_space("my_name_space_application");

$xeki_auth->check_logged();

$xeki_auth->check_permission('my_action_code');


```


##### Check Action Permision
```php

// if not login redirect to not permision page
$xeki_auth->check_logged();

// just return login or not 
$is_auth = $xeki_auth->check_auth("action_permission_code");

```

##### Other methods

Get info of user
```php
$user_info = $xeki_auth->get_user_info();

$user_info = $xeki_auth->get_persmissions_info();
```


