<?php  
require_once __DIR__ . '/menu.php';
    
?> 
<head>
<title>NR_Mingguan</title>
</head>
</head>
<style> 
  .right-text {
    text-align: right;
    vertical-align: middle;
  }
</style> 
<h2>List Jadwal </h2>
<?php  
$id_role = 0 ;
$id_node = 0 ; 

$sql = "SELECT n.nama Node, nr.keterangan NodeRole, COUNT(nw.id) as 'Jumlah Jadwal'
FROM node_role nr 
INNER JOIN `node_role_week` nw ON nr.id = nw.id_role 
INNER JOIN node n ON n.id=nw.id_node 
WHERE nr.id_perusahaan = $id_perusahaan GROUP BY node;  ";
$sHitTabel=bikinTabelSQL($sql);
echo $sHitTabel;

?>

</body></html>