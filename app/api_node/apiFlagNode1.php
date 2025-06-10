<?php  
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor mengenali variabel $cNode sebagai class sebelumnya
// if($cNode->nodeByChip($data->c,$data->n) == false) die("Node"); 
$flag=$data->f; //contoh data masuk request f1 {"c":"XN0123456","f":5}


switch ($flag) {
  case 1:
    updateFlag1();    
    break;
  case 10:
    storeJsonConfig($data);    
    break;
  
  default:
    def_flagNode($flag);
    break;
}


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
 
function updateFlag1(){
  global $cNode;
  $cNode->dieJsonOkTime(array('f'=>0));
}







function def_flagNode($flag){
  global $cNode;
  $r=$cNode->flagStatusNode($flag);
  if($r > 0) $cNode->dieJsonOkTime(array('f'=>9));
  else $cNode->dieJsonOkTime(array('f'=>0));
}
?>