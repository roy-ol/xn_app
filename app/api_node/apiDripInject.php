<?php //apiRelay untuk systemdrip inject, awal system untuk Mergan GH 1
/* ===================== 
APIrelay => prioritas pemilihan role:
1 - test Aktuator *XT1*
2 - Tanggal aktual(NR_date)
3 - Hari(NR_week)
4 - Tanggal terbaru range aktual(NR_date)
*/

if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode();   
  // $data = json_decode($sDataDataPost);
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}  

$status = "0";   
$id_node = $cNode->nodeID ;

//===========pause kejar tayang agar alat GH mergan Jalan====
// bypas sementara hanya untuk melayani jadwal mingguan... 

$sSQL = "SELECT nr.*,nrw.id as id_nrw
FROM node_role_week nrw, node_role nr
WHERE nr.id=nrw.id_role and id_node = :id_node AND 
    (h1 = IF(DAYOFWEEK(NOW()) = 1, 1, 0)
    OR h2 = IF(DAYOFWEEK(NOW()) = 2, 1, 0)
    OR h3 = IF(DAYOFWEEK(NOW()) = 3, 1, 0)
    OR h4 = IF(DAYOFWEEK(NOW()) = 4, 1, 0)
    OR h5 = IF(DAYOFWEEK(NOW()) = 5, 1, 0)
    OR h6 = IF(DAYOFWEEK(NOW()) = 6, 1, 0)
    OR h7 = IF(DAYOFWEEK(NOW()) = 7, 1, 0)
    )
     AND TIME(NOW()) BETWEEN mulai AND selesai
LIMIT 1;   "; 
$param = ["id_node"=>$id_node];
$arrData = $cNode->ambil1Row($sSQL,$param);
if(!$arrData){
  // $cNode->dieJsonNone(); 
  $cNode->dieJsonNoneCheckUpdate(); 
  // $cNode->dieJsonNoneTime(); 
}
//id,id_perusahaan,pola,flag,exeval,satuan,reff_node,ref_n1,ref_n2,ref_n4,
// ref_n3,ref_n5,relay,repeater,limval0,limval1,keterangan,updater 

$id  = $arrData['id']; 
$id_nrw  = $arrData['id_nrw']; 
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
$limval0  = $arrData['limval0'];
$limval1  = $arrData['limval1'];
// $keterangan  = $arrData['keterangan']; 
// $istoday = $arrData['today']; 

$cNode->log_eksekutor(null,$id_nrw,$relay,$exeval,$exe_v1,$exe_v2);      
$respons['f']=20; //flag ada respons untuk aktuator action 
// $respons['sleep']=$sleeptime ; //isian bila ada setting sleep berubah
$respons['exeval']=$exeval ; // bisa jadi nilai menit / nantinya ml liter setelah kalibrasi
$respons["exe_v1"]=$exe_v1 ; // 23-12-2023 nilai sebagai target EC larutan (ppm = * 500)
$cNode->dieJsonOkTime($respons); 


?>