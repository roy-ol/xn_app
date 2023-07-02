<?php  
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor mengenali variabel $cNode sebagai class sebelumnya

/* ======================================================
apirelay untuk aktuator Drip :
#define RELAY0 15 //D8 Snubber  //pulled down D8 bootfail on high
#define RELAY1 5  //D1  doz A
#define RELAY2 4  //D2  doz B
#define RELAY3 14 //D5  POWER
#define RELAY4 12 //D6  Pump Drip
#define RELAY5 13 //D7  Pump Mix

// Kediri Tegowangi
 ========================================================*/

// $waktu
// $data = json_decode($sDataDataPost);
// $chipID=$data->c;
// $id_node = $dataNode['id'];
 
// {"f": 2, "xtime": 18, "r0":1, "r1":1, "r2":1, "r3":1, "r4":1, "r5":1, "r6":1, "r7":1, "sleep":45}
// $arrRespons['f']=0; //flag respons ke ESP tidak ada yang perlu dilakukan
$responNone = '{"f":0}';  //flag respons ke ESP tidak ada yang perlu dilakukan

$arrData=$cNode->ambil1Row("SELECT COALESCE(tanggal=CURDATE(),0) AS today, nr.* FROM  node_role nr WHERE id_node = $id_node AND keterangan LIKE '%*xt1*%' UNION 
    SELECT COALESCE(tanggal=CURDATE(),0) AS today, nr.* FROM  node_role nr WHERE id_node = $id_node AND CURTIME() BETWEEN time0 AND time1 ");
if(!$arrData){
  mysqli_close($con);
  die($responNone);
}
$id  = $arrData['id']; 
$flag  = $arrData['flag'];
$waktu  = $arrData['waktu'];
$sleeptime  = $arrData['sleeptime'];
$exetime  = $arrData['exetime']; 
$relay  = $arrData['relay'];
$repeater  = $arrData['repeater'];
$time0  = $arrData['time0'];
$time1  = $arrData['time1'];
$limval0  = $arrData['limval0'];
$limval1  = $arrData['limval1'];
$keterangan  = $arrData['keterangan'];
$tanggal = $arrData['tanggal']; 	 
$istoday = $arrData['today']; 	 

//===============================================
//prasyarat isi $time_relay_on
//===============================================
// $isXNormal=(strpos($keterangan, '*xt1*')?false:true); //bernilai false bila strpos menghasilkan 0
$xt1=strpos($keterangan, '*xt1*');
//==== bila log tanggal == tanggal hari ini dan repeater = 'N'  
if ($repeater == 'N' && $istoday == 1 && $xt1 === false) $cNode->dieJsonNone();
//===lanjut bila tanggal != CURDATE() atau repeater = Y  atau xt1=false / ada request xt1          
if($xt1 !== false){ 
  $xtreset=strpos($keterangan, '*xt1*rst*');  //perintah untuk mereset tanggal agar tidak terkunci repeater Off 
  if($xtreset !== false){ // The statement (0 != false) evaluates to false. harus !==
    $keterangan2=substr($keterangan,0,$xtreset) . substr($keterangan,$xtreset+9) ; 
    $rupdsql = mysqli_query($con,"UPDATE node_role SET tanggal=NULL , keterangan= '" .$keterangan2 .  "' WHERE id= $id "); //hilangkan perintah *xt1*reset*  
    
    $respons['f']=2; //flag ada respons untuk update Sleep
    $respons['sleep']=$sleeptime ; 
    
    mysqli_close($con);
    die(json_encode($respons)); 
  }
  $keterangan2=substr($keterangan,0,$xt1) . substr($keterangan,$xt1+5) ; 
  $rupdsql = mysqli_query($con,"UPDATE node_role SET keterangan= '" .$keterangan2 .  "' WHERE id= $id "); //hilangkan perintah *xt1*
}else{ //xt1 tidak merubah flag tanggal eksekusi
  $rupdsql = mysqli_query($con,"UPDATE node_role SET tanggal = CURDATE() WHERE id= $id "); //update tanggal berisi hari ini
}
   
logAktuator($chipID,$relay,$exetime);  
logAktuator2($id_node , $relay , $exetime);  

$respons['f']=20; //flag ada respons untuk aktuator action 
$respons['sleep']=$sleeptime ; 
$respons['xtime']=$exetime ;
//untuk aktuator4 : 1 Drip, 2 Mix, 3 Dozing
$respons["relay"]=$relay ; // penugasan, nomer relay mana yang on (0 = aktif low)

mysqli_close($con);
die(json_encode($respons)); 
 
 
?>