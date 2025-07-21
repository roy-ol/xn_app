<?php /**
 * kebutuhan umum seperti api / pelayan data 
 */

$sDefHTML = "<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don't have permission to access this resource.</p><hr><address>Apache/4.4.5 (UNIX) OpenSSL/2.4.3h PHP/8.9.1 Server at Port 80 </address>"; $sDefHTMLtutup = "</body></html>";$sDefHTMLisi = "";


if($_SERVER['REQUEST_METHOD']=='POST'){ 
	//Getting values  
	$sDataDataPost=file_get_contents('php://input');	
} elseif($_SERVER['REQUEST_METHOD']=='GET'){      //GET atau ada post tanpa kode get dianggap method GET
  $sDefHTMLisi = "forbidden"; 
  $sDefHTML .= $sDefHTMLisi . $sDefHTMLtutup;
  die($sDefHTML); 
}else{
  $sDefHTMLisi = "forbidden : NOr GET Nor POST"; 
  $sDefHTML .= $sDefHTMLisi . $sDefHTMLtutup;
  die($sDefHTML); 
}

$data = json_decode($sDataDataPost);
if(json_last_error() !== JSON_ERROR_NONE){ //data POST menjadi array object
  // $data =$_POST; 
  $data =json_encode($_POST); 
  $data =json_decode($data); 
}

// dari sini ada data post untuk diolah:
// ==========================================
require_once __DIR__ . '/koneksi_umum.php';

if(1 == 0){ //dumy biar dikenali IDE
  $cUmum = new cUmum();
  $cUser = new cUser();
}

// $data = json_decode($sDataDataPost); 
$waktu =  gmdate("Y-m-d H:i:s",time() + (3600 * 7)); //gmt+7 jam 

if(isset($_GET['kode'])){  // didapatkan dari setingan htaccess bareng di folder ini RewriteRule ^(.*)$ index.php?kode=$1 [L]
  $kodeApiFile = $_GET['kode'];   
  if(!$kodeApiFile) die("noCode"); 

  switch ($kodeApiFile) { //========== pelayanan API umum Web ===========
    case 'ketPola':   //fungsi ambil data keterangan pola
      $idPola = $data->idPola; 
      getInfoPola($idPola);
      break; 
    case 'ketRole':   //fungsi ambil data keterangan pola
      $idRole = $data->idRole; 
      getInfoRole($idRole);
      break; 
    case 'ketNode':   //fungsi ambil data keterangan pola
      $idKey = $data->idKey; 
      getInfoNode($idKey);
      break; 
    case 'addNodeRole1':    //menambahkan NodeRole Standart field
      addNodeRole1($data);
      break; 
    case 'updateNodeRole1':    //update NodeRole Standart field
      updateNodeRole1($data);
      break; 
    case 'addNodeRoleWeek':    //menambahkan daftar NodeRole WeeK
      addNodeRoleWeek($data);
      break; 
    case 'UpdateNRW':    //menambahkan daftar NodeRole WeeK
      UpdateNRW($data);
      break; 
    case 'addChip':    //menambahkan   chip	
      addChip($data);
      break;
    case 'updateChip':    //update chip
      updateChip($data);
      break;
    case 'addNode':    //menambahkan   Node 
      addNode($data);
      break; 
    case 'updateNode':    //menambahkan   Node 
      updateNode($data);
      break; 
    case 'addPerusahaan':    //menambahkan   Perusahaan 
      addPerusahaan($data);
      break; 
    case 'addKebun':    //menambahkan   Kebun 
      addKebun($data);
      break; 
    case 'tabelNRweek':    //ambil tabel nr week
      tabelNRweek($data);
      break; 
    case 'reset_log_eksekutor':
      reset_log_eksekutor($data);
      break;
    case 'addUser':    //menambahkan   User 
      addUser($data);
      break; 
    case 'updateUser':    //update User 
      updateUser($data);
      break;  
    case 'pwdUpdate':    //update password
      pwdUpdate($data,$userID);
      break; 
    case 'qbank': //pelayanan QueryBank
      qbank($data);
      break;
    case 'test': //pelayanan QueryBank
      test($data);
      break;
    default: // OK : 2023-03-05 
      die("failcode");
      break;
  }  
  die("switch internal fail?");
} else{  
  die("it's forbidden -kode");
}  
 
