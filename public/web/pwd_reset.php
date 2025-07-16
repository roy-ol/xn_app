<?php  
require_once __DIR__ . '../../../app/init_class.php';
require_once __DIR__ . '../../../app/fsambungan.php';

$cUmum = new cUmum();
$cUser = new cUser(); 
  
$error = '';
$success = '';
$sUserName = '';
$sTitleFile = 'Paword baru / reset';
$sPesan="";
$token = '';

// $iHasil = $cUser->eksekusi($stmt,[$email, $token, $expires_at]); 
// buat pengecekan validasi token masuk
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $token = $_GET['token'];     
  //cek validasi token
  if ($token) {
    $sSQL = "SELECT u.username FROM users u INNER JOIN password_resets pr 
    ON u.email = pr.email WHERE pr.token = ? AND pr.expires_at >= NOW()";
    $sUserName = $cUser->ambil1Data($sSQL,[$token]); 
    if (strlen($sUserName) < 2) {  
      tampilDanKeluar('1 Token tidak valid / sudah kadaluarsa');
    }    
  }else{
    tampilDanKeluar(' Token tidak valid ');
  }     
}else if( $_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['token'];
  $sPassword = $_POST['passwordBaru'];
  $sKonfirmasi = $_POST['konfirmasiPassword'];
  if ($sPassword !== $sKonfirmasi) {
    $error = 'Password dan Konfirmasi tidak cocok';
  }
  if (strlen($sPassword) < 4) {
    $error = 'Password minimal 4 karakter';
  }
  if($token == '') {
    tampilDanKeluar('0 Token tidak valid');
  } 
  $sSQL = "SELECT u.id, u.username, u.email FROM users u INNER JOIN password_resets pr 
  ON u.email = pr.email WHERE pr.token = :token AND pr.expires_at >= NOW()";
  $param = ["token" => $token];
  $rowHasil = $cUser->ambil1Row($sSQL,$param,PDO::FETCH_OBJ) ;  //ambil id dan pwd
  if( $rowHasil->id < 2) {
    tampilDanKeluar('user / Token tidak valid');
  }
  $hasilID = $rowHasil->id;   
  // echo $sPassword . " - " . $hasilID . " - " . $rowHasil->username . " - " . $rowHasil->email . "<br>";
  $cUser->loadUserByID($hasilID); 
  if($error == '') {
    $iHasil = $cUser->updatePassword($sPassword); 
    if ($iHasil > 0) {
      //update expires_at agar tidak bisa diakses lagi
      $sSQL = "UPDATE password_resets SET expires_at = NOW() WHERE token = :token";
      $param = ["token" => $token];
      $token = '';
      $success = $iHasil . ' Password berhasil direset';
      $success .= "'); window.location.href = '../web/'; alert('OK Login"; 
      $cUser->eksekusi($sSQL,$param);
      tampilDanKeluar($success);
    }
  }

}

// echo "<script>alert('$success \n $token'); window.location.href = '../web/';</script>";

//================================================================================
//=========================== bagian html ========================================
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>🔐 Reset Password akun <?=$sUserName?></h4>
        </div>
        <div class="card-body">
          <div id="msg" class="alert alert-danger d-none"></div>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
          <?php endif;   
          ?>
          <form method="POST">
            <input type="hidden" name="token" value="<?=$token;?>">             
            <div class="mb-3">
              <label for="passwordBaru">Password Baru:</label>
              <input type="password" id="passwordBaru" name="passwordBaru" required class="form-control">
            </div>
            <div class="mb-3">
              <label for="konfirmasiPassword">Konfirmasi Password:</label>
              <input type="password" id="konfirmasiPassword" name="konfirmasiPassword" required class="form-control">
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary w-50">Simpan Password</button>
            </div>
          </form>
        </div>
        <div class="card-footer text-muted text-center">
          Jika Anda tidak membuat permintaan ini, silakan abaikan halaman ini.
        </div>
      </div>
    </div>
  </div>
</div>

<script> 
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const msgDiv = document.getElementById("msg");

    form.addEventListener("submit", function(event) {
        const passwordBaru = document.getElementById("passwordBaru").value;
        const konfirmasiPassword = document.getElementById("konfirmasiPassword").value;
        
        if (passwordBaru !== konfirmasiPassword) {
            msgDiv.textContent = "Password Baru dan Konfirmasi Password tidak cocok.";
            msgDiv.classList.remove("d-none");
            event.preventDefault(); // Mencegah pengiriman
        } else {
            msgDiv.classList.add("d-none"); // Sembunyikan jika cocok
        }
    });
});

</script>
<?php

function tampilDanKeluar($sPesanTambahan="") {
  echo "<!DOCTYPE html>
    <html><head><title>Token Error</title></head>
    <body><h3 style='color:red; text-align:center'>$sPesanTambahan</h3></body>
    <script>alert('$sPesanTambahan');
    </script>
    </html>";
  exit;
  // <script>alert('$sPesanTambahan'); window.location.href = '../web/';</script>
}
?>
