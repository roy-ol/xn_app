<?php
/**
 * fungsi umum  untuk kebutuhan general awal koneksi
 */
require_once __DIR__ . '../../../../app/init_class.php';
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Simpan URL halaman permintaan saat ini
    $_SESSION['last_page'] = $_SERVER['REQUEST_URI']; 
    $sDir = 'login.php';
    if(!file_exists($sDir)) $sDir = '../login.php';
    if(!file_exists($sDir)) $sDir = '../../login.php';
    // echo $sDir;
    header("Location: $sDir"); // Redirect ke halaman login jika belum login
    exit;
}
 
$cUmum = new cUmum();
$cUser = new cUser();
// if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya  
//     $cUser = new cUser();
// }  


$userID=intval($_SESSION['userID']);
$cUser->loadUserByID($userID);
$id_perusahaan=intval($_SESSION['id_perusahaan']) ; 
$sNamaPerus = $cUser->getNamaPerusahaan();
$id_level =  intval($_SESSION['id_level']);

//================fungsi fungsi umum web php koneksi dll   

/**
 * @brief membuat tampilan tabel dari query sql dengan Key dan link target tujuan
 *        mengisi tHead dan isi tr
 * @param $sqlQuery Select dg kolom pertama adalah key yang hidden 
 *          sekaligus nama keynya untuk kirim ke url link target
 * @param $sLink tujuan url dengan  nilai sKey pola httacces folder web/page
 */
function isiTabelSQL($sqlQuery, $sLink = null) {
    // Menggunakan kelas umum untuk eksekusi query
    global $cUmum ;
    $sKunci = "id";
    // Query SQL
    $hasil = $cUmum->ambilData($sqlQuery);
    $result = $hasil->fetchAll(PDO::FETCH_ASSOC); 

    if (empty($result)) {
        return '<p>Tidak ada data yang ditemukan.</p>';
    }

    // Buat tampilan tabel
    $tableHTML = '<thead><tr>';
    $iKolom = 0 ;
    // Membuat header tabel dari nama kolom hasil query
    foreach(array_keys($result[0]) as $columnName) {
        if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
            $iKolom++;
            $sKunci = $columnName ;
            continue; 
        }  
        $tableHTML .= '<th>'.$columnName.'</th>';
        $iKolom++; 
    }
    $tableHTML .= '</thead></tr>'; 
    
    // Membuat baris tabel dari hasil query
    foreach($result as $row) {
        $tableHTML .= '<tr>'; 
        $iKolom = 0 ;
        $keyVal = 0;
        foreach($row as $value) {
            if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
                $iKolom++;
                $keyVal = intval($value);
                continue;
            }
            if($iKolom == 1 && $sLink !== null){
                $iKolom++; //<a href="index.php?key1=value1&key2=value2">edit</a>
                $sDataCell1 = '<a href="'.$sLink.'$$'.$sKunci.'$$'.$keyVal.'">'.$value.'</a>' ; //berisi link dan key
                $tableHTML .= '<td>'.$sDataCell1.'</td>';
                continue;
            }
            $tableHTML .= '<td>'.$value.'</td>';
            $iKolom++;
        } 
        $tableHTML .= '</tr>';
    }
 

    return $tableHTML;
}


/**
 * @brief membuat tampilan tabel dari query sql dengan Key dan link target tujuan
 * @param $sqlQuery Select dg kolom pertama adalah key yang hidden 
 *          sekaligus nama keynya untuk kirim ke url link target
 * @param $sLink tujuan url dengan  nilai sKey pola httacces folder web/page
 */
