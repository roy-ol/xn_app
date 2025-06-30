<?php //user login untuk apilasi android / web
require_once __DIR__ . '/../app/init_class.php';

class cUser extends cKoneksi{
  protected $userID, $id_level, $username, $email, $fullname, $flag_active, $id_perusahaan;

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

  function loadUserByID($userID){ //load user dr id user bisa juga asal dr token key, hasilkan id_level
    $sql = "SELECT * FROM users WHERE id= :userID ;";
    $param = ["userID" => $userID]; 
    $rowHasil = $this->ambil1Row($sql,$param,PDO::FETCH_OBJ);  
    if($rowHasil){
      $this->userID         = $rowHasil->id; //model array obj dari  PDO::FETCH_OBJ
      $this->id_level       = $rowHasil->id_level;
      $this->id_perusahaan  = $rowHasil->id_perusahaan;
      $this->username       = $rowHasil->username;
      $this->email          = $rowHasil->email;
      $this->fullname       = $rowHasil->fullname;
      $this->flag_active    = $rowHasil->flag_active;
      return $this->id_level;
    }else{
     return false;
    }
  }

  // function loadUser($userName,$pass){ //pakai AES_ENCRYPT crypt9
  //   $sql = "SELECT id FROM users WHERE username=:username AND pwd=MD5(AES_ENCRYPT(:pass,'crypt9'));";
  //   $param = ["username" => $userName, "pass"=>$pass]; 
  //   $hasilID = $this->ambil1Data($sql,$param); 
  //   ($hasilID)?$rsp = $this->loadUserByID($hasilID): $rsp =  false;
  //   return $rsp ; 
  // }
  function loadUser($userName,$pass){ 
    return $this->loadUserHash($userName,$pass) ; 
  }

  
  /**
   * @param fungsi load user metode password hash bcrypt seperti laravel 8
   */
  function loadUserHash($userName,$pass){ 
    $hasilID=0;
    $sql = "SELECT id,pwd FROM users WHERE username=:username ;"; 
    $param = ["username" => $userName]; 
    $rowHasil = $this->ambil1Row($sql,$param,PDO::FETCH_OBJ) ;  //ambil id dan pwd
    if($rowHasil){
      $pwd= $rowHasil->pwd; 
      if (password_verify($pass, $pwd)) $hasilID = $rowHasil->id;  //verifikasi hasil hash pwd dg inputan 
    }
    $this->loadUserByID($hasilID);
    return $this->userID(); 
  }
  
  function loadUserByKey($kunci){
    $sql = "SELECT user_id FROM idroid WHERE token_key= :kunci ;"; 
    $hasilID = $this->ambil1Data($sql,["kunci" => $kunci]); 
    ($hasilID)?$rsp = $this->loadUserByID($hasilID): $rsp =  false;
    // echo $kunci;
    return $rsp ; 
  }

  function getNamaPerusahaan(){
    $sql = "SELECT nama FROM perusahaan WHERE id = :idperus ";
    $namaPerusahaan = $this->ambil1Data($sql , ["idperus" => $this->id_perusahaan]);
    return $namaPerusahaan;
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
  function id_level(){ 
    return $this->id_level;
  }

  function id_perusahaan(){ 
    return $this->id_perusahaan;
  }

  function updatePassword($sPass){  //password menggunakan cara aes n md5
    $hashedPassword = password_hash($sPass, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET pwd= MD5(AES_ENCRYPT(':pass','crypt9')) WHERE id= :userID";    
    $param = ["pass"=>$hashedPassword,"userID" => $this->userID];
    return $this->eksekusi($sql,$param);
  }
  // function updatePassword($sPass){ //metode AES_ENCRYPT
  //   $sql = "UPDATE users SET pwd=MD5(AES_ENCRYPT(':pass','crypt9')) WHERE id= :userID";    
  //   $param = ["pass"=>$sPass,"userID" => $this->userID];
  //   return $this->eksekusi($sql,$param);
  // }

  function cekKunci(){

  }

  //==================== berhubungan dengan akses node =====================
  
  /**
   * Cek apakah node dengan id_node termasuk node yang bisa diakses oleh user yang login
   * @param int $id_node id node yang akan di cek
   * @return int 0 jika tidak ada, id_node jika ada
   */
  function isMyNode($id_node){
    $sql = "SELECT n.id FROM node n WHERE n.id = :id_node AND EXISTS (
        SELECT 1 FROM chip c 
        JOIN kebun k ON k.id = c.id_kebun
        WHERE c.id = n.id_chip AND k.id_perusahaan = :id_perusahaan )";
    $param = ["id_node" => $id_node, "id_perusahaan" => $this->id_perusahaan];
    $hasilID = $this->ambil1Data($sql,$param);
    if($hasilID) return $hasilID;
    return 0;
  }
  

}