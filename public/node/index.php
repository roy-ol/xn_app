<?php 
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
(json_last_error() == JSON_ERROR_NONE)?:die('forbidden #1'); //cek format json apakah valid


// dari sini ada data post untuk diolah:
// ==========================================
require_once __DIR__ . '/../../app/init_class.php'; 


// $data = json_decode($sDataDataPost); 
$waktu =  gmdate("Y-m-d H:i:s",time() + (3600 * 7)); //gmt+7 jam 

if(isset($_GET['kode'])){  // didapatkan dari setingan htaccess bareng di folder ini RewriteRule ^(.*)$ index.php?kode=$1 [L]
  $kodeApiFile = $_GET['kode'];   
  if(!$kodeApiFile) die("noCode");
  $cNode = new cNode();
  logIncomingData($kodeApiFile . "->" . $sDataDataPost);
  (isset($data->c))?:die("chip"); //tidak ada kode chip = die
  (isset($data->n))?:$data->n=1; // hanya chip tanpa sub_node, n default = 1
  if($cNode->nodeByChip($data->c,$data->n) == false) die("Node");
  hitLogChip();

  switch ($kodeApiFile) { //======pause switch.  ===== case ada yang belum di tes=========
    case 'f':   //fungsi umum / default rutin dr node =========== // belum dibuat
      include_once '../../app/api_node/fungsi_umum.php'; // onDev ======
      break;   
    case 'f1':    //flag Status node  OK : 11-03-2023, f=10 pengiriman json config chip 16-06-2024(onDev) 
      // include_once '../../app/api_node/apiFlagNode1.php'; //rencana apiFlagNode1.php dibuang
      include_once '../../app/api_node/fungsi_umum.php'; // onDev ====== f1 akan dipakai yang lain nantinya
      break;
    case 'f7':    //request url repo binary update OK : 2023-03-05
      include_once '../../app/api_node/apiRepoUpdate.php';
      break; 
    case 'sl':
      include_once '../../app/api_node/apiSensorLoger.php'; //sensor logger  //====running n on dev  
      break;  
    case 'di':  //dipanggil ESP url =  http://xn.online-farm.com/node/di 
      include_once '../../app/api_node/apiDripInject.php'; //aktuator drip Inject //==== awal dipakai Mergan and UNISMA
      break; 
    case 'd1':
      include_once '../../app/api_node/apiRelayDrip1.php'; //aktuator metro
      break; 
    case 'd2':  //dipanggil ESP url =  http://xn.online-farm.com/node/d2 belum OK
      include_once '../../app/api_node/apiRelayDrip2.php';
      break;
    default: // OK : 2023-03-05
      cNodeFailLog("default Switch",$sDataDataPost  );
      die("failcode");
      break;
  } 
  cNodeFailLog($kodeApiFile.": sw int",$sDataDataPost );
  die("switch internal fail");
} else{ 
  cNodeFailLog("no kode",$sDataDataPost);
  die("it's forbidden -kode");
}  
 
die("t o"); 

//=============================fungsi fungsi==================================
//=============================fungsi fungsi================================== 
 
function logIncomingData($dataMasuk){  //log aktuator maksimal sejumlah record di tabel / 5 data terakhir 
  global $cNode; 
  $rData= $cNode->ambil1Row("SELECT COALESCE(min(hit),0) min, COALESCE(max(hit),0) max
      FROM `log_apig` ") ; //WHERE id_node= $idNode "); 
  $min=$rData['min'];
  $max=$rData['max'];
  
  $sql = "update log_apig set data='$dataMasuk', hit = " . ($max + 1);
  $sql .= " where hit = $min" ;  

  $cNode->eksekusi($sql);
}

function cNodeFailLog($sSumber = "", $dataMasuk=""){  //log error node/index.php
  global $cNode; 
  $dataMasuk .= " =>".$_SERVER['REMOTE_ADDR']; 
  $rData= $cNode->ambil1Row("SELECT COALESCE(min(hit),0) min, COALESCE(max(hit),0) max
      FROM `log_fail` ") ; //WHERE id_node= $idNode ");  //jumlah row mengikuti jumlah baris/record di tabel
  $min=$rData['min'];
  $max=$rData['max'];  
  $sql = "update log_fail set sumber='node/$sSumber', data= :dt , hit = " . ($max + 1);
  $sql .= " where hit = $min" ;  
  $param = ["dt" => $dataMasuk];
  $cNode->eksekusi($sql,$param);
}

/**
 * logging jumlah hit id chip tertentu (jumlah permintaan koneksi ke server)
 */
function hitLogChip(){ 
  global $cNode; 
  $sql = "UPDATE hit_chip SET hit = hit+1 WHERE id_chip=$cNode->chipID ";
  $cNode->eksekusi($sql);
}
 
 
  
?>