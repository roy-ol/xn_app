<?php  
//api awal untuk aplikasi relay drip system GH Metro
include '../tmpapi/fsambungan.php';   

$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect'); 
$con->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, TRUE);  // hasil mengikuti nativ... tidak digeneralisir string
 
$waktu =  gmdate("Y-m-d H:i:s",time() + (3600 * 7)); //gmt+7 jam 
 

if($_SERVER['REQUEST_METHOD']=='POST'){  
	$sDataDataPost=file_get_contents('php://input');  
    
	// logIncomingData($sDataDataPost); 
    // echo $sDataDataPost;    

  $chipID='NOCHIP';
	$data = json_decode($sDataDataPost);
	$chipID=$data->c;
  logApi($chipID);  
  $id_node = 0;
  $dataNode=ambilData("SELECT *  FROM node n  WHERE n.chip='$chipID'");
  if($dataNode) $id_node = $dataNode['id'];
  if($id_node == 0 ) die("err");
	
  if(isset($_GET['kode'])){
    $kodeApiFile = $_GET['kode'];   
    switch ($kodeApiFile) {
      case 'd1':
        include_once 'apiRelayDrip1.php';
        break;
      case '∟':
        include_once 'apiRelayDrip1.php';
        break;
      case 'f1':    //flag Status node
        include_once 'apiFlagNode1.php';
        break;
      
      default:
        mysqli_close($con); 
        die("failcode");
        break;
    } 
  } else{
    mysqli_close($con);
    die("it's forbidden");
  }  
	mysqli_close($con); 
  die("nope miss code");
}else{ 
	mysqli_close($con);
	die('page isn`t working ;(');
}

//===fungsi fungsi====
//===fungsi fungsi====
//===fungsi fungsi====
function ambilData($sQuery){ //ambil 1 row hasil
  global $con; 
  $r = mysqli_query($con,$sQuery); 
  // while($arrData = mysqli_fetch_array($r)){
  //     return $arrData[$sKey];
  // }   
  return mysqli_fetch_array($r);
}


function logApi($sChip = '' ) {
	global $con;  
	$sql = "INSERT INTO log_apirelay(chip)  VALUES ('$sChip') 
            ON DUPLICATE KEY UPDATE waktu=CURRENT_TIMESTAMP, hit=hit+1"; 
	$r = mysqli_query($con,$sql); 

} 

function logAktuator($chip='', $noRelay=1, $exetime = 0){ 
    global $con;
    $sql = "INSERT INTO log_aktuator(chip,no_relay,exetime) VALUES ('$chip', $noRelay, $exetime)
        ON DUPLICATE KEY UPDATE exetime =  $exetime, waktu=now()";
    $r = mysqli_query($con,$sql);
}
 

function logAktuator2($idNode, $relay=1, $exetime = 0){ 
    global $con; 
    $rData=ambilData("SELECT count(id) jum, COALESCE(max(hit),0) max, COALESCE(min(hit),0) min 
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
 
    $r = mysqli_query($con,$sql);
}

 
//Keluar close con and die(f=0);
//@param null no parameter
function keluar(){     
  global $con;
  $responNone = '{"f":0}';  //flag respons ke ESP tidak ada yang perlu dilakukan
  mysqli_close($con);
  die($responNone);
}


mysqli_close($con);
?>