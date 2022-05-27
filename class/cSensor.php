<?php
require_once __DIR__ . '/../app/init_class.php';
class cSensor extends cKoneksi{
  public  $nodeID,
          $chipID,
          $keterangan,
          $tipeID,
          $statusSensor = false,
          $flag;

  function __construct(){      
    parent::__construct();
  } 

  function logging($rawVal1,$waktuNode = false){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusSensor){ return false; } //keluar bila status false
    $nilaiHasil = $this->value_map($rawVal1);

    $param = ["nodeID"=>$this->nodeID]; 
    $param += ["rawv1"=>$rawVal1];  
    $param["nilai"] = $nilaiHasil;

    if($waktuNode){  
      $dateTime = new DateTime($waktuNode); 
      $timestamp = $dateTime->format('U');
       
      $q = "INSERT INTO sensor_logger(id_node, rawv1, nilai, waktu_node)
        VALUES(:nodeID , :rawv1, :nilai,  FROM_UNIXTIME(:waktu) )" ; 
      $param['waktu'] = $timestamp;
    }else{
      $q = "INSERT INTO sensor_logger(id_node, rawv1, nilai)
        VALUES(:nodeID , :rawv1, :nilai)" ;
    }

    $hasil = $this->eksekusi($q,$param); 
    return $hasil;
  }

  function value_map($rawVal1){  
    $q="select * from value_map where id_node=" . $this->nodeID;
    $r=$this->ambil1Row($q);

    if(!$r){ return $rawVal1; }
    else{
      $raw0=$r['raw_nol'];
      $raw1=$r['raw_satu'];
      $raw2=$r['raw_dua'];
      $val0=$r['val_nol'];
      $val1=$r['val_satu'];
      $val2=$r['val_dua'];
      $min=$r['min'];
      $max=$r['max'];
      if ($raw2 != null ) { 
        if($raw2 < $raw0 ){ // swicth raw0 dan raw 2 bila urutan beda
          $raw2=$r['raw_nol']; 
          $raw0=$r['raw_dua'];
          $val2=$r['val_nol']; 
          $val0=$r['val_dua'];
        }
      }elseif($raw1 != null) { 
        if( $raw1 < $raw0  ){ // swicth raw0 dan raw 1 bila urutan beda
          $raw1=$r['raw_nol']; 
          $raw0=$r['raw_satu'];
          $val1=$r['val_nol']; 
          $val0=$r['val_satu'];
        }
      } 
    }
    
    if ($raw1 != null && $raw2 != null ) {
      if ( $rawVal1 < $raw0 || $raw2 < $rawVal1 ) { // diluar range 
        $rawAwal = $raw0;
        $valAwal = $val0;
        $rawAkhir = $raw2;
        $valAkhir = $val2;
      } elseif( $rawVal1 <= $raw1 ) { // di range 1
        echo " range 1";
        $rawAwal = $raw0;
        $valAwal = $val0;
        $rawAkhir = $raw1;
        $valAkhir = $val1; 
      } else { // lain / range 2 
        $rawAwal = $raw1;
        $valAwal = $val1;
        $rawAkhir = $raw2;
        $valAkhir = $val2;
      } 
    } elseif($raw1 != null) { // gunakan range 1 ref 2 null 
      $rawAwal = $raw0;
      $valAwal = $val0;
      $rawAkhir = $raw1;
      $valAkhir = $val1; 
    } else { // ada nilai raw2, ref 1 null 
      $rawAwal = $raw0;
      $valAwal = $val0;
      $rawAkhir = $raw2;
      $valAkhir = $val2;
    }   
 
    $val_hasil_map = $valAwal + ((($valAkhir - $valAwal) / ($rawAkhir - $rawAwal)) * ($rawVal1 - $rawAwal));

    if($min != null && $max != null){ //ada nilai keduanya / tidak null
      if( $min < $max ){ 
        if($val_hasil_map < $min) $val_hasil_map = $min;
        if($val_hasil_map > $max) $val_hasil_map = $max;        
      }elseif ( $min > $max ) { 
        if($val_hasil_map > $min) $val_hasil_map = $min;
        if($val_hasil_map < $max) $val_hasil_map = $max;  
      } //nilai min = max batas akan diabaikan
    }

    return $val_hasil_map;
  }

  function nodeByChip($chipID,$noSensor){
    $this->statusSensor=false; // awal check dari chip status default $this->getStatusSensor
    $sql = "select * from node where chip= :chipID and no_sensor = :noSensor";
    $param = ["chipID"=>$chipID,"noSensor" =>$noSensor];
    $hasil = $this->ambil1Row($sql,$param);
    if($hasil){
      $this->nodeID = $hasil['id'];
      $this->chipID = $hasil['chip'];
      $this->tipeID = $hasil['id_tipe'];
      $this->flag = $hasil['flag'];
      $this->keterangan = $hasil['keterangan'];
      ($this->flag == 0)?: $this->statusSensor=true; 
    }
    return $this->statusSensor;
  }

  function nodeByID($nodeID){
    $this->statusSensor=false; 
    $sql = "select * from node where id=:nodeID";
    $param = ["nodeID"=>$nodeID];
    $hasil = $this->ambil1Row($sql,$param);
    if($hasil){
      $this->nodeID = $hasil['id'];
      $this->chipID = $hasil['chip'];
      $this->tipeID = $hasil['id_tipe'];
      $this->flag = $hasil['flag'];
      $this->keterangan = $hasil['keterangan'];
      ($this->flag == 0)?: $this->statusSensor=true; 
    }
    return $this->statusSensor;
  } 

  public function getFlag(){ return $this->flag; }  
  public function getID(){ return $this->nodeID; }  
  public function getTipe(){ return $this->tipeID; }  
  public function getKeterangan(){ return $this->keterangan; } 
  public function getStatusSensor(){ return $this->statusSensor; }









}