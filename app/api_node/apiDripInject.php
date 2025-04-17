<?php //apiRelay untuk systemdrip inject, awal system untuk Mergan GH 1
/* ===================== 
APIrelay => prioritas pemilihan role:
1 - test Aktuator *XT1* tabel node_xt
2 - Tanggal terakhir nr_date kalau ada
3 - Hari(NR_week) 
*/


if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode();   
  // $data = json_decode($sDataDataPost);
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}  

$status = "0";   
$id_node = $cNode->nodeID ;
$param = ["id_node"=>$id_node];
$id_nrd = 0;
$id_nrw = 0;

include_once 'genAktuator.php';
// include_once '../api_node/genAktuator.php' ;
cekLogEksekutor($data); 
//===========1 - test Aktuator *XT1* tabel node_xt==============
cekReqExecutionTest($id_node);  

//===========2 - Tanggal terakhir nr_date kalau ada==============
$sSQL = "SELECT nrd.id as id_nrd, nr.* FROM node_role_date nrd 
  JOIN node_role nr ON nrd.id_role = nr.id 
  WHERE nrd.id_node = :id_node 
    AND nrd.tanggal = (
        SELECT MAX(tanggal) 
        FROM node_role_date 
        WHERE id_node = :id_node 
          AND tanggal <= CURDATE()
    ) 
    AND TIME(NOW()) BETWEEN nrd.mulai AND nrd.selesai 
  ORDER BY nrd.tanggal DESC, nrd.mulai ASC 
  LIMIT 1;"; 
$arrData = $cNode->ambil1Row($sSQL,$param); 
if($arrData){ // ada order id_nrd
  $status = "1";
  $id_nrd = $arrData["id_nrd"];
  if(cekFlagEksekutor($id_nrd,0) > 0) $cNode->dieJsonNoneTime(); // ada log eksekutor pada hari ini
}else{ //===========3 - Hari(NR_week)============== // mingguan  
  $sSQL = "SELECT nr.*,nrw.id as id_nrw
  FROM node_role_week nrw, node_role nr
  WHERE nr.id=nrw.id_role and id_node = :id_node AND 
        (h1 = IF(DAYOFWEEK(NOW())=1, 1, 8)
      OR h2 = IF(DAYOFWEEK(NOW())=2, 1, 8)
      OR h3 = IF(DAYOFWEEK(NOW())=3, 1, 8)
      OR h4 = IF(DAYOFWEEK(NOW())=4, 1, 8)
      OR h5 = IF(DAYOFWEEK(NOW())=5, 1, 8)
      OR h6 = IF(DAYOFWEEK(NOW())=6, 1, 8)
      OR h7 = IF(DAYOFWEEK(NOW())=7, 1, 8)
      )
      AND TIME(NOW()) BETWEEN mulai AND selesai
  LIMIT 1;   "; 
  $arrData = $cNode->ambil1Row($sSQL,$param); 
  if($arrData){ // ada order id_nrw
    $status = "2";
    $id_nrw = $arrData["id_nrw"];
    if(cekFlagEksekutor(0,$id_nrw) > 0) $cNode->dieJsonNoneTime(); // ada log eksekutor pada hari ini
  }  
} 

if($status == "0") $cNode->dieJsonOK(); //tidak ada jadwal eksekutor 
 
$id  = $arrData['id'];  
$flag  = $arrData['flag'];
$pola  = $arrData['pola'];  
$exeval  = $arrData['exeval']; 
$exe_v1  = $arrData['exe_v1']; 
$exe_v2  = $arrData['exe_v2']; 
$satuan  = $arrData['satuan']; 
$reff_node  = $arrData['reff_node']; 
$ref_n1  = $arrData['ref_n1']; 
$ref_n2  = $arrData['ref_n2']; 
$ref_n3  = $arrData['ref_n3']; 
$ref_n4  = $arrData['ref_n4']; 
$ref_n5  = $arrData['ref_n5'];  
$relay  = $arrData['relay'];
$repeater  = $arrData['repeater']; 
$limval0  = $arrData['nilai_1'];
$limval1  = $arrData['nilai_2']; 

$idInsert = $cNode->log_eksekutor($id_nrd,$id_nrw,$relay,$exeval,$exe_v1,$exe_v2);      
$respons['id_log']=intval($idInsert); // id log eksekutor yang baru dibuat di tabel log
$respons['f']=20; //flag ada respons untuk aktuator action 
// $respons['sleep']=$sleeptime ; //isian bila ada setting sleep berubah
$respons['exeval']=intval($exeval) ; 
$respons["exe_v1"]=intval($exe_v1); 
$respons["exe_v2"]=intval($exe_v2); 
$respons["relay"]=intval($relay) ; //kode / relay 
 
$cNode->dieJsonOkTime($respons);  

?>