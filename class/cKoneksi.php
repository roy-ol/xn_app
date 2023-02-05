<?php
// require_once __DIR__ . '/../app/init_class.php';

class cKoneksi{
  private $pdo;

  function __construct(){ 
    require_once __DIR__ . "/../app/fsambungan.php";
    $this->pdo = new PDO("mysql:host=".HOST.";dbname=".DB, USER, PASS);   
	} 

  // destructor  : method will be called as soon as there are 
  // no other references to a particular object, or in any order during the shutdown sequence.
  function __destruct(){ //otomatis tutup pdo : 
    $this->pdo = null;   //close connection di akhir 
  }
 

  function eksekusi($sQuery,$parameters=null){ //mengembalikan row affected
    $r = $this->pdo->prepare($sQuery);    // parameters array bila diperlukan 
    ($parameters)?$r->execute($parameters):$r->execute();
    return $r->rowCount(); 
  }

  function ambilData($sQuery,$parameters=null,$fetch_method = PDO::FETCH_ASSOC){  // bila ada parameters array untuk execute
    $hasil = []; //ambil semua data / multirow array assoc
    $r = $this->pdo->prepare($sQuery);  
    ($parameters)?$r->execute($parameters):$r->execute();
    while($row = $r->fetch($fetch_method)){
      $hasil = $row; 
    }
    return $hasil;
  }
   
  function ambil1Row($sQuery,$parameters=null,$fetch_method = PDO::FETCH_ASSOC){  // parameters array bila diperlukan
    $r = $this->pdo->prepare($sQuery);  // hasilnya 1Row data langsung ambil  
    ($parameters)?$r->execute($parameters):$r->execute();
    return $r->fetch($fetch_method) ; //diambil data row pertama meskipun banyak data row terambil 
  }
   
  function ambil1Data($sQuery,$parameters=null){  // query satu data param array bila diperlukan 
    $hasil = 0; // hasilnya 1Row data langsung ambil 
    $r = $this->pdo->prepare($sQuery);  
    ($parameters)?$r->execute($parameters):$r->execute();
    $hasil = $r->fetch(PDO::FETCH_NUM) ; //diambil data row pertama meskipun banyak data row terambil 
    return ($hasil)?$hasil[0]:false;
  }

  function getPDO(){ //menggunakan koneksi pdo yang sudah jadi
    return $this->pdo;
  }


  function debug_log($s_pesan){  
    $fdlog = fopen(__DIR__."/debuglog.txt", "a"); 
    $pesan_debug ="
    " . date('d-m-Y H:i:s') ." =:=> " . $s_pesan ;
    fwrite($fdlog,$pesan_debug);
    fclose($fdlog);
    return $pesan_debug;
  }
 
function dieJsonGagal($sKeterangan = "0"){
  $respons = ["status" => "gagal"];  
  $respons["time"] = date('Y-m-d H:i:s');
  $respons["keterangan"] = $sKeterangan;
  $sJsonRespons = json_encode($respons);
  die($sJsonRespons);
}

function dieJsonOK($param=[]){  
  $respons = ["status" => "OK"];  
  $respons["time"] = date('Y-m-d H:i:s'); 
  $arResp = array_merge($respons,$param); 
  $sJsonRespons = json_encode($arResp);
  // echo $sJsonRespons;
  // die();
  die($sJsonRespons);
}

//fungsi curl POST url ke luar server
function url_post_json($url,$json_data, $status = null, $wait = 3){
  $time = microtime(true);
  $expire = $time + $wait;

  // we fork the process so we don't have to wait for a timeout
  $pid = pcntl_fork();
  if ($pid == -1) {
      die('could not fork');
  } else if ($pid) {
      // we are the parent      
      $ch = curl_init();
      curl_setopt( $ch, CURLOPT_URL, $url); 
      curl_setopt( $ch, CURLOPT_POST, TRUE);
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE ); 
      $head = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      
      if(!$head) return FALSE; 
      if($status === null){
          return ($httpCode < 400)? TRUE : FALSE ;          
      }elseif($status == $httpCode){
          return TRUE;
      }           
      return FALSE;
      pcntl_wait($status); //Protect against Zombie children


  } else {
      // we are the child
      while(microtime(true) < $expire){
        sleep(0.5);
      }
      return FALSE;
  }
}

//fungsi curl get url ke luar server
function http_response($url, $status = null, $wait = 3){
  $time = microtime(true);
  $expire = $time + $wait;

  // we fork the process so we don't have to wait for a timeout
  $pid = pcntl_fork();
  if ($pid == -1) {
      die('could not fork');
  } else if ($pid) {
      // we are the parent
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, TRUE);
      curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $head = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      
      if(!$head) return FALSE; 
      if($status === null){
          return ($httpCode < 400)? TRUE : FALSE ;          
      }elseif($status == $httpCode){
          return TRUE;
      }           
      return FALSE;
      pcntl_wait($status); //Protect against Zombie children
  } else {
      // we are the child
      while(microtime(true) < $expire){
        sleep(0.5);
      }
      return FALSE;
  }
}

}