die("t o"); 

//====================fungsi fungsi=========================
//====================fungsi fungsi=========================
function test($data){ 
  $data='{
    "name": "Tiger Nixon",
    "position": "System Architect",
    "salary": "$320,800",
    "start_date": "2011/04/25",
    "office": "Edinburgh",
    "extn": "5421"
  }';
  die($data);
}


function reset_log_eksekutor($data){
  global $cUmum;    
  $param["id_le"] = intval($data->id_le); 
  // var_dump($param);
  
  $sql="UPDATE log_eksekutor SET flag = 11, waktu=waktu WHERE flag < 11 AND id = :id_le"; 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$param);  
  
  header('Content-Type: application/json'); // Penting!
  if ($iRecAff < 1) {
    $response = [
        'status' => 'fail',
        'message' => 'Data sudah / tidak berubah'
    ]; 
  }else{
    $response = [
        'status' => 'success',
        'message' => 'Data berhasil direset'
    ];
  }
  echo json_encode($response);
  exit; 
}

function bikintabelNRweek($id_node,$sUrl_nrw){ 
  $sSQL =  "SELECT nw.id id_nrw,  CONCAT(mulai, ' ', selesai) AS jadwal, nr.keterangan AS nrole,
  CONCAT(IF(h1=1, 'Minggu, ', ''),IF(h2=1, 'Senin, ', ''), IF(h3=1, 'Selasa, ', ''),
         IF(h4=1, 'Rabu, ', ''), IF(h5=1, 'Kamis, ', ''), IF(h6=1, 'Jumat, ', ''), IF(h7=1, 'Sabtu, ', '')) AS hari_terpilih 
  FROM node_role_week nw INNER JOIN node_role nr ON nr.id = nw.id_role 
  WHERE nw.id_node = $id_node "; 
  // $param["id_node"] = $id_node; 
  $sHtmlTabel = bikinTabelSQL2($sSQL,$sUrl_nrw); 
  return $sHtmlTabel;
}

function tabelNRweek($data){ //ambil tabel nr week
  $id_node = intval($data->id_node);
  $sUrl = $data->sUrl_nrw;
  $sHtmlTabel = bikintabelNRweek($id_node,$sUrl);
  $rspData['tabel'] = $sHtmlTabel;
  die(json_encode($rspData));
} 
  


