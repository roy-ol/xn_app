<?php

require_once __DIR__ . '/../app/init_class.php';
 
$sql = " select * from hit_time limit 1";
// $con = new cKoneksi();
$con = new cSensor();

$result = $con->ambil1Row($sql);

// echo $result[0]." -- ".$result[1]." -- ".$result[2]." -- ".$result[3]." -- " . $result[4];
echo "<br> didalam folder ". __DIR__." <br><hr>";
echo $result["id"]." -- ".$result["waktu"]." -- ".$result["ip_remote"]." -- ".$result["kode"];
echo "<br><hr>";

// foreach ($result as $key => $value) {
//   # code...
// }
// print_r($result); 
$chipID = "XN0123456";
$noSensor = 2;
$sql = "select * from node where chip= :chipID and no_sensor = :noSensor";
$param = ["chipID"=>$chipID,"noSensor" =>$noSensor];
$hasil = $con->ambil1Row($sql,$param);

// ($hasil)?:die('forbidden #2');
echo "<br>jum arr " .count($hasil) . "<hr>";
var_dump($hasil);
echo "<hr>" ;
echo $hasil["keterangan"];
echo "<hr>" ;
print_r($hasil);
echo "<hr>";


$cSensor = new cSensor();  
($cSensor->nodeByChip($chipID,$noSensor))?:die("forbiden #4");
echo $cSensor->getID()."<br>cSensor - ";
echo $cSensor->keterangan;