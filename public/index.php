<?php //index di public root untuk aplikasi android
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
(json_last_error() == JSON_ERROR_NONE)?:die('forbidden #1'); //cek format json apakah valid
$myUser = new cUser();
$userID = false;

if(isset($data->regkey)){ // ada permintaan registrasi user dari android
  $regkey = $data->regkey;
  die("maaf permintaan registrasi user belum bisa ".$regkey);  //paused
}

(isset($data->fungsi))?$fungsi = $data->fungsi:die("forbidden #5"); 
if ($fungsi == "login") {if(login()) $myUser->dieJsonOK();} 
if ($fungsi == "registerDroid") registerDroid(); 

//== cek kunci .. apakah ada token_key valid atau tidak
(isset($data->kunci))?$kunci = $data->kunci:$myUser->dieJsonGagal("forbidden #6'"); // tidak ada kunci = die 
($myUser->loadUserByKey($data->kunci))?:$myUser->dieJsonGagal("forbidden #7"); 


switch ($fungsi) {
  case 'getNama':
    $respons["fullname"] = $myUser->fullname();
    $myUser->dieJsonOK($respons); // class cUser extend ke cKoneksi menggunakan dieJsonOK dengan variable $respon sebagai merger tambahan param
    break;  
  case 'getData':
    $respons["data"] = "data data";
    $myUser->dieJsonOK($respons);
    break;
  default:
    # code...
    break;
}


//================================ fungsi fungsi ===================================
//==================================================================================

function login(){
  global $myUser,$data;
  $userID = false;
  if (isset($data->user) && isset($data->pass)) {
    ($myUser->loadUser($data->user,$data->pass))?:$myUser->dieJsonGagal("user gagal");
    ($userID = $myUser->isAktif())?:die("terblokir");  
    return $userID;
  }
  $myUser->dieJsonGagal("no userpass");
}

function registerDroid(){
  global $myUser;
  $user_ID = login();  
  $req = $myUser->regDroid($user_ID);

  if($req){ 
    $respons["status"] = "sukses";
    $respons["token"] = $req;
    $respons["fullname"] = $myUser->fullname();
    $myUser->dieJsonOK($respons);
  }else{
    $myUser->dieJsonGagal("register android ");
  } 

}