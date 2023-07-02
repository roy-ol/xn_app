<?php
// require_once __DIR__ . '/../app/init_class.php';

class cNode extends cKoneksi{
  public  $nodeID,$flag,$nama,$keterangan,$chipID,
          $subNode,$chip,$kebunID,$tipeID,
          $kelompok,  //id kelompok tipe chip 1=sensor, 2=aktuator, 3=mix, 4=server lokal / timer server
          $statusNode = false ;

  function __construct(){      // dipanggil awal init klass
    parent::__construct();    //panggil __construct() kelas induk (cKoneksi)
  } 

  /**
   * $arResp = array_merge($respons,$param); 
   */
  function dieJsonGagal($param=[]){  
    $respons = ["f" => 0];    // 0 =  flag umum/general untuk status none/null/kosong selesai atau proses gagal
    $arResp = array_merge($respons,$param); 
    $sJsonRespons = json_encode($arResp); 
    die($sJsonRespons);
  }

  function dieJsonNone(){ // 0 =  flag umum/general untuk status none/null/kosong selesai atau proses gagal
    $this->dieJsonOK(["f"=>0]);
  }

  function dieJsonNoneTime(){ // XNtime + 0 =  flag umum/general untuk status none/null/kosong selesai atau proses gagal
    $this->dieJsonOK(["f"=>0],["t"=>date('Y-m-d H:i:s')]);
  }

  /**
   * @brief XNtime + $arResp = array_merge($respons,$param); 
   */
  function dieJsonOkTime($param=[]){ // XNtime + $arResp = array_merge($respons,$param); 
    $respons = ["f" => 9];    // 9 =  flag umum/general untuk status atau proses berhasil atau sukses
    $respons["t"] = date('Y-m-d H:i:s');  
    $arResp = array_merge($respons,$param); 
    $this->dieJsonOK($arResp); 
  }

  /**
   * bikin respon json sukses + param tambahan array merge sebelum json_encode
   * @param array $param ex. $respons["t"] = date('Y-m-d H:i:s'); 
   */
  function dieJsonOK($param=[],$perluCekUpdate = false){  
    $respons = ["f" => 9];    // 9 =  flag umum/general untuk status atau proses berhasil atau sukses 
    $arResp = array_merge($respons,$param); 
    if($perluCekUpdate){
      if($this->cekUpdate()) $arResp = ["f" => 7] ; //ada update abaikan semua jawaban lain
    }  
    $sJsonRespons = json_encode($arResp); 
    die($sJsonRespons);
  }

  /**
   * @brief rutin mengecek apakah ada update firmware untuk langsung dieOkTime dengan status 7 jika update
   */
  function cekUpdate(){
    $sSQL="SELECT * FROM binfirupd WHERE id_chip= $this->chipID AND flag=7 ";
    $rHasil=$this->ambil1Row($sSQL);
    // return ($rHasil >= 1)? $this->dieJsonOkTime( ["f" => 7] ) :  $rHasil;
    return ($rHasil >= 1)? true : false ;
  }


  
  private function konstrukNode($hasil){
    $this->nodeID =$hasil['id'];
    $this->flag =$hasil['flag'];
    $this->nama =$hasil['nama'];
    $this->keterangan =$hasil['keterangan'];
    $this->chipID =$hasil['id_chip'];
    $this->subNode =$hasil['sub_node'];
    $this->chip =$hasil['chip'];
    $this->kebunID =$hasil['id_kebun'];
    $this->tipeID =$hasil['id_tipe'];
    $this->kelompok =$hasil['kelompok'];  //id kelompok tipe chip 1=sensor, 2=aktuator, 3=mix, 4=server lokal / timer server    
    ($this->flag == 0)?: $this->statusNode=true;      // flag = 0 node status deactive
  }
 
  /**
   * ambil/load konstruk data node dari nodeID
   * @param nodeID dari node 
   */
  function nodeByID($nodeID){ 
    $this->statusNode=false; // awal check dari chip status default $this->getstatusNode
    $sql = "SELECT n.id, n.flag,n.nama,n.keterangan, n.id_chip,n.sub_node,
      c.chip, c.id_kebun,c.id_tipe, t.kelompok
      FROM node n, chip c, tipe t
      WHERE c.id=n.id_chip AND c.id_tipe=t.id
      AND n.id= :nodeID ";
    $param = ["chnodeID"=>$nodeID];
    $hasil = $this->ambil1Row($sql,$param);
    if($hasil){
      $this->konstrukNode($hasil);
      $this->statusNode=true;
    }
    return $this->statusNode;
  }  

  /**
   * @brief awal 
   * @roy-ol ambil/load data node dari kode chip dan subNode (noSensor)
   * @param $chip = String kode chip XN*****
   * @param $subNode = nomer sub node/sensor/relay
   */
  function nodeByChip($chip,$subNode=1){
    $this->statusNode=false; // awal check dari chip status default $this->getstatusNode
    $sql = "SELECT n.id, n.flag,n.nama,n.keterangan, n.id_chip,n.sub_node,
      c.chip, c.id_kebun, c.id_tipe, t.kelompok
      FROM node n, chip c, tipe t
      WHERE n.id_chip=c.id AND c.id_tipe=t.id
      AND n.sub_node= :subNode AND c.chip= :chip "; 
    $param = ["chip"=>$chip,"subNode" =>$subNode];
    $hasil = $this->ambil1Row($sql,$param);
    if($hasil){
      $this->konstrukNode($hasil);
      $this->statusNode=true;
    }
    return $this->statusNode;
  }
  
  function logging($raw0, $val1,$waktuNode = false, $id_loc = false ){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusNode){ return false; } //keluar bila status false

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
    if(!$this->statusNode){ return false; } //keluar bila status false

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

    if($min != null && $max != null){ //ada batas minmax nilai keduanya / tidak null
      if( $min < $max ){ 
        if($val_hasil_map < $min) $val_hasil_map = $min;
        if($val_hasil_map > $max) $val_hasil_map = $max;        
      }elseif ( $min > $max ) { 
        if($val_hasil_map > $min) $val_hasil_map = $min;
        if($val_hasil_map < $max) $val_hasil_map = $max;  
      } //nilai min = max atau null batas akan diabaikan
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


  // public function getFlag(){ return $this->flag; }  // dibutuhkan untuk ambil nilai bila variable bukan public
  // public function getID(){ return $this->nodeID; }  
  // public function getTipe(){ return $this->tipeID; }  
  // public function getKeterangan(){ return $this->keterangan; } 
  // public function getstatusNode(){ return $this->statusNode; }









}