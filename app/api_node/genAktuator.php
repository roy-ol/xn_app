<?php // fungsi - fungsi umum berhubungan dengan aktuator  

if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode(); //cNode sebelumnya sudah dikonstructor di app/class/cNode.php
  $data = json_decode($sDataDataPost); 
  $data = $data ; //contoh isi $data = "{"c":"XN000201"}
}  

function cek_json_jadwal(){
  global $cNode;

  //cek jadwal// 1. Ambil waktu terbaru dari node_status
  $queryLatestTime = "SELECT MAX(waktu) AS latest_time 
    FROM node_status WHERE id_node = $cNode->nodeID AND flag = 12 ";
  $latestTime = $cNode->ambil1Data($queryLatestTime);

  // 2. Cek apakah ada perubahan lebih baru
  $queryCheckChanges = "SELECT COUNT(id) AS count 
       FROM node_role_date WHERE id_node = $cNode->nodeID AND updated > '$latestTime'";   
  $hasNewChanges = $cNode->ambil1Data($queryCheckChanges);

  // 3. Jika ADA perubahan baru
  if (!$hasNewChanges > 0) return false;

  // 3a. Ambil data node_role_date dengan tanggal >= hari ini
  $queryJadwalLast = "SELECT * FROM node_role_date WHERE id_node = $cNode->nodeID 
      AND tanggal < CURDATE() ORDER BY tanggal DESC LIMIT 1";
  $queryJadwalNext = "SELECT * FROM node_role_date WHERE id_node = $cNode->nodeID 
      AND tanggal >= CURDATE()" ;
  $querJadwal = "($queryJadwalLast) UNION ($queryJadwalNext) ORDER BY tanggal ASC"; 
  $jadwal = $cNode->ambilDataRows($querJadwal, null, PDO::FETCH_ASSOC);
  if(empty($jadwal)) return false;
  // kirim dalam json
  $output = [];
  foreach ($jadwal as $row) {      
      $tglKey = date('m-d', strtotime($row['tanggal'])); // Buat key format MM-DD dari tanggal

      list($jam, $menit) = explode(':', $row['mulai']); // Ambil jam dan menit dari kolom 'mulai'
      $jamMenit = (int)($jam . $menit); // Ubah jadi integer seperti 930, 1145, dst

      if (!isset($output[$tglKey])) { // Tambahkan ke array output
          $output[$tglKey] = [];
      }

      $output[$tglKey][] = [$jamMenit, (int)$row['id_role']];
  }

  // Konversi ke JSON untuk dikirim ke ESP32
  $json = json_encode($output, JSON_UNESCAPED_SLASHES);
  return $json;
} 

function cek_json_role(){
  global $cNode;

  //cek jadwal// 1. Ambil waktu terbaru dari node_status
  $queryLatestTime = "SELECT MAX(waktu) AS latest_time 
    FROM node_status WHERE id_node = $cNode->nodeID AND flag = 13 ";
  $latestTime = $cNode->ambil1Data($queryLatestTime);

  // 2. Cek apakah ada perubahan lebih baru
  $queryCheckChanges = "SELECT COUNT(id) AS count 
       FROM node_role WHERE reff_node = $cNode->nodeID AND updated > '$latestTime'";   
  $hasNewChanges = $cNode->ambil1Data($queryCheckChanges);

  // 3. Jika ADA perubahan baru
  if (!$hasNewChanges > 0) return false;

  // 3a. Ambil data node_role yang dipakai di jadwal  
  $sql_tanggalBatas = "SELECT tanggal FROM node_role_date WHERE id_node = $cNode->nodeID 
      AND tanggal < CURDATE() ORDER BY tanggal DESC LIMIT 1";
  $tanggalBatas = $cNode->ambil1Data($sql_tanggalBatas);
  
  $sql_role = "SELECT id,exeval,exe_v1 FROM node_role WHERE id in(SELECT id_role FROM node_role_date 
    WHERE id_node = $cNode->nodeID AND tanggal >= '$tanggalBatas' )";

  $role = $cNode->ambilDataRows($sql_role, null, PDO::FETCH_ASSOC);
  if(empty($role)) return false;
  // kirim dalam json
  $output = [];
  foreach ($role as $row) {   
      $output[$row['id']] = [(int)$row['exeval'],(int)$row['exe_v1']];
  } 

  // Konversi ke JSON untuk dikirim ke ESP32
  $json = json_encode($output, JSON_UNESCAPED_SLASHES);
  return $json;
}

