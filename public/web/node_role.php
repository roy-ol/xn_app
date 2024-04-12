<!DOCTYPE html>
<html>
<head>
    <title>Node Role</title>
</head>

<?php

require_once __DIR__ . '/menu.php';
if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cUmum sebagai class sebelumnya  
  // $cUser = new cUser();
  $cUmum = new cUmum();
}  

$sql = "select * from node_role";




$sHitTabel=bikinTabelSQL($sql);
echo $sHitTabel;

?>



</body>
</html>