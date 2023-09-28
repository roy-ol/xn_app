<?php //sensor logger 2 menerima array json multi data lebih dari 1 sensor
if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya 
  $cNode = new cNode();   
  $data = $data ; 
}
/**  
 * $data = json_decode($sDataDataPost); 
 {"c":"XN0123456", "n":1, "t":"2023-08-15 18:36:54", "r0":510, "v1":51 }
 {"c":"XN0123456", "t":"2023-08-15 18:36:54","D":[ 
     { "n":1, "r0":510, "v1":51 }, { "n":2, "r0":540, "v1":54 }, 
     { "n":3, "r0":550, "v1":55 }, { "n":4, "r0":560, "v1":56 }   
   ]}
 {"c":"XN0123456", "t":"2023-08-15 18:36:54", "st":90, "ic":3, "D":[   
     { "n":1, "r0_0":509, "r0_1":510, "r0_2":509, "v1_0":50, "v1_1":51,"v1_2":50 }, 
 */
$status = "0";  
if(isset($data->D)){ // format data array bisa jadi lebih dari 1 sensor
  $dataArray = $data->D ; 
  $WaktuNode = $data->t ?? false;
  if(isset($data->st)){ //data beruntun yang disimpan di RTCmemory dari tiap siklus sleep
    $status = sleepDataLogging($data);
  }else{ // data single time / bukan multi data tersimpan RTCmemory tiap sleep
    foreach ($dataArray as $arData) {   
      if($cNode->nodeByChip($data->c,$arData->n)==false) continue ; //tidak ada id_node lanjut continue/next array
      if(!isset($arData->t)) $arData->t = $WaktuNode ; //jika tidak ada  object "t" di dalam gunakan t di root untuk ditambahkan di dalam
      $status += arr_to_logging($arData);
    } 
  }
} else { // satu set json data sensor versi awal
  $status =  arr_to_logging($data);
} 

$respons = ["s" => $status];    
$respons["t"] = $waktu;  
$cNode->dieJsonOK($respons,true);  //plus cek update, contoh hasil {"s":6,"t":"2023-09-07 03:02:43","f":0} 
// $cNode->dieJson($respons);  //contoh hasil {"s":6,"t":"2023-09-07 03:02:43"} 
 

//=====================================================================================================
//================== data dengan SleepTime logging dari array data sensor ============================  
function sleepDataLogging($data){
  global $cNode ;
  /*  {"c":"XN0123456", "t":"2023-08-15 18:36:54","il":234, "st":90, "ic":3, "D":[ 
      { "n":1, "r0_0":509, "r0_1":510, "r0_2":509, "v1_0":50, "v1_1":51,"v1_2":50 }, 
      { "n":2, "r0_0":540, "r0_1":543, "r0_2":542, "v1_0":27, "v1_1":29,"v1_2":28 }, 
      { "n":3, "r0_0":980, "r0_1":978, "r0_2":979, "v1_0":3.87, "v1_1":3.84,"v1_2":3.89 } 
    ]} */
  $WaktuNode = $data->t ?? date("Y-m-d H:i:s"); //now / timestamp terbaru data tersimpan(timestamp terakhir data sensor)
  
  $sleeptime = $data->st; //waktu sleep / selisih waktu data tersimpan
  $iCount = $data->ic; //jumlah data tersimpan di RTC / siklus periode sleep yang disimpan RCT memory 
  $id_loc = $data->il ?? false;     //bila ada nilai il / id_loc id dari tabel senlog lokal
  $Waktu_Node = array(); // Mendeklarasikan array kosong
  $key_r0 = array() ;
  $key_v1 = array() ;
  $dataArray = $data->D ;  
  $status = 0; 
  for ($i=0; $i < $iCount; $i++) { 
    $iDetikSelisih = $sleeptime * ($iCount - $i - 1);
    $Waktu_Node[$i]= date("Y-m-d H:i:s", strtotime($WaktuNode) - $iDetikSelisih);
    $key_r0[$i]="r0_" . $i ;
    $key_v1[$i]="v1_" . $i ; 
    foreach ($dataArray as $arData) {  
      if($cNode->nodeByChip($data->c,$arData->n)==false) continue ; //tidak ada id_node lanjut continue/next array
      $raw0 = $arData->{$key_r0[$i]}; // Menggunakan tanda kurung kurawal untuk mengakses properti objek
      $val1 = $arData->{$key_v1[$i]}; // Menggunakan tanda kurung kurawal untuk mengakses properti objek  
      $status += $cNode->logging($raw0,$val1,$Waktu_Node[$i],$id_loc);  //jumlah row di insert
    } 
  }  
  return $status;
}
  
//==================  logging dari array tunggal data sensor ============================  
function arr_to_logging($arrDataSensor){
  global $cNode ;
  // (isset($arrDataSensor->r0))?$raw0=$arrDataSensor->r0 : $raw0=null ;  // baris ini dan 3 dibawahnya sama hasilnya
  // $raw0 = isset($arrDataSensor->r0) ? $arrDataSensor->r0 : null;       // ternary dengan lebih efisien
  $raw0 = $arrDataSensor->r0 ?? null;                                  // Null Coalescing Operator (PHP 7.0 ke atas):
  // $raw0 = $arrDataSensor->r0 ;                                            // fitur di PHP yang disebut dengan "Null Property Fetching" dan berlaku jika Anda mencoba mengakses properti yang tidak ada dalam objek.
  $val1=$arrDataSensor->v1 ?? null;
  $WaktuNode = $arrDataSensor->t ?? false;  //bila ada nilai t / waktu dari node / Null Coalescing Operator (??) 
  $id_loc = $arrDataSensor->il ?? false;     //bila ada nilai il / id_loc id dari tabel senlog lokal

  //========pause for next atau di class..  belum memperhitungkan maksimal data 
  $rInsert = $cNode->logging($raw0,$val1,$WaktuNode,$id_loc);  //jumlah row di insert
  return $rInsert;
}
