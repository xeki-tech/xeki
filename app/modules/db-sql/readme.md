# Xeki php Admin
SQL for xeki 

## Todo 

## Instalation

### Xeki install
In console 
```
xeki add module xeki-sql
```


### Manual 
+ Clone 
+ Add to 

## How use
### Load Module
```
$sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');

```

### Manual query
```
$res = $sql->query("SELECT * FROM xeki_table where active='on' order by order_list asc");
foreach($res as $item){
    d($item); // print on xeki
}
```

### Update
```
$data = array(
    "email" => $user['new_email'],
);
$sql->update("user", $data, "field='{$value}'");
```
### Insert

```
$data = array(
    "email" => $user['new_email'],
);
$sql->insert("user", $data);

```