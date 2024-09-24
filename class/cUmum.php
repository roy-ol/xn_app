<?php
// require_once __DIR__ . '/../app/init_class.php';

class cUmum extends cKoneksi{ 

  function __construct(){      
    parent::__construct();
  }
  
  
  /**
   * Menambahkan memo baru ke dalam table memo.
   *
   * @param string $sMemo isi memo yang akan ditambahkan
   * @return int id dari memo yang baru dibuat
   */
  function addMemo($sMemo){ 
    $iRecAff1 = 0 ;  
  
    $sql = "INSERT INTO memo(memo) values (:memo) ";
    $paramMemo['memo']=$sMemo;
    parent::eksekusi($sql,$paramMemo);
    $id_memo = parent::ambil1Data("SELECT LAST_INSERT_ID();"); 
    return $id_memo;
  }
  
  /**
   * Mengupdate memo yang ada di dalam table memo berdasarkan idmemo yang diberikan.
   *
   * @param int $id_memo id dari memo yang diupdate
   * @param string $sMemo isi memo yang akan diupdate
   *
   * @return int jumlah record yang terupdate
   */
  function updateMemo($sMemo,$id_memo){    
    $sql = "UPDATE memo set memo= :memo where id=$id_memo ; ";
    $paramMemo['memo']=$sMemo;
    return parent::eksekusi($sql,$paramMemo); 
  }

  function getMemo($id_memo){  
    if($id_memo < 1) return "";
    $sql = "SELECT memo FROM memo where id=$id_memo ; ";
    return parent::ambil1Data($sql);
  }

  //fungsi curl POST data JSON ke url luar server
  function url_postjson($url,$dataJson){
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url);  
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $dataJson ); 
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE ); 
    $sJawaban = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch); 
    if($httpCode >= 400) $sJawaban = $httpCode;
    return $sJawaban;
  }
  
  //dekripting 15 karakter angka + huruf K + tanda json buang spasi
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

  //kripting 15 karakter angka + huruf K + tanda json buang spasi
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
 
  //dekript karakter 0+33 sd 255 => xor dg char didepan dimulai iPanjang data
  function xndekript0($sData){
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

  //kript karakter 0+33 sd 255 => xor dg char didepan dimulai iPanjang data
  function xnkript0($sData){
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


}