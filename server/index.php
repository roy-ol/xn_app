<?php
$sDefHTML = "<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don't have permission to access this resource.</p><hr><address>Apache/4.4.5 (UNIX) OpenSSL/2.4.3h PHP/8.9.1 Server at Port 80 </address>"; $sDefHTMLtutup = "</body></html>";$sDefHTMLisi = "";

if($_SERVER['REQUEST_METHOD']=='POST'){ 
	//Getting values  
	$sDataDataPost=file_get_contents('php://input');	
} elseif($_SERVER['REQUEST_METHOD']=='GET'){ 
  $sDefHTMLisi = "forbidden "; 
  $sDefHTML .= $sDefHTMLisi . $sDefHTMLtutup;
  die($sDefHTML); 
}else{
  $sDefHTMLisi = "forbidden : NOr GET Nor POST"; 
  $sDefHTML .= $sDefHTMLisi . $sDefHTMLtutup;
  die($sDefHTML); 
}

// dari sini ada data post untuk diolah:
// ==========================================
require_once __DIR__ . '/../app/init_class.php';

$data = json_decode($sDataDataPost);
$myUser = new cUser();

if(isset($data->regkey)){ // ada permintaan registrasi user dari android
  $regkey = $data->regkey;
  
}

(isset($data->kunci))?$kunci = $data->kunci:die("forbiden #5"); // tidak ada kunci = die


(isset($data->fungsi))?$fungsi = $data->fungsi:$fungsi = false; 