function flag0_aktuator(){
  global $cNode;
  $param["id_node"] = $cNode->nodeID;
  $param["f"] = 0 ; // flag umum/general untuk status none/null/kosong selesai atau proses gagal
  $hasilJadwal = cek_json_jadwal();  
  if(!empty($hasilJadwal)){
    $param["f"] = 12 ; //flag jadwal json
    $param["jadwal"] = $hasilJadwal;
  }else{
    $hasil_role = cek_json_role();
    if(!empty($hasil_role)){
      $param["f"] = 13 ; //flag role json
      $param["formula"] = $hasil_role;
    }
  }
  $cNode->dieJsonOKTime($param);
}

/**
 * @brief Cek apakah sudah ada log eksekutor untuk idNode dan noderole repeater 0 id tertentu
 *       pada hari ini (berdasarkan tanggal CURDATE()).
 * @param int $id_nrw id node role week
 * @param int $id_nrd id node role date
 * @return int id log eksekutor atau 0 jika tidak ada
 */
function cekLogEksekutor_r0($id_nrd,$id_nrw){
  global $cNode;
  $id_log = 0; 
  
  $param["id_node"] = $cNode->nodeID;  
  if($id_nrd > 0){ 
    $param["id_nr_date"]=$id_nrd;
    $sql="SELECT le.id FROM log_eksekutor le 
      JOIN node_role_date nrd ON nrd.id = le.id_nr_date
      JOIN node_role nr ON nr.id = nrd.id_role
      WHERE  DATE(le.created) = CURDATE() AND le.flag < 11 AND  nr.repeater = 0
      AND le.id_node = :id_node  AND le.id_nr_date= :id_nr_date"; 
  }elseif($id_nrw > 0){    
    $param["id_nr_week"]=$id_nrw;
    $sql="SELECT le.id FROM log_eksekutor le 
      JOIN node_role_week nrw ON nrw.id = le.id_nr_week
      JOIN node_role nr ON nr.id = nrw.id_role
      WHERE  DATE(le.created) = CURDATE() AND le.flag < 11  AND  nr.repeater = 0
      AND le.id_node = :id_node  AND le.id_nr_week= :id_nr_week";
  } 

  $id_log = $cNode->ambil1Data($sql,$param); 
  return $id_log;
}

/**
 * @brief memeriksa apakah request aktuator ini ada data id_log 
 *      untuk mengupdate tabel / flag status log ekeskutor
 * @param $data hasil decode json dari dataDataPost request aktuator
 */
function cekLogEksekutor($data){ 
global $cNode; 
  //jika kiriman berupa report status sukses aktuator di eksekusi dengan id_log
  if(isset($data->id_log)){
    $id_log_eksekutor = $data->id_log;  //ambil data berupa object
    // $id_log_eksekutor = $data["id_log"]; //ambil data berupa array
    $iRowAffected = $cNode->flag_eksekutor($id_log_eksekutor);
    $cNode->dieJsonOK(["row"=>$iRowAffected],true); //true = cek apakah ada update binfirupdate untuk dieksekusi
    // $cNode->dieJsonOK(["f"=>0],true);
  }
}

/**
 * @brief memeriksa apakah ada permintaan eksekusi aktuator testing 
 * @brief node_xt ============= execution test============
 * @param $id_node
 */ 
function cekReqExecutionTest($id_node){
  global $cNode;

  $param = ["id_node"=>$id_node];
    
  $arrData = false;
  $sql = "SELECT id,exeval,relay,exe_v1,exe_v2 FROM node_xt WHERE flag=0 and id_node = :id_node ;";
  $arrData = $cNode->ambil1Row($sql,$param);
  if($arrData){
    $id  = $arrData['id'];    
    $relay  = $arrData['relay'];    
    $exeval  = $arrData['exeval']; 
    $exe_v1  = $arrData['exe_v1']; 
    $exe_v2  = $arrData['exe_v2']; 
    $cNode->eksekusi("update node_xt set flag = 9 where id=$id"); //update tabel flag menjadi 9(terambil / dalam proses)
    $idInsert = $cNode->log_eksekutor(null,null,$relay,$exeval,$exe_v1,$exe_v2);      
    $respons['id_log']=intval($idInsert); // id log eksekutor yang baru dibuat di tabel log
    $respons['f']=20; //flag ada respons untuk aktuator action 
    // $respons['sleep']=$sleeptime ; //isian bila ada setting sleep berubah
    $respons['exeval']=intval($exeval) ; // bisa jadi nilai menit / nantinya ml liter setelah kalibrasi
    $respons["exe_v1"]=intval($exe_v1); // 23-12-2023 nilai sebagai target EC larutan (ppm = * 500)
    $respons["exe_v2"]=intval($exe_v2); // 13-06-2024 nilai tambahan parameter
    $respons["relay"]=intval($relay) ; //kode / relay
    
    $cNode->dieJsonOkTime($respons); 
  }
}

?>