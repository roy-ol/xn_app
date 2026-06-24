<?php 
session_start();
 //=========== pause disini untuk simpan $_GET['kode'] ke session agar bisa diakses di halaman setelah login.php
$urlRedirect = "dashboard"; // default redirect ke dashboard bila tidak ada kode

if(isset($_GET['kode'])){  // didapatkan dari setingan htaccess bareng di folder ini RewriteRule ^(.*)$ index.php?kode=$1 [L]
  // $urlRedirect = "/page/dashboard_home.php";
  $kodeApiFile = $_GET['kode'];    
  if(strlen($kodeApiFile) > 999){
    $kodeApiFile = substr($kodeApiFile, 0, 999);
  }
  $urlRedirect ="page/$kodeApiFile";       
}else{
  if(isset($_SESSION['last_page'])) {
    $urlRedirect = $_SESSION['last_page'];
    }
} 
unset($_SESSION['last_page']); // Hapus URL halaman terakhir dari session 


// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { // cek login dan lanjut disini bila sudah login   
    header("Location: $urlRedirect"); // Redirect ke halaman tujuan jika sudah login 
    exit; 
}else { 
  // Simpan URL halaman permintaan saat ini
  $_SESSION['last_page'] = $urlRedirect;    
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
        $umur_session = 60 * 60 * 3; // detik x 60 x 3 = 3 jam 
        session_set_cookie_params($umur_session);
        session_start();
        $_SESSION['last_page'] = $urlRedirect; // simpan halaman terakhir yang diakses sebelum login
        $_SESSION['logged_in'] = true;
        $_SESSION['id_level'] = $myUser->id_level();
        $_SESSION['id_perusahaan'] = $myUser->id_perusahaan();
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] =$myUser->fullname(); 
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = getIpInfo($ip); 

        $sCookie = isset($_COOKIE['geo_info']) ? $_COOKIE['geo_info'] : '{}';
        $sCookieJson = urldecode($sCookie);
        $geoArray = json_decode($sCookieJson, true);        
        $geo_json = json_encode($geoArray, JSON_UNESCAPED_UNICODE); 
        $myUser->logUser($userID,"login",$ip,1, $response,$geo_json); 

        header("Location: $urlRedirect"); // Redirect ke halaman tujuan setelah login berhasil
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
                let seconds = 11;
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
}else {  
  header("Location: login.php"); // Redirect ke halaman  login 
  exit;
}


function getIpInfo($ip) {
    $token = 'e669fbb04a257a';
    $url = "https://ipinfo.io/{$ip}?token={$token}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}

?>