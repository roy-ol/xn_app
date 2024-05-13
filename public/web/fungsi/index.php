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

function updateNodeRole1($data){ //edit NodeRole Standart field dari data Post minimalis
  global $cUmum; 
  
  $id_memo = intval($data->id_memo);
  $sMemo = $data->memo ;
  $iRecAff1 = 0 ;
  if($id_memo >1){    
    $sql = "UPDATE memo set memo= :memo where id=$id_memo ; ";
    $paramMemo['memo']=$sMemo;
    $iRecAff1 = $cUmum->eksekusi($sql,$paramMemo);
    // $id_memo = $cUmum->ambil1Data("SELECT LAST_INSERT_ID();"); 
  } else if(strlen($sMemo) >= 9 ){
    $sql = "INSERT INTO memo(memo) values (:memo) ";
    $paramMemo['memo']=$sMemo;
    $cUmum->eksekusi($sql,$paramMemo);
    $id_memo = $cUmum->ambil1Data("SELECT LAST_INSERT_ID();"); 
    $iRecAff1 = ($id_memo > 0) ? 1 : 0 ;
  }
  
   
  $paramNR['id_role']=intval($data->id_role);
  $paramNR['id_perusahaan']=intval($_SESSION['id_perusahaan'] ); 
  $paramNR['pola']=intval($data->pola);
  $paramNR['exeval']=intval($data->exeval);
  $paramNR['exe_v1']=intval($data->val1);
  $paramNR['exe_v2']=intval($data->val2);
  $paramNR['reff_node']=intval($data->reff_node);
  $paramNR['relay']=intval($data->relay);
  $paramNR['repeater']=intval($data->repeater);  
  $paramNR['nilai_1']=intval($data->nilai_1);
  $paramNR['keterangan']=$data->keterangan;
  $paramNR['id_memo']=$id_memo; 
  global $userID ;
  $paramNR['updater']=$userID;
 
  $sql = "UPDATE node_role SET pola = :pola, exeval = :exeval, exe_v1 = :exe_v1, exe_v2 = :exe_v2, reff_node = :reff_node,
   relay = :relay, repeater = :repeater, nilai_1 = :nilai_1, keterangan = :keterangan, id_memo = :id_memo, updater = :updater 
   WHERE id = :id_role AND id_perusahaan = :id_perusahaan";

  // $sql = "UPDATE node_role set pola = :pola, exeval= :exeval, exe_v1= :exe_v1, exe_v2=:exe_v2, reff_node=:reff_node,
  //  relay=:relay, repeater=:repeater, nilai_1=:nilai1, keterangan=:keterangan, id_memo=:id_memo, updater=:updater 
  //  WHERE id = :id_role and id_perusahaan = :id_perusahaan ; ";
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$paramNR); 
  //==============
  // echo $sql;
  // var_dump($paramNR);
  // return;
  //============
  // $sLinkRedirect = '/../node_role.php';
  if($iRecAff > 0 || $iRecAff1 > 0) splashBerhasil("$iRecAff1 Memo, $iRecAff Node_Role, terupdate");
  splashBerhasil("tidak ada perubahan data");  

}