function addNodeRole1($data){ //menambahkan NodeRole Standart field dari data Post  
  global $cUmum; 
  
  if( intval($data->reff_node ?? 0) == 0){
    splashTengah("Reff Node harus dipilih",1);
  }
  $sMemo = $data->memo ?? "";
  $id_memo = 0;
  if(strlen($sMemo) >= 9 ){ 
    $id_memo =  $cUmum->addMemo($sMemo);
  } 
  // $paramNR['id_role']=intval($data->id_role);
  $paramNR['id_perusahaan']=intval($_SESSION['id_perusahaan'] ); 
  $paramNR['pola'] = intval($data->pola ?? 0);
  $paramNR['flag'] = intval($data->flag ?? 0);
  $paramNR['exeval'] = intval($data->exeval ?? 0);
  $paramNR['exe_v1'] = intval($data->exe_v1 ?? 0);
  $paramNR['exe_v2'] = intval($data->exe_v2 ?? 0);
  $paramNR['satuan'] = intval($data->satuan ?? 0);
  $paramNR['reff_node'] = intval($data->reff_node ?? 0);
  $paramNR['ref_n1'] = intval($data->ref_n1 ?? null);
  $paramNR['ref_n2'] = intval($data->ref_n2 ?? null);
  $paramNR['ref_n3'] = intval($data->ref_n3 ?? null);
  $paramNR['ref_n4'] = intval($data->ref_n4 ?? null);
  $paramNR['ref_n5'] = intval($data->ref_n5 ?? null);
  $paramNR['relay'] = intval($data->relay ?? 0);
  $paramNR['repeater'] = intval($data->repeater ?? 0);
  $paramNR['nilai_1'] = floatval($data->nilai_1 ?? null);
  $paramNR['nilai_2'] = floatval($data->nilai_2 ?? null);
  $paramNR['nilai_3'] = floatval($data->nilai_3 ?? null);
  $paramNR['nilai_4'] = floatval($data->nilai_4 ?? null);
  $paramNR['nilai_5'] = floatval($data->nilai_5 ?? null);
  $paramNR['nilai_6'] = floatval($data->nilai_6 ?? null);
  $paramNR['nilai_7'] = floatval($data->nilai_7 ?? null);
  $paramNR['keterangan']=$data->keterangan??null;
  $paramNR['id_memo']=$id_memo;

 
  global $userID ;
  $paramNR['updater']=$userID;
 
    
  $sql ="INSERT INTO node_role (id_perusahaan, pola, flag, exeval, exe_v1, exe_v2, satuan,
    reff_node, ref_n1,ref_n2,ref_n3,ref_n4,ref_n5,
    relay, repeater, 
    nilai_1, nilai_2,nilai_3,nilai_4,nilai_5,nilai_6,nilai_7,
    keterangan, id_memo, updater
    ) VALUES (
    :id_perusahaan, :pola, :flag, :exeval, :exe_v1, :exe_v2, :satuan,
    :reff_node, :ref_n1, :ref_n2, :ref_n3, :ref_n4, :ref_n5,
    :relay, :repeater, 
    :nilai_1, :nilai_2, :nilai_3, :nilai_4, :nilai_5, :nilai_6, :nilai_7,
    :keterangan, :id_memo, :updater
  )";
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$paramNR); 
  if($iRecAff > 0) splashTengah("$iRecAff record Node_Role, Berhasil ditambahkan");
  splashTengah("ada kesalahan tambah data");  

}


function updateNodeRole1($data){ //edit NodeRole Standart field dari data Post minimalis
  global $cUmum; 
  
  $id_memo = intval($data->id_memo??0);
  $sMemo = $data->memo??"";
  $iRecAff1 = 0 ;
  if($id_memo >1){     
    $iRecAff1 = $cUmum->updateMemo($sMemo,$id_memo);
  } else if(strlen($sMemo) >= 9 ){ 
    $id_memo = $cUmum->addMemo($sMemo);
    $iRecAff1 = ($id_memo > 0) ? 1 : 0 ;
  }
  
   
  $paramNR['id_role']=intval($data->id_role);
  $paramNR['id_perusahaan']=intval($_SESSION['id_perusahaan'] ); 
  $paramNR['pola'] = intval($data->pola ?? 0);
  $paramNR['flag'] = intval($data->flag ?? 0);
  $paramNR['exeval'] = intval($data->exeval ?? 0);
  $paramNR['exe_v1'] = intval($data->exe_v1 ?? 0);
  $paramNR['exe_v2'] = intval($data->exe_v2 ?? 0);
  $paramNR['satuan'] = intval($data->satuan ?? 0);
  $paramNR['reff_node'] = intval($data->reff_node ?? 0);
  $paramNR['ref_n1'] = intval($data->ref_n1 ?? null);
  $paramNR['ref_n2'] = intval($data->ref_n2 ?? null);
  $paramNR['ref_n3'] = intval($data->ref_n3 ?? null);
  $paramNR['ref_n4'] = intval($data->ref_n4 ?? null);
  $paramNR['ref_n5'] = intval($data->ref_n5 ?? null);
  $paramNR['relay'] = intval($data->relay ?? 0);
  $paramNR['repeater'] = intval($data->repeater ?? 0);
  $paramNR['nilai_1'] = floatval($data->nilai_1 ?? null);
  $paramNR['nilai_2'] = floatval($data->nilai_2 ?? null);
  $paramNR['nilai_3'] = floatval($data->nilai_3 ?? null);
  $paramNR['nilai_4'] = floatval($data->nilai_4 ?? null);
  $paramNR['nilai_5'] = floatval($data->nilai_5 ?? null);
  $paramNR['nilai_6'] = floatval($data->nilai_6 ?? null);
  $paramNR['nilai_7'] = floatval($data->nilai_7 ?? null);
  $paramNR['keterangan']=$data->keterangan??null;
  $paramNR['id_memo']=$id_memo;
 
  global $userID ;
  $paramNR['updater']=$userID;
 
 
  $sql =  "UPDATE node_role SET 
    pola = :pola, flag = :flag, exeval = :exeval, exe_v1 = :exe_v1, 
    exe_v2 = :exe_v2, satuan = :satuan, reff_node = :reff_node,
    ref_n1 = :ref_n1, ref_n2 = :ref_n2, ref_n3 = :ref_n3,
    ref_n4 = :ref_n4, ref_n5 = :ref_n5, relay = :relay, repeater = :repeater, 
    nilai_1 = :nilai_1, nilai_2 = :nilai_2, nilai_3 = :nilai_3,
    nilai_4 = :nilai_4, nilai_5 = :nilai_5, nilai_6 = :nilai_6,
    nilai_7 = :nilai_7, keterangan = :keterangan, id_memo = :id_memo, 
    updater = :updater, id_perusahaan = :id_perusahaan
    WHERE id = :id_role"; 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$paramNR);  
  if($iRecAff > 0 || $iRecAff1 > 0) splashTengah("$iRecAff1 Memo, $iRecAff Node_Role, terupdate");
  splashTengah("tidak ada perubahan data");  

}

