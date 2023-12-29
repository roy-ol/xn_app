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
      include_once '../../app/api_node/apixxxxxxx'; //next coding
      break; 
    case 'f1':    //flag Status node  OK : 11-03-2023
      include_once '../../app/api_node/apiFlagNode1.php';
      break;
    case 'f7':    //request url repo binary update OK : 2023-03-05
      include_once '../../app/api_node/apiRepoUpdate.php';
      break; 
    case 'sl':
      include_once '../../app/api_node/apiSensorLoger.php'; //sensor logger  //====running n on dev //==pause
      break;  
    case 'di':  //dipanggil ESP url =  http://xn.online-farm.com/node/di 
      include_once '../../app/api_node/apiDripInject.php'; //aktuator drip Inject //==== on dev
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


//pause=========






//===old to delete .. ?
function logAktuator0($chip='', $noRelay=1, $exetime = 0){ 
  global $con;
  $sql = "INSERT INTO log_aktuator(chip,no_relay,exetime) VALUES ('$chip', $noRelay, $exetime)
      ON DUPLICATE KEY UPDATE exetime =  $exetime, waktu=now()";
  // $r = mysqli_query($con,$sql);
}

 
function logAktuator2($idNode, $relay=1, $exetime = 0){  //log aktuator 5 data terakhir
  global $cNode; 
  $rData=$cNode->ambilData("SELECT count(id) jum, COALESCE(max(hit),0) max, COALESCE(min(hit),0) min 
      FROM `log_aktuator2` WHERE id_node= $idNode ");
  $jum=$rData['jum'];
  $max=$rData['max'];
  $min=$rData['min'];
  if ($jum < 5) {  // batas Record yang disimpan di log aktuator tiap id_node
    $sql = "insert into log_aktuator2(id_node,relay,exetime,hit) values($idNode ,";
    $sql .= " $relay , $exetime , " . ($max + 1) . ")" ; 
  }else{
    $sql = "update log_aktuator2 set relay=$relay, exetime = $exetime, hit = " . ($max + 1);
    $sql .= " where id_node = $idNode and hit = $min" ;  
  } 
  $r = $cNode->eksekusi($sql);
}

  
?>