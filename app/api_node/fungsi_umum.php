<?php // fungsi - fungsi umum berhubungan dengan node

if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode(); //cNode sebelumnya sudah dikonstructor di app/class/cNode.php
  $data = json_decode($sDataDataPost); 
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}   

$status = "0";   
$id_node = $cNode->nodeID ;
$param = ["id_node"=>$id_node];
$is_aktuator = false;

$sql="SELECT t.kelompok FROM node n JOIN chip c ON c.id = n.id_chip
JOIN tipe t ON t.id = c.id_tipe WHERE n.id = :id_node ";
$kelompok = $cNode->ambil1Data($sql,$param);
if($kelompok == 2 || $kelompok == 3){ // kelompok aktuator
  $is_aktuator = true;
  $id_nrd = 0;
  $id_nrw = 0;
  
  include_once 'genAktuator.php'; 
  cekLogEksekutor($data); 
  //===========1 - test Aktuator *XT1* tabel node_xt==============
  cekReqExecutionTest($id_node);  
}

$flag=$data->f; //contoh data masuk request f1 {"c":"XN0123456","f":5}
switch ($flag) {
  case 0:   // umum
    if($is_aktuator) flag0_aktuator();   
    break;
  case 10:
    storeJsonConfig($data);    
    break;
  
  default:
    def_flagNode($flag); // kiriman flag belum terakomodir di fungsi umum langsung di save
    break;
}

$cNode->dieJsonOkTime(array('f'=>$status));

// ======================================== fungsi fungsi========================================
// ==============================================================================================




function storeJsonConfig($data){ //pause==== on testing
  global $cNode;
  $arrRespons['f']=6;   //kode 6 = fail bila ada kesalahan
  
  $hasJson = isset($data->sConfig) && is_string($data->sConfig) && !empty($data->sConfig);
  if (!$hasJson) $cNode->dieJsonOkTime($arrRespons);
  
  $sJson = $data->sConfig;
  $iRecAff = $cNode->log_json_config($sJson); 
  if($iRecAff > 0){
    $arrRespons['f']=9;
    $sSQL = "update chip set flag=0 where id=$cNode->chipID ";  
    $cNode->eksekusi($sSQL);
  }
  $cNode->dieJsonOkTime($arrRespons);
}

function def_flagNode($flag){
  global $cNode;
  $r=$cNode->flagStatusNode($flag);
  if($r > 0) $cNode->dieJsonOkTime(array('f'=>9));
  else $cNode->dieJsonOkTime(array('f'=>0));
}

?>