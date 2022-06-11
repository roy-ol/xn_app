<?php
require_once __DIR__ . '/../app/init_class.php';

class cUser extends cKoneksi{
  protected $userID, $id_level, $username, $email, $fullname, $flag_active;

  function __construct(){      
    parent::__construct();
    $this->userID = 0 ;
  } 

  function regDroid($updater = 0){
    if($this->flag_active == 0)   return false; // "diactivated";
    $newToken = md5(dechex(time() . $this->userID)); //md5 dari hex nilai epoch time digit belakang append userID
    $sql = "select token_key from idroid where user_id=" . $this->userID;
    $token = $this->ambil1Data($sql);
    if($token){
      $sql = "update idroid set token_key='".$newToken."',updater=".$updater ." where user_id=" . $this->userID;
      // $sql .= " and token_key='" . $token . "' ;";
    }else{
      $sql = "insert into idroid(user_id,token_key,updater) values( " . $this->userID;
      $sql .= ", '" .$newToken. "', ". $updater ." );" ;      
    } 
    $rAff =  $this->eksekusi($sql);
    ($rAff)?:$newToken = false;
    return $newToken;
  }

  function loadUserByID($userID){ //load user dr token key
    $sql = "SELECT * FROM users WHERE id= :userID ;";
    $param = ["userID" => $userID]; 
    $rowHasil = $this->ambil1Row($sql,$param,PDO::FETCH_OBJ);  
    if($rowHasil){
      $this->userID       = $rowHasil->id; //model array obj dari  PDO::FETCH_OBJ
      $this->id_level     = $rowHasil->id_level;
      $this->username     = $rowHasil->username;
      $this->email        = $rowHasil->email;
      $this->fullname     = $rowHasil->fullname;
      $this->flag_active  = $rowHasil->flag_active;
      return $this->id_level;
    }else{
     return false;
    }
  }

  function loadUser($userName,$pass){
    $sql = "SELECT id FROM users WHERE username=:username AND pwd=MD5(AES_ENCRYPT(:pass,'crypt9'));";
    $param = ["username" => $userName, "pass"=>$pass]; 
    $hasilID = $this->ambil1Data($sql,$param); 
    ($hasilID)?$rsp = $this->loadUserByID($hasilID): $rsp =  false;
    return $rsp ; 
  }
  
  function loadUserByKey($kunci){
    $sql = "SELECT user_id FROM idroid WHERE token_key= :kunci ;"; 
    $hasilID = $this->ambil1Data($sql,["kunci" => $kunci]); 
    ($hasilID)?$rsp = $this->loadUserByID($hasilID): $rsp =  false;
    // echo $kunci;
    return $rsp ; 
  }


  function isAktif(){
    if($this->flag_active == 0)   return false; // "diactivated";
    return true;
  }
  function userID(){
    if($this->flag_active == 0)   return false; // "diactivated";
    return $this->userID;
  }
  function fullname(){ 
    return $this->fullname;
  }

  function updatePassword($sPass){
    $sql = "UPDATE users SET pwd=MD5(AES_ENCRYPT(':pass','crypt9')) WHERE id= :userID";    
    $param = ["pass"=>$sPass,"userID" => $this->userID];
    return $this->eksekusi($sql,$param);
  }

  function cekKunci(){

  }

  

}