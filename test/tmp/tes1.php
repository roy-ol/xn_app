<?php

// $tString = base64_decode("testing");
$tString = base64_decode("tes coba");
echo $tString . " \n";
$tString = base64_encode("��-�x");
echo $tString . " \n";

$cb2 = "ƒ";
// $cb2 = "a";
$jumLennya = strlen($cb2);
echo "panjang ".$jumLennya .": <br> ";
for ($i=0; $i < $jumLennya; $i++) { 
  $cb2=ord($cb2[$i]);
  echo $cb2;
  echo "==="; 
}

echo "<br>testing.. :  <br> \n ";
$cb1 = chr(-58);
$cb1 .=chr(-110);
// $cb1 = chr(33-91);
// $cb1 .=chr(33-143);
echo $cb1 ;
echo " =testing.. :  <br> \n ";
$urlnya="https://smk.online-farm.com/tmpapi/getsettingExpo2.php";
$datanya='{"c":"XN3EXPO"}';
$sJawaban = url_postjson($urlnya,$datanya);
echo "<pre> $sJawaban </pre>";
echo "<hr>";
// $krip = kript($sJawaban);
// echo "$krip";
// echo "<hr>";

// echo "<pre>".dekript($krip)."</pre>"; 

// $tesKrip = kript("!b|bƒ|ƒ|");
// echo dekript($tesKrip);


echo "<hr>\n";

// for ($i=0; $i < (127-33); $i++) { 
//   $hasil = $i ^ 255;
//   // if ($hasil >=255) {
//     $karakter1=chr($i);
//     $karakter2=chr($hasil);
//     echo "$i($karakter1)  xor 255 = $hasil($karakter2) " ;
      
//     echo "&#$hasil ";
//     echo "<br>\n ";
//   // } 
// }

//fungsi curl POST url ke luar server
function url_postjson($url,$dataJson){
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url); 
  // curl_setopt( $ch, CURLOPT_POST, TRUE);
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $dataJson );
  // curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE ); 
  $head = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  echo "code jawab " . $httpCode ;
  return $head;
}
 

  
function dekript($sData){
  $iPanjang = strlen($sData); 
  $byteChr = 0;
  $xor =  $iPanjang;//key awal xor 
  $sHasil =""; 
  for ($i=0; $i < $iPanjang; $i++) {  
    echo $sData[$i] . " > " . ord($sData[$i]). " xor $xor = ";
    $byteChr = ord($sData[$i]) - 33; 
    $tmp0 = ($byteChr ^ $xor) ; 
    $xor = $tmp0;  
    $sTmp=chr($tmp0 + 33);
    echo "$tmp0 =+33= $sTmp <br>";
    $sHasil .= $sTmp;  
  } 

  return $sHasil;
} 

function kript($sData){
  $iPanjang = strlen($sData); 
  $byteChr = 0;
  $xor =  $iPanjang;//key awal xor 
  $sHasil =""; 
  echo " hitung : <br>";
  for ($i=0; $i < $iPanjang; $i++) {  
    echo $sData[$i] . " > " . ord($sData[$i]). " xor $xor = ";
    $byteChr = ord($sData[$i]) - 33; 
    $tmp0 = ($byteChr ^ $xor) + 33;  // pengaman biar tidak dapat null + 33 karakter keyboard UTF-8
    $xor = $byteChr;
    $sTmp=chr($tmp0);
    echo "$tmp0 == $sTmp <br>";
    $sHasil .= $sTmp;     
  }   
  echo " hitung : <br>";
  return $sHasil;
} 
 