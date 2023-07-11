<?php //sensor logger

if(!$_SERVER['REQUEST_METHOD']=='POST'){ die('page isn’t working ;('); }  //tidak ada post die

$sDataDataPost=file_get_contents('php://input');  
// logIncomingData($sDataDataPost); 
// echo $sDataDataPost;    
$data = json_decode($sDataDataPost);  //{"c":"XN0123456", "n":1, "r0":512, "v1":54, "t":"2022-05-26 02:28:34"}
if (json_last_error() != JSON_ERROR_NONE) {
  die('forbidden #1:'. json_last_error() . '=>'.$sDataDataPost); //cek format json apakah valid
}
require_once __DIR__ . '/../../app/init_class.php';

$chipID='NOCHIP';
$chipID=$data->c; 
$sensorNum=$data->n;  
(isset($data->r0))?$raw0=$data->r0 : $raw0=null ; 
(isset($data->v1))?$val1=$data->v1 : $val1=null ; 
(isset($data->t))?$WaktuNode=$data->t:$WaktuNode = false; //bila ada nilai t / waktu dari node
(isset($data->il))?$id_loc=$data->il:$id_loc = false; //bila ada nilai idl / id_loc id dari tabel senlog lokal
 
$cSensor = new cSensor();  
($cSensor->nodeByChip($chipID,$sensorNum))?$status = "in":die("forbidden #3");// sensor tidak ketemu atau flag = 0
$nodeID=$cSensor->nodeID;
// $valMap = $cSensor->value_map($rawV1);
$rInsert = $cSensor->logging($raw0,$val1,$WaktuNode,$id_loc);
if($rInsert) $status = "OK";


$respons = ["s" => $status];  
$respons["t"] = date('Y-m-d H:i:s');
// $respons["v"] = $valMap;
echo json_encode($respons) ;