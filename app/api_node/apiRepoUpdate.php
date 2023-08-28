<?php  
if(1==0) $cNode = new cNode();   //dummy if syntact hanya agar editor vsc mengenali variabel $cNode sebagai class sebelumnya
// if($cNode->nodeByChip($data->c,$data->n) == false) die("Node");
// contoh data masuk request f7 {"c":"XN0123456","f":7,"b":1677297230} 
// contoh data masuk request f7 {"c":"XN0123456"} ==> data biasa cek apakah ada update 
 
// if(array_key_exists("b",$data)){  //deprecated
if(isset($data->b)){
  $iBuildVersion=$data->b ;  //indikasi / jawaban balik dari node cek build num
} else{
  ($cNode->cekUpdate() > 0)?$cNode->dieJsonOK(["f"=>7]) : $cNode->dieJsonNone(); //infokan keberadaan update/keluar
}

//pause ===

$sSQL="SELECT c.build, b.build as bin_build, b.file_repo FROM chip c , binfirupd b where b.id = c.id_repo 
  AND c.id=$cNode->chipID ";
  // AND c.id=$cNode->chipID AND b.build > $iBuildVersion";
$rHasil=$cNode->ambil1Row($sSQL);
if($rHasil){
  $cBuild =intval($rHasil["build"]);  // fungsi menjadikan nilai integer
  $bBuild = (int) $rHasil["bin_build"];  // casting menjadikan nilai integer
  if($bBuild > $iBuildVersion ){
    $respons["url_update"]="http://xn.online-farm.com/repo/" . $rHasil["file_repo"] . ".bin";
    $respons["f"]=8;
    $cNode->dieJsonOkTime($respons); 
  }else{ //jika iBuild sudah lebih besar  dengan repo Build / binRepoBuild / bBuild
    if($cBuild < $iBuildVersion){ 
        if(isset($data->v)){ // ada versi app
          $versi = $data->v; 
          $sSQL="update chip set flag=8, build = $iBuildVersion, versi=$versi where id=$cNode->chipID ";   
        }else{ 
          $sSQL="update chip set flag=8, build = $iBuildVersion where id=$cNode->chipID "; 
        }
        $cNode->eksekusi($sSQL);
        $cNode->dieJsonNone();
    }else{
      $sSQL="update chip set flag=5 where id=$cNode->chipID "; // jika sudah besar/sama nilai ibuild vs cBuild
      $cNode->eksekusi($sSQL);
    }
  }  
} 
 
$cNode->dieJsonNone();

?>