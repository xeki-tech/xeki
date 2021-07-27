# Xeki base project 

## Quick build

### Composer 
Install composer https://getcomposer.org/
After installed run on folder of you proyect composer install

### Create project 

#### init project

```
    composer create-project xeki-tech/xeki myProject
```

#### add modules

```
    composer require xeki-tech/html-twig
    composer require xeki-tech/db
    composer require xeki-tech/auth
    composer require xeki-tech/admin
```



## Deploy
- local
- docker 
- cloud run 
- app engine
- cpanel 


## Navigation


```
\xeki\routes::any('', 'home');

 \xeki\routes::any('demo-function', function(){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "date"=>date("Y-m-d")
        ]
    );
 });


\xeki\routes::post('url/with/vars/{regexVar:[1-9]}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "regexVar"=>$var['regexVar']
        ]
    );
});


\xeki\routes::any('url/with/vars/{nameVar:.+}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "var"=>$var['nameVar']
        ]
    );
});


\xeki\routes::post('url/with/vars/{numberVar:\d+}', function($var){
    \xeki\core::PrintJson(
        [
            "response"=>'demo',
            "var"=>$var['numberVar']
        ]
    );
});


```