function bikinTabelSQL3($sqlQuery, $sLink = null) {
    // Menggunakan kelas umum untuk eksekusi query
    global $cUmum ;
    $sKunci = "id";
    // Query SQL
    $hasil = $cUmum->ambilData($sqlQuery);
    $result = $hasil->fetchAll(PDO::FETCH_ASSOC); 

    if (empty($result)) {
        return '<p>Tidak ada data yang ditemukan.</p>';
    }

    // Buat tampilan tabel
        $tableHTML = '<table class="table table-striped table-bordered"><thead><tr>';
    $iKolom = 0 ;
    // Membuat header tabel dari nama kolom hasil query
    foreach(array_keys($result[0]) as $columnName) {
        if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
            $iKolom++;
            $sKunci = $columnName ;
            continue; 
        }  
        $tableHTML .= '<th>'.$columnName.'</th>';
        $iKolom++; 
    }
    
    $tableHTML .= '</thead></tr>'; 
    // Membuat baris tabel dari hasil query
    foreach($result as $row) {
        $tableHTML .= '<tr>'; 
        $iKolom = 0 ;
        $keyVal = 0;
        foreach($row as $value) {
            if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
                $iKolom++;
                $keyVal = intval($value);
                continue;
            }
            if($iKolom == 1 && $sLink !== null){ //== 
                $iKolom++; //<a href="index.php?key1=value1&key2=value2">edit</a>
                $sDataCell1 = '<a href="'.$sLink.'$$'.$sKunci.'$$'.$keyVal.'">'.$value.'</a>' ; //berisi link dan key
                $tableHTML .= '<td>'.$sDataCell1.'</td>';
                continue;
            }
            $tableHTML .= '<td>'.$value.'</td>';
            $iKolom++;
        } 
        $tableHTML .= '</tr>';
    }

    // Menutup tabel
    $tableHTML .= '</table>';

    return $tableHTML;
}


/**
 * @brief membuat tampilan tabel dari query sql dengan Key dan link target tujuan
 * @param $sqlQuery Select dg kolom pertama adalah key yang hidden 
 *          sekaligus nama keynya untuk kirim ke url link target
 * @param $sLink tujuan url dengan  nilai sKey menggunakan metode GET
 */
function bikinTabelSQL2($sqlQuery, $sLink = null) {
    // Menggunakan kelas umum untuk eksekusi query
    global $cUmum ;
    $sKunci = "id";
    // Query SQL
    $hasil = $cUmum->ambilData($sqlQuery);
    $result = $hasil->fetchAll(PDO::FETCH_ASSOC); 

    if (empty($result)) {
        return '<p>Tidak ada data yang ditemukan.</p>';
    }

    // Buat tampilan tabel
    $tableHTML = '<table><tr>';
    $iKolom = 0 ;
    // Membuat header tabel dari nama kolom hasil query
    foreach(array_keys($result[0]) as $columnName) {
        if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
            $iKolom++;
            $sKunci = $columnName ;
            continue; 
        }  
        $tableHTML .= '<th>'.$columnName.'</th>';
        $iKolom++; 
    }
    
    $tableHTML .= '</tr>'; 
    // Membuat baris tabel dari hasil query
    foreach($result as $row) {
        $tableHTML .= '<tr>'; 
        $iKolom = 0 ;
        $keyVal = 0;
        foreach($row as $value) {
            if($iKolom == 0 && $sLink !== null){  //=== kolom pertama kalau jadi kunci link tidak ditampilkan
                $iKolom++;
                $keyVal = intval($value);
                continue;
            }
            if($iKolom == 1 && $sLink !== null){
                $iKolom++; //<a href="index.php?key1=value1&key2=value2">edit</a>
                $sDataCell1 = '<a href="'.$sLink.'?'.$sKunci.'='.$keyVal.'">'.$value.'</a>' ; //berisi link dan key
                $tableHTML .= '<td>'.$sDataCell1.'</td>';
                continue;
            }
            $tableHTML .= '<td>'.$value.'</td>';
            $iKolom++;
        } 
        $tableHTML .= '</tr>';
    }

    // Menutup tabel
    $tableHTML .= '</table>';

    return $tableHTML;
}

/**
 * membuat tampilan tabel dari query sql
 */
