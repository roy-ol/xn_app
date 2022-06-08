<?php
require_once __DIR__ . '/../app/init_class.php';

class cUser extends cKoneksi{
  private $userID, $id_level, $username, $email, $fullname, $flag_active;

  function __construct(){      
    parent::__construct();
    $this->userID = 0 ;
  } 

  function regDroid($userName,$pass){
    $sql = "SELECT * FROM users WHERE username=:username AND pwd=MD5(AES_ENCRYPT(:pass,'crypt9'));";
    $param = ["username" => $userName, "pass"=>$pass]; 
    $row = $this->ambil1Row($sql,$param,PDO::FETCH_OBJ);  
    if($row){
      $this->userID       = $row->id; //model array obj dari  PDO::FETCH_OBJ
      $this->id_level     = $row->id_level;
      $this->username     = $row->username;
      $this->email        = $row->email;
      $this->fullname     = $row->fullname;
      $this->flag_active  = $row->flag_active;
      echo "ketemu " . $this->fullname;
    }else{
      echo "tidak ketemu";
    }

  }

  function updatePassword($sPass){
    $sql = "UPDATE users SET pwd=MD5(AES_ENCRYPT(':pass','crypt9')) WHERE id= :userID";    
    $param = ["pass"=>$pass,"userID" => $this->userID];
    return $this->eksekusi($sql,$param);
  }

  

}