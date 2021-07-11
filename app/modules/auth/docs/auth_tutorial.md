# How use the module auth

## Dowload module 

Clone the following repository: xeki-framework/auth-module
[SSH](git@github.com:xeki-framework/auth-module.git) -
[HTTPS](https://github.com/xeki-framework/auth-module.git)
in the /modules folder.

## Run initial command
```
index.php install xeki_auth
```

## Setup DataBase
Configure the database in the following path: /core/modules_config/db-sql/config.php 
```
$MODULE_DATA_CONFIG = array(
    "main" => array(
        "host" => "host",
        "user" => "user",
        "pass" => "password",
        "db"   => "database",
    )
);
```

You can configure a secondary database, example:

```
$MODULE_DATA_CONFIG = array(
    "main" => array(
        "host" => "host",
        "user" => "user",
        "pass" => "password",
        "db"   => "database",
    ),
    "secondary" => array(
        "host" => "host",
        "user" => "user",
        "pass" => "password",
        "db"   => "database",
    )
);
```

## Create login page
To create a login page you need to create your view, which will render the form that will capture the data
<br>  
pages/login.php
```
<form method="POST">
    <div class="input-group form-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="email" name="email"/>
        
    </div>
    <div class="input-group form-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
        </div>
        <input type="password" class="form-control" name="pw" placeholder="password" />
    </div>
    <div class="form-group">
        <input type="submit" value="Login" class="btn center login_btn" />
    </div>
    <input type="hidden" value="auth::login" name="xeki_action" />
</form>
```

At this point you can capture the data in two ways:
<br>


### Handling the data (Option #1): action_method
In the file action_methods.php will import the module auth, and will use the data sent by the form to login

```
\xeki\routes::action('auth::login', function(){
    $auth = \xeki\module_manager::import_module('auth');
    $user = $auth->login($_POST['email'],$_POST['pw']);
    d($user->get_info());
});
```

### Handling the data (Option #2): url > controller
To use the data from a controller it is necessary to define the action in the form, and that same value will go in the file url.php

```
...
<form method="POST" action="url_value">
...
```

```
url.php: 

\xeki\routes::post('url_value', function($vars){
    $auth = \xeki\module_manager::import_module('auth');
    $user = $auth->login($_POST['email'],$_POST['pw']);
    d($user->get_info());
    if(\xeki\core::is_error($user)) {
        if($user->code == "invalid_pass"){/* ok!! */}
        else{
            d("08: Error ".$name_test);die();
        }
    }
});
```


## Create logout page
To close the user session you only need a button that directs to /logout
Example: ```<a href="{{URL_BASE}}logout"> Logout </a>```
<br>
This will redirect automatically to the url logout defined in the file url.php
<br>
```
\xeki\routes::any('logout', function(){
    $auth = \xeki\module_manager::import_module('auth');
    $auth->logout();
    \xeki\core::redirect('');
});
```
## Validate logged user restricted pages
To validate if a user is logged in we can do it from the contolador, first we import the auth module and then validate it in the following way:
```
$auth = \xeki\module_manager::import_module('auth');
if(!$auth->is_logged()){
   redirect to bla 
}
```
## Get info user 
Accessing user information is as simple as from the controller to store the following method in an array:
```
$auth = \xeki\module_manager::import_module('auth');
$data['user'] = $user->get_info();
```
- And then pass the information to the view
```
\xeki\html_manager::render('file.html', $data);
```
- And to use the information in the view, you only need to embed it in the flat html as follows:
```
<h1>{{user.id}}</h1>
<p>{{user.email}}</p>
```
You can also access unique data, as follows:

```
$auth = \xeki\module_manager::import_module('auth');
$user = $auth->get_user(); 
$data['user'] = $user->get_info(); //array data
$data['unique_data'] = $user->get("email"); //unique data
```
then you send the data array to the view
```
\xeki\html_manager::render('info_user.html', $data);
```
and to use it in the view: 
```
<h1>{{ unique_data }}</h1>
```
## Set global info for html
To send global data to the views, we must do it from the main.php file as follows: 
- We can send a single data, or an array of data
```
\xeki\html_manager::add_extra_data("var_name", "unique data");
\xeki\html_manager::add_extra_data("array_name", "array data");
```
And to use the data in any of our views, we just need to embed it in the html
```
<h1> Unique data: {{ var_name }} </h1>
<h1> Array data: {{ array_name.value }} </h1> 

```