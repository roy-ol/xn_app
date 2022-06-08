<?php
class cKoneksi{
  private $pdo;

  function __construct(){ 
    require_once __DIR__ . "/../App/fsambungan.php";
    $this->pdo = new PDO("mysql:host=".HOST.";dbname=".DB, USER, PASS);   
	} 

  // destructor  : method will be called as soon as there are 
  // no other references to a particular object, or in any order during the shutdown sequence.
  function __destruct(){ //otomatis tutup pdo : 
    $this->pdo = null;   //close connection di akhir 
  }
 

  function eksekusi($sQuery,$parameters=false){ //mengembalikan row affected
    $r = $this->pdo->prepare($sQuery);    // parameters array bila diperlukan
    ($parameters)?$r->execute($parameters):$r->execute();
    return $r->rowCount(); 
  }

  function ambilData($sQuery,$parameters=false,$fetch_method = PDO::FETCH_ASSOC){  // bila ada parameters array untuk execute
    $hasil = []; //ambil semua data / multirow array assoc
    $r = $this->pdo->prepare($sQuery);  
    ($parameters)?$r->execute($parameters):$r->execute();
    while($row = $r->fetch($fetch_method)){
      $hasil = $row; 
    }
    return $hasil;
  }
   
  function ambil1Row($sQuery,$parameters=false,$fetch_method = PDO::FETCH_ASSOC){  // parameters array bila diperlukan
    $r = $this->pdo->prepare($sQuery);  // hasilnya 1Row data langsung ambil  
    ($parameters)?$r->execute($parameters):$r->execute();
    return $r->fetch($fetch_method) ; //diambil data row pertama meskipun banyak data row terambil 
  }
   
  function ambil1Data($sQuery,$parameters=false){  // query satu data param array bila diperlukan 
    $hasil = 0; // hasilnya 1Row data langsung ambil 
    $r = $this->pdo->prepare($sQuery);  
    ($parameters)?$r->execute($parameters):$r->execute();
    $hasil = $r->fetch(PDO::FETCH_NUM) ; //diambil data row pertama meskipun banyak data row terambil 
    return $hasil[0];
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



}