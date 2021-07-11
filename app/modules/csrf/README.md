# csrf Module 
Implements csrf for xeki framework 

## quick use 
Add this to core/main.php
```
$csrf = \xeki\module_manager::import_module('csrf');
\xeki\html_manager::add_extra_data("csrf", $csrf->get_token_html());
```

In pages add 
```
<form>
    {{csrf|raw}}
    <input>
    <button>Send</button>
</form>
```

In actions or request 
```
function(){
    $csrf = \xeki\module_manager::import_module('csrf');
    $valid_csrf = $csrf->validate_token();
    if($valid_csrf){
        // handling form 
    }
    else{
        // handling error
    }
}
```