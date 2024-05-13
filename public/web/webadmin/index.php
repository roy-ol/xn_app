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
    require_once __DIR__ . '../../../../app/init_class.php';
    $myUser = new cUser();
    $userID = false;
    $error_message="Login page:";

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $userID = $myUser->loadUserHash($input_username,$input_password); 
    if($userID) {
      if($myUser->id_level() > 1){
        $error_message = "maaf hak akses anda tidak diperbolehkan menggunakan fitur ini"; 
      }else{
        $_SESSION['logged_in'] = true;
        $_SESSION['id_level'] = $myUser->id_level();
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] =$myUser->fullname();
        header("Location: dashboard.php"); // Redirect ke halaman dashboard setelah login berhasil
        exit;
      }
    } else {
      $error_message = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head> 
<!-- <script> 
    var userEmail = "email pengguna";
  
    alert(userEmail);
    console.log(userEmail);
    </script> -->
    <title>Halaman Login</title>
</head>
<body>
    <h2>Halaman Login</h2>

    <?php if (isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="login" value="Login">
    </form>
    <div id="pesan"></div>

<script> 
    var userEmail = "email pengguna";

    // Memeriksa apakah pengguna telah terautentikasi di browser
    if (navigator.credentials && navigator.credentials.get) {
      var credential = navigator.credentials.get({ type: 'email' });

      // Mengambil email dari kredensial yang ditemukan
      userEmail = credential && credential.id ? credential.id : "tidak ada";
    }

    // alert(userEmail);
    // console.log(userEmail);

  var pesanElement = document.getElementById("pesan");
  pesanElement.textContent = userEmail;
</script>
</body>
</html>
