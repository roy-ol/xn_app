<?php
  // $sTest = "ab";
  $sTest = '{"c":"XN0123456","v1":244.99}';
  echo $sTest ."\n"; 
  $hasil = kript($sTest);
  echo $hasil;
  
  echo "\n"; 
  $hasil2 = dekript($hasil);
  echo $hasil2;

 

  //============fungsi fungsi========== 
 function dekript($sData){
  $iPanjang = strlen($sData); 
  $byteChr = 0;
  $xor =  $iPanjang;//key awal xor 
  $sHasil =""; 
  for ($i=0; $i < $iPanjang; $i++) {  
    $byteChr = ord($sData[$i]) - 33; 
    $tmp0 = ($byteChr ^ $xor) ; 
    $xor = $tmp0;  
    $sTmp=chr($tmp0 + 33);
    $sHasil .= $sTmp;  
  } 
  
  return $sHasil;
 } 

 function kript($sData){
  $iPanjang = strlen($sData); 
  $byteChr = 0;
  $xor =  $iPanjang;//key awal xor 
  $sHasil =""; 
  for ($i=0; $i < $iPanjang; $i++) {  
    $byteChr = ord($sData[$i]) - 33; 
    $tmp0 = ($byteChr ^ $xor) + 33;  // pengaman biar tidak dapat null + 33 karakter keyboard UTF-8
    $xor = $byteChr;  
    $sTmp=chr($tmp0);
    $sHasil .= $sTmp;     
  }   
  return $sHasil;
 } 
  