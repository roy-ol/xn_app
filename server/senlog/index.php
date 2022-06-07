<?php //sensor logger

if(!$_SERVER['REQUEST_METHOD']=='POST'){ die('page isn’t working ;('); }  //tidak ada post die

$sDataDataPost=file_get_contents('php://input');  
// logIncomingData($sDataDataPost); 
// echo $sDataDataPost;    
$data = json_decode($sDataDataPost);
(json_last_error() == JSON_ERROR_NONE)?:die('forbidden #1'); //cek format json apakah valid

require_once __DIR__ . '/../../app/init_class.php';

$chipID='NOCHIP';
$chipID=$data->c; 
$sensorNum=$data->n; 
$rawV1=$data->v1;  
(isset($data->t))?$WaktuNode=$data->t:$WaktuNode = false; //bila ada nilai t / waktu dari node
 
$cSensor = new cSensor();  
($cSensor->nodeByChip($chipID,$sensorNum))?$status = "in":die("forbidden #3");// sensor tidak ketemu atau flag = 0
$nodeID=$cSensor->nodeID;
// $valMap = $cSensor->value_map($rawV1);
$rInsert = $cSensor->logging($rawV1,$WaktuNode);
if($rInsert) $status = "OK";


$respons = ["s" => $status];  
$respons["t"] = date('Y-m-d H:i:s');
// $respons["v"] = $valMap;
echo json_encode($respons) ;