function UpdateNRW($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;  
  global $userID ;
  
  $param['id_nrw']=intval($data->id_nrw);
  $param['id_role']=intval($data->id_role);
  $param['id_role']=intval($data->id_role);
  $param['id_node']=intval($data->id_node); 
  $sMulai = date('H:i:s', strtotime($data->mulai)); // Mengonversi waktu ke format yang sesuai untuk kolom TIME
  $sSelesai = date('H:i:s', strtotime($data->selesai)); // Mengonversi waktu ke format yang sesuai untuk kolom TIME
  $param['updater']=$userID; 
  $param['h1']=(isset($data->h1) && $data->h1 =="on" )? 1 : 0 ;
  $param['h2']=(isset($data->h2) && $data->h2 =="on" )? 1 : 0 ;
  $param['h3']=(isset($data->h3) && $data->h3 =="on" )? 1 : 0 ;
  $param['h4']=(isset($data->h4) && $data->h4 =="on" )? 1 : 0 ;
  $param['h5']=(isset($data->h5) && $data->h5 =="on" )? 1 : 0 ;
  $param['h6']=(isset($data->h6) && $data->h6 =="on" )? 1 : 0 ;
  $param['h7']=(isset($data->h7) && $data->h7 =="on" )? 1 : 0 ;   
    
  $sql = "UPDATE node_role_week SET id_node = :id_node, id_role= :id_role, mulai=TIME_FORMAT('$sMulai', '%H:%i:%s'), 
  selesai=TIME_FORMAT('$sSelesai', '%H:%i:%s'),  updater=:updater, h1=:h1, h2=:h2, h3=:h3, h4=:h4, h5=:h5, h6=:h6, h7=:h7
   where id=:id_nrw";
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$param); 
  $sUrl = $data->sUrl_nrw;
  $sUrl .="?id_nrw=$param[id_nrw]";
  if($iRecAff > 0) splashTengah("$iRecAff record Jadwal, Berhasil ditambahkan",$sUrl);
  splashTengah("ada kesalahan tambah data");  

}

