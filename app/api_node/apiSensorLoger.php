<?php //sensor logger
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor mengenali variabel $cNode sebagai class sebelumnya 
// $data = json_decode($sDataDataPost);  //{"c":"XN0123456", "n":1, "r0":512, "v1":54, "t":"2022-05-26 02:28:34"}  

(isset($data->r0))?$raw0=$data->r0 : $raw0=null ; 
(isset($data->v1))?$val1=$data->v1 : $val1=null ; 
(isset($data->t))?$WaktuNode=$data->t:$WaktuNode = false; //bila ada nilai t / waktu dari node
(isset($data->il))?$id_loc=$data->il:$id_loc = false; //bila ada nilai il / id_loc id dari tabel senlog lokal

//========pause for next ada sedikit update tambahan.. belum memperhitungkan maksimal data
  // if($cNode->nodeByChip($data->c,$data->n) == false) die("Node"); //sudah dipanggil di index
$nodeID=$cNode->nodeID;
// $valMap = $cSensor->value_map($rawV1);
$status = "-";  
$rInsert = $cNode->logging($raw0,$val1,$WaktuNode,$id_loc);  
if($rInsert) $status = "OK"; 
$respons = ["s" => $status];    
$respons["t"] = $waktu; 
$cNode->dieJson($respons);


