<?php
// // Set umur sesi menjadi 30 menit
// $umur_session = 60 * 60 * 5; // detik x 60 x 5== 5 jam
// // $umur_session = 1800; // 30 * 60 (30 menit dalam detik)
// session_set_cookie_params($umur_session);
session_start();

// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { // cek login dan lanjut disini bila sudah login
  
  if(isset($_GET['kode'])){  // didapatkan dari setingan htaccess bareng di folder ini RewriteRule ^(.*)$ index.php?kode=$1 [L]
    // $urlRedirect = "/page/dashboard_home.php";
    $kodeApiFile = $_GET['kode'];    
    if(strlen($kodeApiFile) > 999){
      $kodeApiFile = substr($kodeApiFile, 0, 999);
    }
    $urlRedirect ="page/$kodeApiFile";      
    header("Location: $urlRedirect"); // Redirect ke halaman tujuan jika sudah login 
    exit;
  }

  header("Location: dashboard"); // Redirect ke halaman web/ dashboard jika sudah login 
  exit;
}

// Cek apakah form login telah disubmit
if (isset($_POST['login'])) { 
    // dari sini ada data post untuk diolah:
    // ==========================================
    require_once __DIR__ . '../../../app/init_class.php';
    $myUser = new cUser();
    $userID = false;
    $error_message="Login page:";

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $userID = $myUser->loadUserHash($input_username,$input_password); 
    if($userID) {
      if($myUser->id_level() == 13){  //sementara disini tidak dibatasi akses level
        $error_message = "maaf hak akses anda tidak diperbolehkan menggunakan fitur ini"; 
      }else{ 
        session_destroy();
        $umur_session = 60 * 60 * 5; // detik x 60 x 5 = 5 jam 
        session_set_cookie_params($umur_session);
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['id_level'] = $myUser->id_level();
        $_SESSION['id_perusahaan'] = $myUser->id_perusahaan();
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] =$myUser->fullname();
        //log user
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents("https://ipinfo.io/{$ip}?token=e669fbb04a257a");
        // $response = file_get_contents("https://ipinfo.io/{$ip}/json");
        echo $response;

        $sCookie = isset($_COOKIE['geo_info']) ? $_COOKIE['geo_info'] : '{}';
        $sCookieJson = urldecode($sCookie);
        $geoArray = json_decode($sCookieJson, true);        
        $geo_json = json_encode($geoArray, JSON_UNESCAPED_UNICODE);
        //cek data
        echo "<pre>";
        print_r([
          'metadata' => $geo_json,
          'lokasi' => $geoArray['city'] . ', ' . $geoArray['country'] ?? ''
        ]);
        echo "</pre>";
        //=========================================
        $myUser->logUser($userID,"login",$ip,1,$geo_json, $response);

        
        // Periksa apakah ada URL halaman yang disimpan
        if(isset($_SESSION['last_page'])) {
          $lastPage = $_SESSION['last_page'];
          unset($_SESSION['last_page']); // Hapus URL halaman terakhir dari session
          header('Location: ' . $lastPage); // Arahkan pengguna ke URL halaman terakhir
          exit;
        } else {
          // Jika tidak ada URL halaman yang disimpan, arahkan ke halaman default
          header("Location: dashboard"); // Redirect ke halaman dashboard setelah login berhasil
          exit;
        }

        exit;
      }
    } else { 
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Gagal</title>
            <style>
                .error-container {
                    max-width: 500px;
                    margin: 50px auto;
                    padding: 30px;
                    border-radius: 8px;
                    background-color: #f8d7da;
                    border: 1px solid #f5c6cb;
                    color: #721c24;
                    text-align: center;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .error-container h2 {
                    margin-bottom: 20px;
                }
                .error-container a {
                    color: #721c24;
                    font-weight: bold;
                    text-decoration: none;
                    border-bottom: 1px dotted #721c24;
                }
                .countdown {
                    font-size: 0.9em;
                    margin-top: 15px;
                    color: #856404;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h2>Username dan/atau password salah</h2>
                <p>Anda akan diarahkan ke halaman login dalam <span id="countdown">5</span> detik</p>
                <p>Atau <a href="login.php">klik di sini</a> untuk kembali sekarang</p>
            </div>
    
            <script>
                let seconds = 18;
                const countdownElement = document.getElementById("countdown");                
                const countdown = setInterval(function() {
                    seconds--;
                    countdownElement.textContent = seconds;                    
                    if(seconds <= 0) {
                        clearInterval(countdown);
                        window.history.back(); 
                    }
                }, 1000);
            </script>
        </body>
        </html>'; 
      // echo "<h2>Username(email) dan/atau password salah. <a href='login.php'> Login Ulang</a> <h2>"; 
      exit;
    }
}

header("Location: login.php"); // Redirect ke halaman  login 

?>