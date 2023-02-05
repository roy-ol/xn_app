<?php 

// echo '<!DOCTYPE html> <html lang="en-US"><body>';
$sTest = '{ "K0" : "XN0123456","K2":244.99,"K3": 12545699   }';
// $sTest = '{1029 }';
echo "\n".$sTest ."\n"; 
$hasil = xnkrip1($sTest);

$dekripan = xndekrip1($hasil);
echo "\n asal = ".$sTest ."\n"; 
echo "\n dkrp = ".$dekripan ."\n"; 
echo "\nkrip = $hasil\n" ;  
// echo '</body> </html>';
 

//============fungsi fungsi========== 

function xndekrip1($sData){  //
  $sKunci= ' ":,.K0123456789'; //dekrip spasi dihilangkan..!
  $iKunci =16; //=16 char -- spasi == null / dihilangkan
  $iPanjang = strlen($sData);   
  $sHasil =""; 
  $iHit = 0 ;  
  $binChar = 0;
  $binChar1 = 0; //default bila tidak ada di map key.. karakter akan dihilangkan chr(0) = NUL
  $binChar2 = 0; //default bila tidak ada di map key.. karakter akan dihilangkan chr(0) = NUL

  for ($i=0; $i < $iPanjang; $i++) {  
    $binChar = ord($sData[$i]) ; 
    $bFaktor16 = 16;
    if($binChar >= $bFaktor16){
      $binChar2 = (int)($binChar / $bFaktor16); //MSB biner 4 digit sebelah kiri.. di dekrip jadi binChar2 digit sebelah kanan dari binChar1
      $binChar1 = $binChar - (16 * $binChar2); 
      $sHasil .= $sKunci[$binChar1].$sKunci[$binChar2]; 
    }else{
      $sHasil .= $sKunci[$binChar];
    }       
  }   
  $sHasil = "{ $sHasil }";
  return $sHasil;    
}

function xnkrip1($sData){  //
  $sKunci= ' ":,.K0123456789'; //dekrip spasi dihilangkan..!
  $iKunci =16; //=16 char -- spasi == null / dihilangkan
  $sData = str_replace("XN","",$sData); //buang karakter XN
  $sData = str_replace(" ","",$sData); //buang karakter spasi
  $iPanjang = strlen($sData);   
  $sHasil =""; 
  $iHit = 0 ; 
  $iPanjang-- ; //panjang data tanpa penutup "}" 
  $binChar = 0;
  $binChar1 = 0; //default bila tidak ada di map key.. karakter akan dihilangkan chr(0) = NUL

  for ($i=1; $i < $iPanjang; $i++) {  
    for ($iK=0; $iK < $iKunci; $iK++) { 
      if ($sData[$i] === $sKunci[$iK]) {
        $binChar1 = decbin($iK); 
        break 1;
      }
    }
    if(bindec($binChar1) === 0) continue;
    
    $iHit++; 
    if ($iHit === 1 ) {  // jadi LSB // ganjil sebelah kanan.. nantinya iHit2 sebelah kiri (MSB) 
      $binChar = $binChar1; 
      $binChar1 = 0;
      if($i === $iPanjang -1) $sHasil .= chr(bindec($binChar)); 
    } else {
      $binChar += $binChar1 * decbin(16); // dikalikan 16(1 0000) menjadi MSB di sebelah kiri 
      $sHasil .= chr(bindec($binChar)); 
      $binChar ="";
      $iHit = 0;
    }      
  }   
  return $sHasil;    
}



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