function addNodeRoleWeek($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;  
  global $userID ;
 
  $param['id_role']=intval($data->id_role);
  $param['id_node']=intval($data->id_node); 
  $sMulai = date('H:i:s', strtotime($data->mulai)); // Mengonversi waktu ke format yang sesuai untuk kolom TIME
  $sSelesai = date('H:i:s', strtotime($data->selesai)); // Mengonversi waktu ke format yang sesuai untuk kolom TIME
  $param['updater']=$userID; 
  $param['h1']=(isset($data->h1) && $data->h1 =="on" )? 1 : 0 ;
  $param['h2']=(isset($data->h2) && $data->h2 =="on" )? 1 : 0 ;
  $param['h3']=(isset($data->h3) && $data->h3 =="on" )? 1 : 0 ;
  $param['h4']=(isset($data->h4) && $data->h4 =="on" )? 1 : 0 ;
  $param['h5']=(isset($data->h5) && $data->h5 =="on" )? 1 : 0 ;
  $param['h6']=(isset($data->h6) && $data->h6 =="on" )? 1 : 0 ;
  $param['h7']=(isset($data->h7) && $data->h7 =="on" )? 1 : 0 ;   
    
  $sql = "INSERT INTO node_role_week ( id_node, id_role, mulai, selesai,  updater, h1, h2, h3, h4, h5, h6, h7 ) 
  VALUES ( :id_node, :id_role, TIME_FORMAT('$sMulai', '%H:%i:%s'),TIME_FORMAT('$sSelesai', '%H:%i:%s'),
  :updater, :h1, :h2, :h3, :h4, :h5, :h6, :h7);";
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$param); 
  $sUrl = $data->sUrl_nrw;
  if($iRecAff > 0) splashTengah("$iRecAff record Jadwal, Berhasil ditambahkan",$sUrl);
  splashTengah("ada kesalahan tambah data");  

}

function getInfoNode($idNode){
  global $cUmum;
  // Query untuk mengambil detail //==pause==
  $sql = "SELECT n.nama,c.chip, k.nama kebun,k.keterangan ketKebun,c.keterangan ketChip, 
  n.keterangan ketNode,m.memo  from node n
  INNER JOIN chip c ON c.id = n.id_chip
  INNER JOIN kebun k ON k.id=c.id_kebun
  LEFT JOIN memo m ON m.id=c.id_memo
  where n.id = :id_node ";  
  $rData = $cUmum->ambil1Row($sql,["id_node" => $idNode]);  
  $sRsp = json_encode($rData);
  die($sRsp); 
}

function getInfoRole($idRole){
  global $cUmum;
  // Query untuk mengambil detail role
  $sql = "SELECT nr.keterangan, nr.id,p.pola,u.fullname, m.memo FROM node_role nr   
    INNER JOIN nrpola p ON p.id=nr.pola 
    INNER JOIN users u ON u.id=nr.updater 
    LEFT JOIN memo m ON m.id = nr.id_memo 
    WHERE nr.id = :id_role ";  
  $rData = $cUmum->ambil1Row($sql,["id_role" => $idRole]);  
  $sRsp = json_encode($rData);
  die($sRsp);
  // die('{"keterangan":"'. $sData . '"}');
}

function getInfoPola($idPola){
  global $cUmum;
  // Query untuk mengambil detail pola
  $sql = "SELECT p.keterangan, p.id, m.memo, p.pola FROM nrpola p 
    LEFT JOIN memo m ON m.id = p.id_memo 
    WHERE p.id = :id_pola ";  
  $rData = $cUmum->ambil1Row($sql,["id_pola" => $idPola]); 
  // $sKetPola=$rData['keterangan'];
  // $sMemo=$rData['memo'];
  $sRsp = json_encode($rData);
  die($sRsp);
  // die('{"keterangan":"'. $sData . '"}');
}


function addChip($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;   
  $sSQL = "INSERT INTO `chip` ( `id_kebun`, `chip`, `id_tipe`, `keterangan`, `versi`, `build`, `id_memo`) 
  VALUES (:id_kebun, :chip, :id_tipe, :keterangan, :versi, :build, :id_memo);";

  $param["id_kebun"] = intval($data->kebun); 
  $param["chip"] = $data->chip;
  $param["id_tipe"] = intval($data->tipe);
  $param["keterangan"] = $data->keterangan;
  $param["versi"] = intval($data->versi);
  $param["build"] = intval($data->build); 
  $param["id_memo"] = $cUmum->addMemo($data->memo);

  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik=__DIR__ . '../webadmin/node_baru.php';
  // if($iRecAff > 0) splashTengah("$iRecAff record Node baru, Berhasil ditambahkan",$urlBalik);
  if($iRecAff > 0) splashTengah("$iRecAff record Chip baru, Berhasil ditambahkan",1);
  splashTengah("ada kesalahan tambah data");  

}
 
