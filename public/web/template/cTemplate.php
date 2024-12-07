<?php
class cTemplate{
  public $sTitle="XmartNode"; 
  public $sNamaFile;
  public $val1;
  public $val2;
  public $val3;
  public  $sAddOnNavBarLeft="";  
  public  $sAddOnNavBarRight=""; 

  public function __construct($sTitle = "XmartNode"){
    $this->sTitle = $sTitle;  
  }
  // public function __construct($sNamaFile){
  //   $this->sNamaFile = $sNamaFile;  
  // }

  public function setTitle($sTitle){
    $this->sTitle = $sTitle;
  }

  public function setAddOnNavBarLeft($sAdOnNavBar){
    $this->sAddOnNavBarLeft = $sAdOnNavBar;
  }

  //set AddOnNavBarRight
  public function setAddOnNavBarRight($sAdOnNavBar){
    $this->sAddOnNavBarRight = $sAdOnNavBar;
  }

  public function loadHeader(){
    $sTitle = $this->sTitle;
    $sAddOnNavBarLeft = $this->sAddOnNavBarLeft;
    $sAddOnNavBarRight = $this->sAddOnNavBarRight;  
    include_once "../template/header.php";
  }

  public function loadFooter($start_loading_halaman_pemanggil){
    $start_loading_halaman=$start_loading_halaman_pemanggil;
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