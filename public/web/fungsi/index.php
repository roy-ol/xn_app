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
    case 'addNodeRole1':    //menambahkan NodeRole Standart field
      addNodeRole1($data);
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

function addNodeRole1($data){ //menambahkan NodeRole Standart field dari data Post minimalis
  global $cUmum; 

  $sMemo = $data->memo;
  $id_memo = 0;
  if(strlen($sMemo) >= 9 ){
    $sql = "INSERT INTO memo(memo) values (:memo) ";
    $paramMemo['memo']=$data->memo;
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
  if($iRecAff > 0) splashBerhasil("$iRecAff record Node_Role, Berhasil ditambahkan","../node_role.php");
  splashBerhasil("ada kesalahan tambah data");  

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



?>