<?php  
/* ======================================================
apirelay untuk aktuator metro tandon 1
 ========================================================*/

// $waktu
// $data = json_decode($sDataDataPost);
// $chipID=$data->c;
// $id_node = $dataNode['id'];
 
// {"f": 2, "xtime": 18, "r0":1, "r1":1, "r2":1, "r3":1, "r4":1, "r5":1, "r6":1, "r7":1, "sleep":45}
// $arrRespons['f']=0; //flag respons ke ESP tidak ada yang perlu dilakukan
$responNone = '{"f":0}';  //flag respons ke ESP tidak ada yang perlu dilakukan

$arrData=ambilData("SELECT COALESCE(tanggal=CURDATE(),0) AS today, nr.* FROM  node_role nr WHERE id_node = $id_node AND keterangan LIKE '%*xt1*%' UNION 
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
if ($repeater == 'N' && $istoday == 1 && $xt1 === false) keluar();
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

switch ($relay) { //r0=A1 r1=A2 r3=A2 
  case 1: //drip nutrisi
    aktuatorOn(2,6);
    break;  
  case 2: //Flushing
    aktuatorOn(1,3);
    break;  
  case 3: //isi Tandon 
    aktuatorOn(1,4);
    break;  
  case 4: //Aduk Nutrisi 
    aktuatorOn(2,5);
    break;  
  case 5: //pompa Air Baku 
    aktuatorOn(1);
    break;  
  default:
    if($relay >= 20 && $relay < 84 ){
      keluar(); // for next dec to binner relay
    }else{ 
      keluar();
    }
    // diluar kode
    break;
}
   
mysqli_close($con);
die(json_encode($respons)); 

function aktuatorOn($on1, $on2=0, $on3=0, $on4=0, $on5=0, $on6=0){ 
  global $respons;
  if($on1 > 0 ) $respons["r".($on1 - 1)]=0 ;
  if($on2 > 0 ) $respons["r".($on2 - 1)]=0 ;
  if($on3 > 0 ) $respons["r".($on3 - 1)]=0 ;
  if($on4 > 0 ) $respons["r".($on4 - 1)]=0 ;
  if($on5 > 0 ) $respons["r".($on5 - 1)]=0 ;
  if($on6 > 0 ) $respons["r".($on6 - 1)]=0 ;
}
 
?>