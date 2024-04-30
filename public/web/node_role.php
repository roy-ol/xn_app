<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Insert Data Node Role</title> 
</head>
<body>
<?php require_once __DIR__ . '/menu.php'; ?>

<!-- Tombol untuk membuka modal -->
<button onclick="bukaLink()">Add Role</button>
 

<?php

require_once __DIR__ . '/menu.php';
if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cUmum sebagai class sebelumnya  
  // $cUser = new cUser();
  $cUmum = new cUmum();
}  

$sql = "SELECT nr.id nrid, CONCAT('▶ ',nr.keterangan) NodeRole, CONCAT('R:',nr.relay,'\nX:',nr.exeval)'Rel xVal', CONCAT('V1:',nr.exe_v1 , ' V2:', 
  nr.exe_v2) 'Val1 Val2', CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated 
  FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ORDER BY nr.id DESC";  
$sHitTabel=bikinTabelSQL2($sql,"../web/node_role_form.php");
// $sql = "SELECT nr.keterangan NodeRole, nr.relay rl,nr.exeval xVal, CONCAT('V1:',nr.exe_v1 , ' V2:', 
//   nr.exe_v2) Val, CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated 
//   FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ORDER BY nr.id DESC";  
// $sHitTabel=bikinTabelSQL($sql);

echo $sHitTabel;

?>

<script> 
// Fungsi untuk buka link
function bukaLink() {
  window.location.href = "node_role_form.php";
}
 
</script>


</body>
</html>