function updateChip($data){
  global $cUmum;   
  $sSQL = "UPDATE `chip` SET `id_kebun` = :id_kebun, `chip` = :chip, `id_tipe` = :id_tipe, 
  `keterangan` = :keterangan, `versi` = :versi, `build` = :build, `id_memo` = :id_memo WHERE `chip`.`id` = :id_chip ";

  $iRecAff = 0 ;
  $param["id_chip"] = intval($data->id_chip);
  $param["id_kebun"] = intval($data->kebun); 
  $param["chip"] = $data->chip;
  $param["id_tipe"] = intval($data->tipe);
  $param["keterangan"] = $data->keterangan;
  $param["versi"] = intval($data->versi);
  $param["build"] = intval($data->build); 
  $sMemo = $data->memo;
  $id_memo = intval($data->id_memo); 
  if($id_memo < 1 && strlen($sMemo) > 1){
    $id_memo = $cUmum->addMemo($sMemo);
    if($id_memo > 0) $iRecAff++;
  }else{
    $iRecAff = $cUmum->updateMemo($sMemo, $id_memo);
  }
  $param["id_memo"] = $id_memo;
 
  $iRecAff += $cUmum->eksekusi($sSQL,$param);


  if($iRecAff > 0) splashTengah("$iRecAff record chip,memo terupdate",1);
  splashTengah("ada kesalahan tambah data");

  }

function addNode($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;   
  $sSQL = "INSERT INTO `node` (`id_chip`, `sub_node`, `id_pola`,`id_satuan`, `nama`, `keterangan`,`flag`)
  VALUES (:id_chip, :sub_node, :id_pola, :id_satuan, :nama, :keterangan, :flag)" ;
  $param["id_chip"] = intval($data->id_chip);
  $param["sub_node"] = intval($data->sub_node);
  $param["id_pola"] = intval($data->id_pola);
  $param["id_satuan"] = intval($data->id_satuan);
  $param["nama"] = $data->nama;
  $param["keterangan"] = $data->keterangan;
  $param["flag"] = intval($data->flag); 
 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik=__DIR__ . '../webadmin/node_baru.php';
  // if($iRecAff > 0) splashTengah("$iRecAff record Node baru, Berhasil ditambahkan",$urlBalik);
  if($iRecAff > 0) splashTengah("$iRecAff record Node baru, Berhasil ditambahkan",1);
  splashTengah("ada kesalahan tambah data");  

}

function updateNode($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;   
  $sSQL = "UPDATE `node` set `id_chip`=:id_chip, sub_node=:sub_node, id_pola= :id_pola,id_satuan= :id_satuan,
   nama= :nama, keterangan = :keterangan, flag =:flag where id = :id_node " ;
  $param["id_node"] = intval($data->id_node);
  $param["id_chip"] = intval($data->id_chip);
  $param["sub_node"] = intval($data->sub_node);
  $param["id_pola"] = intval($data->id_pola);
  $param["id_satuan"] = intval($data->id_satuan);
  $param["nama"] = $data->nama;
  $param["keterangan"] = $data->keterangan;
  $param["flag"] = intval($data->flag); 
  
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik= __DIR__ . '../webadmin/node_baru.php';
  if($iRecAff > 0) splashTengah("$iRecAff record update", 1);
  // if($iRecAff > 0) splashTengah("$iRecAff record update",$urlBalik);
  splashTengah("ada kesalahan tambah data");  
  
}

function addKebun($data){
  global $cUmum;    
  
  $sSQL = "INSERT INTO kebun (id_perusahaan, nama, apikey, keterangan, log_limit, flag)
  VALUES (:id_perusahaan, :nama, :apikey, :keterangan, :log_limit, :flag)" ;
  $param["id_perusahaan"] = intval($data->id_perusahaan);
  $param["nama"] = $data->nama;
  $param["apikey"] = $data->apikey;
  $param["keterangan"] = $data->keterangan;
  $param["log_limit"] = intval($data->log_limit);
  $param["flag"] = intval($data->flag); 
  
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param);
  if($iRecAff > 0) splashTengah("$iRecAff record baru, Berhasil ditambahkan",1);
  splashTengah("ada kesalahan tambah data");  
}