function bikinTabelSQL($sqlQuery) {
    // Menggunakan kelas umum untuk eksekusi query
    global $cUmum ;
 
    // Query SQL
    $hasil = $cUmum->ambilData($sqlQuery);
    $result = $hasil->fetchAll(PDO::FETCH_ASSOC); 

    if (empty($result)) {
        return '<p>Tidak ada data yang ditemukan.</p>';
    }

    // Buat tampilan tabel
    $tableHTML = '<table>
                    <tr>';
    
    // Membuat header tabel dari nama kolom hasil query
    foreach(array_keys($result[0]) as $columnName) {
        $tableHTML .= '<th>'.$columnName.'</th>';
    }
    
    $tableHTML .= '</tr>'; 
    // Membuat baris tabel dari hasil query
    foreach($result as $row) {
        $tableHTML .= '<tr>'; 
        foreach($row as $value) {
            $tableHTML .= '<td>'.$value.'</td>';
        } 
        $tableHTML .= '</tr>';
    }

    // Menutup tabel
    $tableHTML .= '</table>';

    return $tableHTML;
}

/**
 * bikin isian option dari sql berisi id (ex: <Select .. . )
 * @param sTampil wajib ada setelah id text tampil di dalam opsi
 * @param sp1 = pemisah 1 2 3
 * @param tampil1 = field dari query untuk menjadi teks ditampilkan 1 2 3 
 * @param iTerpilih = default id terpilih untuk tampil di option
 */
function bikinOption($sqlQuery,$iTerpilih=0, $sTampil,$sp1="",$sTampil1="",$sp2="",$sTampil2="",$sp3="",$sTampil3=""){
  global $cUmum ;
  $result = $cUmum->ambilData($sqlQuery); 
  // Memeriksa apakah query berhasil dijalankan
  if ($result) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
        $sTerpilih = (intval($row['id']) == $iTerpilih )? 'SELECTED' : '' ;
        $oTampil1 = ($sTampil1 !== "")?$row[$sTampil1]:"";
        $oTampil2 = ($sTampil2 !== "")?$row[$sTampil2]:"";
        $oTampil3 = ($sTampil3 !== "")?$row[$sTampil3]:"";
        echo '<option value="' . $row['id'] . '" ' .$sTerpilih .'>' . $row[$sTampil] .$sp1 . $oTampil1 
        . $sp2 .  $oTampil2  . $sp3 . $oTampil3  . '</option>';
      }
  } else { echo "Error: " . $sqlQuery . "<br>" . $cUmum->getPDO()->errorInfo()[2];}
}
 
/**
 * Tampilkan pesan di tengah layar dan redirect ke $sLinkRedirect setelah $iMillisSplash milidetik
 * @param string $sPesan pesan yang ingin ditampilkan
 * @param string $sLinkRedirect jika null maka akan kembali ke halaman sebelumnya, jika lebih dari 0 maka akan kembali ke halaman sebelumnya sebanyak $sLinkRedirect
 * @param int $iMillisSplash waktu dalam milidetik
 */
function splashTengah($sPesan = "Berhasil", $sLinkRedirect=null, $iMillisSplash = 3339){ 
    if($sLinkRedirect == null){
        // $sLinkRedirect = "window.location.href";
        $sLinkRedirect = "window.history.go(-2)";
    } elseif($sLinkRedirect > 0 && $sLinkRedirect < 7 ){
        $sLinkRedirect = 0 - $sLinkRedirect;
        $sLinkRedirect = "window.history.go($sLinkRedirect)";
    } else {
        $sLinkRedirect = "window.location.href = '$sLinkRedirect'";
    }
    $sHTMLSplash = '<!DOCTYPE html> <head><style> .centered-message {
        position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);
        text-align: center;}</style> <script> setTimeout(function() {'.$sLinkRedirect.' ;
        }, '. intval($iMillisSplash) .'); </script>
    </head><body><div class="centered-message"> <h2>'.$sPesan.' </h2> </div></body></html> '; 
   die($sHTMLSplash);
}



?>