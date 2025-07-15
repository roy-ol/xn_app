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
      echo "<script>alert('1 Token tidak valid\n$token'); window.location.href = '../web/';</script>";
      exit;
    }    
  }else{
    echo "<script>alert('isi Token tidak valid'); window.location.href = '../web/';</script>";
    exit;
  }     
}else if( $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $sPassword = $_POST['passwordBaru'];
    $sSQL = "SELECT u.id, u.username, u.email FROM users u INNER JOIN password_resets pr 
    ON u.email = pr.email WHERE pr.token = :token AND pr.expires_at >= NOW()";
    $param = ["token" => $token];
    $rowHasil = $cUser->ambil1Row($sSQL,$param,PDO::FETCH_OBJ) ;  //ambil id dan pwd
    if( $rowHasil->id < 2) {
      echo "<script>alert('Token tidak valid, ID < 2'); window.location.href = '../web/';</script>";
      exit;
    }
    $hasilID = $rowHasil->id;  //verifikasi hasil hash pwd dg inputan 
    // echo $sPassword . " - " . $hasilID . " - " . $rowHasil->username . " - " . $rowHasil->email . "<br>";
    $cUser->loadUserByID($hasilID); 
    $iHasil = $cUser->updatePassword($sPassword); 
    if ($iHasil > 0) {
      $success = $iHasil . ' Password berhasil diubah';
      $token = '';
    }

}
echo "<script>alert('$success \n $token'); window.location.href = '../web/';</script>";

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
    form.addEventListener("submit", function(event) {
        const passwordBaru = document.getElementById("passwordBaru").value;
        const konfirmasiPassword = document.getElementById("konfirmasiPassword").value;
        
        if (passwordBaru !== konfirmasiPassword) {
            document.getElementById("msg").textContent = "Password baru dan konfirmasi password tidak cocok.";
            event.preventDefault(); // Mencegah formulir dari pengiriman
        }
    });
});
</script>
