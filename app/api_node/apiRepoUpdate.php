<?php  
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor mengenali variabel $cNode sebagai class sebelumnya
// if($cNode->nodeByChip($data->c,$data->n) == false) die("Node");
// contoh data masuk request f7 {"c":"XN0123456","f":7,"b":1677297230} 
// contoh data masuk request f7 {"c":"XN0123456"} ==> data biasa cek apakah ada update 
 
if(array_key_exists("b",$data)){
  $iBuildVersion=$data->b ;  
} else{
  ($cNode->cekUpdate() > 0)?$cNode->dieJsonOK(["f"=>7]) : $cNode->dieJsonNone(); //infokan keberadaan update
}

$sSQL="SELECT * FROM binfirupd WHERE id_chip= $cNode->chipID AND flag=7 AND build > $iBuildVersion";
$rHasil=$cNode->ambil1Row($sSQL);
if($rHasil){
  $respons["url_update"]="http://xn.online-farm.com/repo/" . $rHasil["file_repo"] . ".bin";
  $respons["f"]=8;
  $cNode->dieJsonOkTime($respons); 
}else{  //bila versi sudah baru.. update flag 8 di tabel binfirupd
  $sSQL="update binfirupd set flag=8 where id_chip=$cNode->chipID ";
  $cNode->eksekusi($sSQL);
  if(array_key_exists("v",$data)){ // ada versi app
    $versi = $data->v; 
    $sSQL="update chip set build = $iBuildVersion, versi=$versi where id=$cNode->chipID ";   
  }else{
    $sSQL="update chip set build = $iBuildVersion where id=$cNode->chipID "; 
  }
  $cNode->eksekusi($sSQL);
  $cNode->dieJsonNone();
}
 
$cNode->dieJsonNone();

?>