**SQL Module**

Import module.
```
$sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
```

Example to use:

```
$query = 'SELECT * FROM table';
$var = $sql->query($consulta);
```

## update

```

$sql->update($table = null, $array_of_values = array(), $conditions = 'FALSE')

```


```

$data_array=[];
$data_array['title']="":
$sql->update("tableName", $data_array, "sku='cd22'");

$sql->delete($table = null, $conditions = 'FALSE')

```