function pwdUpdate($data,$userID){
  global $cUmum;

  $sSQL = "SELECT pwd from users where id = :id_user ";
  $param["id_user"] = $userID;
  $rData = $cUmum->ambil1Row($sSQL,$param);
  $sPwdLama = $rData['pwd'];
  if(!password_verify($data->passwordLama, $sPwdLama)){
    splashTengah("Password Lama tidak cocok",1);
    return;
  }  
  $sSQL = "UPDATE users set pwd = :pwd where id = :id_user ";
  $param["id_user"] = $userID;
  $param["pwd"] = password_hash($data->passwordBaru, PASSWORD_DEFAULT); // Menggunakan password_hash dengan algoritma bcrypt

  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param);
  if($iRecAff > 0) splashTengah("$iRecAff record baru, Berhasil di update",1);
  splashTengah("ada kesalahan update data",1);  
}
 
function addUser($data){
  global $cUmum;     
  $sSQL = "INSERT INTO users (id_level, id_perusahaan, fullname, username, pwd, flag_active,email)
  VALUES (:id_level, :id_perusahaan, :fullname, :username, :pwd, :flag_active,:email)" ;
  $param["id_level"] = intval($data->id_level);
  $param["id_perusahaan"] = intval($data->id_perusahaan);
  $param["username"] = $data->username;
  $param["email"] = $data->email;
  $param["fullname"] = $data->fullname;
  $pwd_xn = $data->pwd;
  $param["pwd"] = password_hash($pwd_xn, PASSWORD_DEFAULT); // Menggunakan password_hash dengan algoritma bcrypt
  $param["flag_active"] = intval($data->flag_active); 

  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param);
  if($iRecAff > 0) splashTengah("$iRecAff record baru, Berhasil ditambahkan",1);
  splashTengah("ada kesalahan tambah data");  
}

function updateUser($data){
  global $cUmum;     
  $sSQL = "UPDATE users set id_level = :id_level, id_perusahaan = :id_perusahaan, fullname = :fullname, username = :username, email = :email, flag_active = :flag_active where id = :id_user ";
  $param["id_user"] = intval($data->id_user);
  $param["id_level"] = intval($data->id_level);
  $param["id_perusahaan"] = intval($data->id_perusahaan);
  $param["username"] = $data->username;
  $param["email"] = $data->email;
  $param["fullname"] = $data->fullname;
  $param["flag_active"] = intval($data->flag_active); 

  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param);
  if($iRecAff > 0) splashTengah("$iRecAff record baru, Berhasil di update",1);
  splashTengah("ada kesalahan update data");
}

function addPerusahaan($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;    
  
  $sSQL = "INSERT INTO perusahaan (nama, singkatan, alamat, kota, telp,dirut,flag)
  VALUES (:nama, :singkatan, :alamat, :kota, :telp, :dirut, :flag)" ;
  $param["nama"] = $data->nama;
  $param["singkatan"] = $data->singkatan;
  $param["alamat"] = $data->alamat;
  $param["kota"] = $data->kota;
  $param["telp"] = $data->telp;
  $param["dirut"] = $data->dirut;  
  $param["flag"] = intval($data->flag);

 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik=__DIR__ . '../webadmin/node_baru.php';
  // if($iRecAff > 0) splashTengah("$iRecAff record Node baru, Berhasil ditambahkan",$urlBalik);
  if($iRecAff > 0) splashTengah("$iRecAff record baru, Berhasil ditambahkan",1);
  splashTengah("ada kesalahan tambah data");  

}

function qbank($data){ 
  global $cUmum; 
  
  $sParams = array();
  foreach ($data as $key => $value) {
    if($key == 'iQbID') continue;
    $sParams[$key]=$value;  
  } 
  // die(var_dump($sParams));
  $parID["iQbID"]=$data->iQbID;
  $sSQL =$cUmum->ambil1Data("select query from qbank where id = :iQbID",$parID); 
  // $sTabelData=isiTabelSQL($sSQL,null,$sParams);
  // die($sTabelData);

  $rHasil= $cUmum->ambilData($sSQL,$sParams);
  $sJsonData = $rHasil->fetchAll(PDO::FETCH_ASSOC);

  $sJsonData = json_encode($sJsonData);
  die($sJsonData);  
}

?>