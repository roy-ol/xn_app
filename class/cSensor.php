<?php
// require_once __DIR__ . '/../app/init_class.php';

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

  
  function logging($raw0, $val1,$waktuNode = false, $id_loc = false ){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusSensor){ return false; } //keluar bila status false

    //ada r0 dan v1 kosong atau ada nilaii v1
    ($raw0 != null && $val1 == null )? $nilaiHasil = $this->value_map($raw0) : $nilaiHasil = $this->value_map($val1); 

    $param = ["nodeID"=>$this->nodeID];  
    $param += ["val1"=>$val1];  
    $param += ["raw0"=>$raw0];  
    $param["nilai"] = $nilaiHasil;

    if($waktuNode){  
      $dateTime = new DateTime($waktuNode); 
      $timestamp = $dateTime->format('U');       
      if($id_loc){
        $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai, waktu_node,id_loc)
          VALUES(:nodeID , :raw0, :val1, :nilai,  FROM_UNIXTIME(:waktu), :id_loc )" ; 
        $param['waktu'] = $timestamp;
        $param['id_loc'] = $id_loc;
      }else{
        $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai, waktu_node)
          VALUES(:nodeID , :raw0, :val1, :nilai,  FROM_UNIXTIME(:waktu) )" ; 
        $param['waktu'] = $timestamp;
      }
    }else{
      if($id_loc){
        $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai, id_loc)
        VALUES(:nodeID , :raw0, :val1, :nilai, :id_loc)" ;
        $param['id_loc'] = $id_loc;
      }else{
      $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai)
        VALUES(:nodeID , :raw0, :val1, :nilai)" ;
      }
    }

    $hasil = $this->eksekusi($q,$param);  
    return $hasil;
  }
  
  function logging0($raw0, $val1,$waktuNode = false){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusSensor){ return false; } //keluar bila status false

    //ada r0 dan v1 kosong atau ada nilaii v1
    ($raw0 != null && $val1 == null )? $nilaiHasil = $this->value_map($raw0) : $nilaiHasil = $this->value_map($val1); 

    $param = ["nodeID"=>$this->nodeID];  
    $param += ["val1"=>$val1];  
    $param += ["raw0"=>$raw0];  
    $param["nilai"] = $nilaiHasil;

    if($waktuNode){  
      $dateTime = new DateTime($waktuNode); 
      $timestamp = $dateTime->format('U');       
      $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai, waktu_node)
        VALUES(:nodeID , :raw0, :val1, :nilai,  FROM_UNIXTIME(:waktu) )" ; 
      $param['waktu'] = $timestamp;
    }else{
      $q = "INSERT INTO sensor_logger(id_node, raw0, val1, nilai)
        VALUES(:nodeID , :raw0, :val1, :nilai)" ;
    }

    $hasil = $this->eksekusi($q,$param);  
    return $hasil;
  }
  
  function value_map($rawVal1){  
    if($rawVal1 == null) return 0;
    $q="select * from value_map where id_node=" . $this->nodeID;
    $r=$this->ambil1Row($q);

    if(!$r) return $rawVal1;  //keluar langsung pakai rawval bila tidak ketemu map value

    $raw0=$r['raw_nol'];
    $raw1=$r['raw_satu'];
    $raw2=$r['raw_dua'];
    $val0=$r['val_nol'];
    $val1=$r['val_satu'];
    $val2=$r['val_dua'];
    $min=$r['min'];
    $max=$r['max'];

    if ($raw2 != null ) {  //2 range / asumsi 3 map bila raw2 bernilai 
      if($raw2 < $raw0 ){ // swicth raw0 dan raw 2 bila urutan beda
        $raw2=$r['raw_nol']; 
        $raw0=$r['raw_dua'];
        $val2=$r['val_nol']; 
        $val0=$r['val_dua'];
      }
    }elseif($raw1 != null) { // 2 nilai map bila raw1 bernilai
      if( $raw1 < $raw0  ){ // swicth raw0 dan raw 1 bila urutan beda
        $raw1=$r['raw_nol']; 
        $raw0=$r['raw_satu'];
        $val1=$r['val_nol']; 
        $val0=$r['val_satu'];
      }
    } 
      
    
    if ($raw1 != null && $raw2 != null ) { // berisi 3 mapping / 2 range
      if ( $rawVal1 < $raw0 || $raw2 < $rawVal1 ) { // diluar range 
        $rawAwal = $raw0;
        $valAwal = $val0;
        $rawAkhir = $raw2;
        $valAkhir = $val2;
      } elseif( $rawVal1 <= $raw1 ) { // di range 1 
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
    } else { // ada nilai raw2, ref 1 null == 1 range tapi maping r0 dan r2
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

  function value_map_lama($rawVal1){  
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