function addNodeRole1($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum; 
 
  // $sMemo = (isset($data->memo))? $data->memo : $sMemo="" ;
  $sMemo = $data->memo ;
  $id_memo = 0;
  if(strlen($sMemo) >= 9 ){
    $sql = "INSERT INTO memo(memo) values (:memo) ";
    $paramMemo['memo']=$sMemo;
    $cUmum->eksekusi($sql,$paramMemo);
    $id_memo = $cUmum->ambil1Data("SELECT LAST_INSERT_ID();"); 
  }
  
   
  $paramNR['id_perusahaan']=intval($_SESSION['id_perusahaan'] ); 
  $paramNR['pola']=intval($data->pola);
  $paramNR['exeval']=intval($data->exeval);
  $paramNR['exe_v1']=intval($data->val1);
  $paramNR['exe_v2']=intval($data->val2);
  $paramNR['reff_node']=intval($data->reff_node);
  $paramNR['relay']=intval($data->relay);
  $paramNR['repeater']=intval($data->repeater);  
  $paramNR['nilai_1']=intval($data->nilai_1);
  $paramNR['keterangan']=$data->keterangan;
  $paramNR['id_memo']=$id_memo; 
  global $userID ;
  $paramNR['updater']=$userID;
 
    
  $sql = "INSERT INTO node_role (id_perusahaan, pola, exeval, exe_v1, exe_v2, reff_node,
   relay, repeater, nilai_1, keterangan, id_memo, updater) VALUES (:id_perusahaan, :pola, :exeval, 
   :exe_v1, :exe_v2, :reff_node, :relay, :repeater, :nilai_1, :keterangan, :id_memo,
    :updater )";
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sql,$paramNR); 
  if($iRecAff > 0) splashBerhasil("$iRecAff record Node_Role, Berhasil ditambahkan");
  splashBerhasil("ada kesalahan tambah data");  

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
  if($iRecAff > 0) splashBerhasil("$iRecAff record Jadwal, Berhasil ditambahkan",1);
  splashBerhasil("ada kesalahan tambah data");  

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
  $sql = "SELECT p.keterangan, p.id, m.memo FROM nrpola p 
    LEFT JOIN memo m ON m.id = p.id_memo 
    WHERE p.id = :id_pola ";  
  $rData = $cUmum->ambil1Row($sql,["id_pola" => $idPola]); 
  // $sKetPola=$rData['keterangan'];
  // $sMemo=$rData['memo'];
  $sRsp = json_encode($rData);
  die($sRsp);
  // die('{"keterangan":"'. $sData . '"}');
}


function addNode($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;   
  $sSQL = "INSERT INTO `node` (`id_chip`, `sub_node`, `id_pola`, `nama`, `keterangan`,`flag`)
  VALUES (:id_chip, :sub_node, :id_pola, :nama, :keterangan, :flag)" ;
  $param["id_chip"] = intval($data->id_chip);
  $param["sub_node"] = intval($data->sub_node);
  $param["id_pola"] = intval($data->id_pola);
  $param["nama"] = $data->nama;
  $param["keterangan"] = $data->keterangan;
  $param["flag"] = intval($data->flag); 
 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik=__DIR__ . '../webadmin/node_baru.php';
  // if($iRecAff > 0) splashBerhasil("$iRecAff record Node baru, Berhasil ditambahkan",$urlBalik);
  if($iRecAff > 0) splashBerhasil("$iRecAff record Node baru, Berhasil ditambahkan",1);
  splashBerhasil("ada kesalahan tambah data");  

}

function updateNode($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum;   
  $sSQL = "UPDATE `node` set `id_chip`=:id_chip, sub_node=:sub_node, id_pola= :id_pola, nama= :nama ,
   keterangan = :keterangan, flag =:flag where id = :id_node " ;
  $param["id_node"] = intval($data->id_node);
  $param["id_chip"] = intval($data->id_chip);
  $param["sub_node"] = intval($data->sub_node);
  $param["id_pola"] = intval($data->id_pola);
  $param["nama"] = $data->nama;
  $param["keterangan"] = $data->keterangan;
  $param["flag"] = intval($data->flag); 
  
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik= __DIR__ . '../webadmin/node_baru.php';
  if($iRecAff > 0) splashBerhasil("$iRecAff record update", 1);
  // if($iRecAff > 0) splashBerhasil("$iRecAff record update",$urlBalik);
  splashBerhasil("ada kesalahan tambah data");  
  
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
  if($iRecAff > 0) splashBerhasil("$iRecAff record baru, Berhasil ditambahkan",1);
  splashBerhasil("ada kesalahan tambah data");  
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
 
  $iRecAff = 0 ;
  $iRecAff = $cUmum->eksekusi($sSQL,$param); 
  // $urlBalik=__DIR__ . '../webadmin/node_baru.php';
  // if($iRecAff > 0) splashBerhasil("$iRecAff record Node baru, Berhasil ditambahkan",$urlBalik);
  if($iRecAff > 0) splashBerhasil("$iRecAff record baru, Berhasil ditambahkan",1);
  splashBerhasil("ada kesalahan tambah data");  

}

?>