<?php // fungsi umum berhubungan dengan aktuator


if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode();   
  $data = json_decode($sDataDataPost); 
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}  

/**
 * @brief memeriksa apakah request aktuator ini ada data id_log 
 *      untuk mengupdate tabel / flag status log ekeskutor
 * @param $data hasil decode json dari dataDataPost request aktuator
 */
function cekLogEksekutor($data){ 
global $cNode; 
  //jika kiriman berupa report status sukses aktuator
  if(isset($data->id_log)){
    $id_log_eksekutor = $data->id_log;  //ambil data berupa object
    // $id_log_eksekutor = $data["id_log"]; //ambil data berupa array
    $cNode->flag_eksekutor($id_log_eksekutor);
    $cNode->dieJsonNoneCheckUpdate();
  }
}

/**
 * @brief node_xt ============= execution test============
 */ 
function cekReqExecutionTest($id_node){
  global $cNode;

  $param = ["id_node"=>$id_node];
    
  $arrData = false;
  $sql = "SELECT id,exeval,relay,exe_v1,exe_v2 FROM node_xt WHERE flag=0 and id_node = :id_node ;";
  $arrData = $cNode->ambil1Row($sql,$param);
  if($arrData){
    $id  = $arrData['id'];    
    $relay  = $arrData['relay'];    
    $exeval  = $arrData['exeval']; 
    $exe_v1  = $arrData['exe_v1']; 
    $exe_v2  = $arrData['exe_v2']; 
    $cNode->eksekusi("update node_xt set flag = 9 where id=$id"); //update tabel flag menjadi 9(terambil / dalam proses)
    $idInsert = $cNode->log_eksekutor(null,null,$relay,$exeval,$exe_v1,$exe_v2);      
    $respons['id_log']=intval($idInsert); // id log eksekutor yang baru dibuat di tabel log
    $respons['f']=20; //flag ada respons untuk aktuator action 
    // $respons['sleep']=$sleeptime ; //isian bila ada setting sleep berubah
    $respons['exeval']=$exeval ; // bisa jadi nilai menit / nantinya ml liter setelah kalibrasi
    $respons["exe_v1"]=$exe_v1 ; // 23-12-2023 nilai sebagai target EC larutan (ppm = * 500)
    $respons["relay"]=$relay ;//kode / relay
    
    $cNode->dieJsonOkTime($respons); 
  }
}

?>