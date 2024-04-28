<?php
/**
 * fungsi umum  untuk kebutuhan general awal koneksi
 */
require_once __DIR__ . '../../../../app/init_class.php';
session_start();

 
$cUmum = new cUmum();
$cUser = new cUser();
// if(1==0){ //dummy if syntact hanya agar editor mengenali variabel &/ $cNode sebagai class sebelumnya  
//     $cUser = new cUser();
// }  

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit;
}

$userID=intval($_SESSION['userID']);
$cUser->loadUserByID($userID);
$id_perusahaan=intval($_SESSION['id_perusahaan']) ; 
$sNamaPerus = $cUser->getNamaPerusahaan();
$id_level =  intval($_SESSION['id_level']);

//================fungsi fungsi umum web php koneksi dll   

/**
 * @brief membuat tampilan tabel dari query sql dengan Key dan link target tujuan
 * @param $sqlQuery Select dg kolom pertama adalah key yang hidden 
 * @param $sKey field key sekaligus nama keynya untuk post ke link target
 * @param $sLink tujuan url dengan POST nilai sKey
 */
function bikinTabelSQL2($sqlQuery,$sKey = null, $sLink = null) {
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
        if($iKolom == 0){
            if($sLink){
                $sKunci = $columnName ;
                $iKolom++;
                // next();
            }
        }else {
            $tableHTML .= '<th>'.$columnName.'</th>';
            $iKolom++;
        }
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
 */
function bikinOption($sqlQuery,$sTampil,$sp1="",$sTampil1="",$sp2="",$sTampil2="",$sp3="",$sTampil3=""){
  global $cUmum ;
  $result = $cUmum->ambilData($sqlQuery); 
  // Memeriksa apakah query berhasil dijalankan
  if ($result) {
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
        echo '<option value="' . $row['id'] . '">' . $row[$sTampil] .$sp1 . $row[$sTampil1] 
        . $sp2 . $row[$sTampil2] . $sp3 . $row[$sTampil3]  . '</option>';
      }
  } else { echo "Error: " . $sqlQuery . "<br>" . $cUmum->getPDO()->errorInfo()[2];}
}


function splashBerhasil($sPesan = "Berhasil", $sLinkRedirect=null, $iMillisSplash = 3339){
    if($sLinkRedirect == null){
        // $sLinkRedirect = "window.location.href";
        $sLinkRedirect = "history.go(-1)";
    } else {
        $sLinkRedirect = "window.location.href = '.$sLinkRedirect.'";
    }
    $sHTMLSplash = '<!DOCTYPE html> <head><style> .centered-message {
        position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);
        text-align: center;}</style> <script> setTimeout(function() { '.$sLinkRedirect.' ;
        }, '. intval($iMillisSplash) .'); </script>
    </head><body><div class="centered-message"> <h2>'.$sPesan.' </h2> </div>  </body></html> '; 
   die($sHTMLSplash);
}



?>