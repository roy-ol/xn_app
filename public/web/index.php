<?php
// Set umur sesi menjadi 30 menit
$umur_session = 1800; // 30 * 60 (30 menit dalam detik)
session_set_cookie_params($umur_session);
session_start();

// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php"); // Redirect ke halaman dashboard jika sudah login
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
        $_SESSION['logged_in'] = true;
        $_SESSION['id_level'] = $myUser->id_level();
        $_SESSION['id_perusahaan'] = $myUser->id_perusahaan();
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] =$myUser->fullname();
        header("Location: dashboard.php"); // Redirect ke halaman dashboard setelah login berhasil
        exit;
      }
    } else {
      echo "<h2>Username dan/atau password salah. <a href='login.php'> Login Ulang</a> <h2>"; 
      exit;
    }
}

header("Location: login.php"); // Redirect ke halaman  login 
?> 