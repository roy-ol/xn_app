<?php
class cTemplate{
  public $sHeaderCap; 
  public $sNamaFile;
  public $val1;
  public $val2;
  public $val3;


  public function __construct($sHeaderCap){
    $this->sHeaderCap = $sHeaderCap;  
  }
  // public function __construct($sNamaFile){
  //   $this->sNamaFile = $sNamaFile;  
  // }

  public function setHeaderCap($sHeaderCap){
    $this->sHeaderCap = $sHeaderCap;
  }
  
  public function loadHeader(){
    $sHeaderCap = $this->sHeaderCap;
    $sHeaderCap = (empty($sHeaderCap)) ? "XmartNode" : $sHeaderCap; 
    include_once "../template/header.php";
  }

  public function loadFooter(){
    include_once "../template/footer.php";
  }

  // public function tampil(){
  //   $sNamaFile = $this->sNamaFile;
  //   $sHeaderCap = $this->sHeaderCap;
  //   $sHeaderCap = (empty($sHeaderCap)) ? "XmartNode" : $sHeaderCap; 
  //   include_once "../template/header.php";
  //   include_once "../page/$sNamaFile.php";      
  //   include_once "../template/footer.php";    
  // }



}
?>

