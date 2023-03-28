<?php  
//api awal untuk aplikasi relay drip system GH Metro
include_once '../../app/api_node/apiUmum.php';   


// $data = json_decode($sDataDataPost); 
$waktu =  gmdate("Y-m-d H:i:s",time() + (3600 * 7)); //gmt+7 jam 

if(isset($_GET['kode'])){
  $kodeApiFile = $_GET['kode'];   
  if(!$kodeApiFile) die("noCode");
  $cNode = new cNode();
  logIncomingData($kodeApiFile . "->" . $sDataDataPost);
  (isset($data->c))?:die("chip"); 
  (isset($data->n))?:$data->n=1; // hanya chip tanpa sub_node n default = 1
  if($cNode->nodeByChip($data->c,$data->n) == false) die("Node");

  switch ($kodeApiFile) { //======pause switch.  ===== case ada yang belum di tes=========
    case 'sl':
      include_once '../../app/api_node/apiSensorLoger.php'; //sensor logger 
      break; 
    case 'd1':
      include_once '../../app/api_node/apiRelayDrip1.php'; //aktuator metro
      break; 
    case 'd2':  //dipanggil ESP url =  http://xn.online-farm.com/node/d2 belum OK
      include_once '../../app/api_node/apiRelayDrip2.php';
      break;
    case 'f1':    //flag Status node  OK : 11-03-2023
      include_once '../../app/api_node/apiFlagNode1.php';
      break;
    case 'f7':    //request url repo binary update OK : 2023-03-05
      include_once '../../app/api_node/apiRepoUpdate.php';
      break; 
    default: // OK : 2023-03-05
      cNodeFailLog("default Switch",$sDataDataPost  );
      die("failcode");
      break;
  } 
  cNodeFailLog($kodeApiFile.": sw int",$sDataDataPost );
  die("switch internal fail");
} else{ 
  cNodeFailLog("no kode",$sDataDataPost);
  die("it's forbidden -kode");
}  
 
die("t o"); 

//=============================fungsi fungsi==================================
//=============================fungsi fungsi================================== 
 
function logIncomingData($dataMasuk){  //log aktuator maksimal sejumlah record di tabel / 5 data terakhir 
  global $cNode; 
  $rData= $cNode->ambil1Row("SELECT COALESCE(min(hit),0) min, COALESCE(max(hit),0) max
      FROM `log_apig` ") ; //WHERE id_node= $idNode "); 
  $min=$rData['min'];
  $max=$rData['max'];
  
  $sql = "update log_apig set data='$dataMasuk', hit = " . ($max + 1);
  $sql .= " where hit = $min" ;  

  $cNode->eksekusi($sql);
}

function cNodeFailLog($sSumber = "", $dataMasuk=""){  //log error node/index.php
  global $cNode; 
  $dataMasuk .= " =>".$_SERVER['REMOTE_ADDR']; 
  $rData= $cNode->ambil1Row("SELECT COALESCE(min(hit),0) min, COALESCE(max(hit),0) max
      FROM `log_fail` ") ; //WHERE id_node= $idNode "); 
  $min=$rData['min'];
  $max=$rData['max'];  
  $sql = "update log_fail set sumber='node/$sSumber', data= :dt , hit = " . ($max + 1);
  $sql .= " where hit = $min" ;  
  $param = ["dt" => $dataMasuk];
  $cNode->eksekusi($sql,$param);
}

//pause=========

function logAktuator2($idNode, $relay=1, $exetime = 0){  //log aktuator 5 data terakhir
  global $cNode; 
  $rData=$cNode->ambilData("SELECT count(id) jum, COALESCE(max(hit),0) max, COALESCE(min(hit),0) min 
      FROM `log_aktuator2` WHERE id_node= $idNode ");
  $jum=$rData['jum'];
  $max=$rData['max'];
  $min=$rData['min'];
  if ($jum < 5) {  // batas Record yang disimpan di log aktuator tiap id_node
    $sql = "insert into log_aktuator2(id_node,relay,exetime,hit) values($idNode ,";
    $sql .= " $relay , $exetime , " . ($max + 1) . ")" ; 
  }else{
    $sql = "update log_aktuator2 set relay=$relay, exetime = $exetime, hit = " . ($max + 1);
    $sql .= " where id_node = $idNode and hit = $min" ;  
  } 
  $r = $cNode->eksekusi($sql);
}

  
?>