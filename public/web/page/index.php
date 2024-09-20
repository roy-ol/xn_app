<?php
require_once '../fungsi/koneksi_umum.php';
if( 1 == 0){
  $cUmum = new cUmum();
  $cUser = new cUser();
}
 
// // Cek apakah pengguna sudah login atau belum
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//   header("Location: ../login.php"); // Redirect ke halaman login.php jika belum login
//   exit;
// }

// echo "<pre>";
// print_r($_GET);
// echo "</pre>"; 

$val1 = "";$val2 = "";$val3 = "";$val4 = "";$val5 = "";
require_once "../template/cTemplate.php";

if(!isset($_GET['kode'])){  // dari htaccess folder ini: RewriteRule ^(.*)$ index.php?kode=$1 [L]   
  // $cTemp = new cTemplate("dashboard");	 
  // include_once "../template/header.php";
  // include_once "dashboard.php";      
  // include_once "../template/footer.php"; 
  // exit;    
  $sNamaFile = "dashboard";
}else{  
  $sKode = $_GET['kode'];
  if(strlen($sKode) > 999){
    $sKode = substr($sKode, 0, 999);
  }  
  $arrKode = explode("$$", $sKode);
  $iJumData=count($arrKode);
  if($iJumData>0) $sNamaFile = $arrKode[0]; 
  if($iJumData>1) $val1 = $arrKode[1]; 
  if($iJumData>2) $val2 = $arrKode[2]; 
  if($iJumData>3) $val3 = $arrKode[3]; 
  if($iJumData>4) $val4 = $arrKode[4];
  if($iJumData>5) $val5 = $arrKode[5];
  // echo $iJumData ." data : $sNamaFile 1= $val1 2= $val2 3= $val3";
  // header("Location: $sNamaFile.php");
  // require_once "../template/cTemplate.php";
  // $cTemp = new cTemplate($sNamaFile); 
  // $cTemp->setHeaderCap("cap: " . $sNamaFile);
  // $cTemp->tampil(); 
}

$sFilePHP = $sNamaFile . ".php";
//periksa apakah ada file PHP $sFilePHP
if(!file_exists($sFilePHP)){
  $sFilePHP = "dashboard.php";
  $sNamaFile = "dashboard";
} 
$cTemp = new cTemplate($sNamaFile); 
include_once "$sFilePHP";
 
$cTemp->loadFooter(); 
?>