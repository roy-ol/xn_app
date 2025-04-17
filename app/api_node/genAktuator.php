<?php // fungsi umum berhubungan dengan aktuator


if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode();   
  $data = json_decode($sDataDataPost); 
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}  

/**
 * @brief Cek apakah sudah ada log eksekutor untuk idNode dan noderole id tertentu
 *       pada hari ini (berdasarkan tanggal CURDATE()).
 * @param int $id_nrw id node role week
 * @param int $id_nrd id node role date
 * @return int id log eksekutor atau 0 jika tidak ada
 */
function cekFlagEksekutor($id_nrd,$id_nrw){
  global $cNode;
  $id_log = 0;

  $sql="SELECT le.id FROM log_eksekutor le 
  JOIN node_role_week nrw ON nrw.id = le.id_nr_week 
  JOIN node_role nr ON nr.id = nrw.id_role 
  WHERE DATE(le.created) = CURDATE() AND  nr.repeater = 0
  AND le.id_node = :id_node "; 
  if($id_nrw > 0){
    $sql .= " AND le.id_nr_date= :id_nr_date";
    $param["id_nr_date"]=$id_nrd;
  }elseif($id_nrd > 0){
    $sql .= " AND le.id_nr_week= :id_nr_week";
    $param["id_nr_week"]=$id_nrw;
  }

  $param["id_node"] = $cNode->nodeID;  
  $id_log = $cNode->ambil1Data($sql,$param); 
  return $id_log;
}

/**
 * @brief memeriksa apakah request aktuator ini ada data id_log 
 *      untuk mengupdate tabel / flag status log ekeskutor
 * @param $data hasil decode json dari dataDataPost request aktuator
 */
function cekLogEksekutor($data){ 
global $cNode; 
  //jika kiriman berupa report status sukses aktuator di eksekusi dengan id_log
  if(isset($data->id_log)){
    $id_log_eksekutor = $data->id_log;  //ambil data berupa object
    // $id_log_eksekutor = $data["id_log"]; //ambil data berupa array
    $iRowAffected = $cNode->flag_eksekutor($id_log_eksekutor);
    $cNode->dieJsonOK(["row"=>$iRowAffected],true); //true = cek apakah ada update binfirupdate untuk dieksekusi
    // $cNode->dieJsonOK(["f"=>0],true);
  }
}

/**
 * @brief memeriksa apakah ada permintaan eksekusi aktuator testing 
 * @brief node_xt ============= execution test============
 * @param $id_node
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
    $respons['exeval']=intval($exeval) ; // bisa jadi nilai menit / nantinya ml liter setelah kalibrasi
    $respons["exe_v1"]=intval($exe_v1); // 23-12-2023 nilai sebagai target EC larutan (ppm = * 500)
    $respons["exe_v2"]=intval($exe_v2); // 13-06-2024 nilai tambahan parameter
    $respons["relay"]=intval($relay) ; //kode / relay
    
    $cNode->dieJsonOkTime($respons); 
  }
}

?>