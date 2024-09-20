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
    // $this->dieJsonOK(["f"=>0]);
    parent::dieJson(["f"=>0]);  //hasil sama
  }

  function dieJsonNoneCheckUpdate(){ //flag 0 sekaligus memeriksa update
    $this->dieJsonOK(["f"=>0],true);
  }

  function dieJsonNoneTime(){ // XNtime + 0 =  flag umum/general untuk status none/null/kosong selesai atau proses gagal
    $respons["f"] = 0;  
    $respons["t"] = date('Y-m-d H:i:s');   
    $this->dieJsonOK($respons); 
  }

  /**
   * @brief XNtime + $arResp = array_merge($respons,$param); 
   */
  function dieJsonOkTime($param=[]){ // XNtime + $arResp = array_merge($respons,$param);  
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
      $iCek = $this->cekUpdate(); //flag sesuai hasil bila ada update
      if($iCek > 0) $arResp["f"]=  $iCek; ; 
    }   
    parent::dieJson($arResp); 
  }

  /**
   * @brief rutin mengecek apakah ada update firmware untuk langsung dieOkTime dengan status 7 jika update
   */
  function cekUpdate(){ //cek keberadaan flag f = 7(binfir) / 10(jsonSetting)
    $sSQL="SELECT flag FROM chip WHERE id= $this->chipID AND flag > 0 "; 
    $nilaiHasil=$this->ambil1Data($sSQL);
    if($nilaiHasil) return $nilaiHasil;
    return 0;
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
    $param = ["nodeID"=>$nodeID];
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
  
  //untuk sensor_logger
  function logging($raw0, $val1,$waktuNode = false, $id_loc = false ){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusNode){ return false; } //keluar bila status false

    //pause == angka v1 = 0 yang masuk nilai hasil kenapa r0 ..?
    //ada r0 dan v1 kosong atau ada nilaii v1 
    // if ($raw0 != null && $val1 == null ){  //disini nilai angka 0 di samakan nilainya dg null atau falsy
    if ($val1 === null && $raw0 !== null ){   //disini uji identik bahkan tipe datanya benar benar null
      $nilaiHasil = $this->value_map($raw0); //ada data r0 tetapi v1 == null
    } elseif($val1 !== null ) {
      $nilaiHasil = $this->value_map($val1); 
    } else{
      return 0; //kedua data r0 maupun v1 kosong
    }
    $param = ["nodeID"=>$this->nodeID];  
    $param += ["val1"=>$val1];  
    $param += ["raw0"=>$raw0];  
    $param["nilai"] = $nilaiHasil;

    if($waktuNode){  
      $dateTime = new DateTime($waktuNode); 
      $timestamp = $dateTime->format('U');       
      if($id_loc){ //bila ada id_loc (dikirim oleh local server)
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
      if($id_loc){ //bila ada id_loc (dikirim oleh local server)
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



  function logging1($raw0, $val1,$waktuNode = false, $id_loc = false ){  //false atau format ex. '2022-05-26 02:28:34'
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
      if($id_loc){ //bila ada id_loc (dikirim oleh local server)
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
      if($id_loc){ //bila ada id_loc (dikirim oleh local server)
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
   
  /**
   * logging status config json dari schip
   * @param string $sJsonConfig isi json config dari schip
   * @return int jumlah record terpengaruh
   */
  function log_json_config($sJsonConfig){   
    if(!$this->statusNode){ return false; } //keluar bila status false
    $q = "INSERT INTO memo(memo) VALUES(:sJsonConfig)";
    $param = ["sJsonConfig"=>$sJsonConfig];

    $iRecAff = $this->eksekusi($q,$param);
    $id_memo = $this->ambil1Data("SELECT LAST_INSERT_ID();"); 

    $q ="INSERT INTO chip_log(id_chip,keterangan,id_memo) VALUES(:chipID,:keterangan,:id_memo);  ";
    $param = ["id_memo"=>$id_memo];   
    $param["keterangan"] = "jsonConfig";      
    $param["chipID"] = $this->chipID;
    $iRecAff = $this->eksekusi($q,$param);  
    return $iRecAff;
  }

  /**
   * logging bila ada eksekusi aktuator
   */
  function log_eksekutor($id_nr_date, $id_nr_week, $relay, $exeval, $exe_v1, $exe_v2){  //false atau format ex. '2022-05-26 02:28:34'
    if(!$this->statusNode){ return false; } //keluar bila status false
    
    $q =" INSERT INTO `log_eksekutor`
    (`id_nr_date`, `id_nr_week`, `id_node`, `relay`, `exeval`, `exe_v1`, `exe_v2`) VALUES 
    (:id_nr_date, :id_nr_week, :id_node, :relay, :exeval, :exe_v1, :exe_v2) ";
  
  // $param = ["id_nr_week"=>''];   
  // $param += ["id_nr_date"=>''];   
    $param = ["id_nr_week"=> $id_nr_week];   
    $param += ["id_nr_date"=> $id_nr_date];    
    // $param["id_nr_week"] = $id_nr_week;   
    // $param["id_nr_date"] = $id_nr_date;   
    // if($id_nr_week !== null ) $param["id_nr_week"] = $id_nr_week;   
    // if($id_nr_date !== null ) $param["id_nr_date"] = $id_nr_date;   
    $param += ["id_node"=>$this->nodeID];  
    $param += ["relay"=>$relay];  
    $param += ["exeval"=>$exeval];  
    $param += ["exe_v1"=>$exe_v1];  
    $param += ["exe_v2"=>$exe_v2];  
    
    // var_dump($param);
    $this->eksekusi($q,$param);   
    return $this->ambil_pdo_lastInsertID();
  }

  /**
   * update flag menjadi 9 atau sudah terlaksana dari tabel eksekutor berdasarkan ID
   */
  function flag_eksekutor($id_log_eksekutor){
    $q = "UPDATE log_eksekutor SET flag = 9 WHERE id_node = :nodeID and  id = :id ";
    $param = ["id"=>$id_log_eksekutor];  
    $param += [":nodeID"=>$this->nodeID];  
    return $this->eksekusi($q,$param);   //mengembalikan nilai yang di eksekusi
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
  
  /**
   * mapping Value
   */
  function value_map($rawVal1,&$adaMinMax = false){  
    if($rawVal1 == null) return 0;
    $q="select * from value_map where id_node=" . $this->nodeID;
    $r=$this->ambil1Row($q);

    if(!$r){
      $adaMinMax = false;
      return $rawVal1;  //keluar langsung pakai rawval bila tidak ketemu map value
    }
    
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
      $adaMinMax = true;  
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

  function value_map_lama0($rawVal1){  
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