<?php  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload
require 'vendor/autoload.php';

require_once __DIR__ . '../../../app/init_class.php';
require_once __DIR__ . '../../../app/fsambungan.php';

$cUmum = new cUmum();
$cUser = new cUser(); 
 
$reset_link='';
$error = '';
$success = '';
$sTitleFile = 'Reset Password';
$sPesan="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validasi email
    if (empty($email)) {
        $error = 'Email harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // Cek apakah email terdaftar
        $stmt = "SELECT id FROM users WHERE email = ?";        
        $user = $cUser->ambil1Data($stmt,[$email]);
        
        if ($user) {
            // Generate token unik
            $token = bin2hex(random_bytes(50));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Simpan token ke database
            $stmt = "INSERT INTO password_resets (email, token, expires_at) 
            VALUES (?, ?, ?)";
            $iHasil = $cUser->eksekusi($stmt,[$email, $token, $expires_at]); 
            
            // Kirim email dengan link reset (implementasi ini tergantung email service Anda)
            $reset_link = "https://xn.online-farm.com/web/pwd_reset.php?token=$token";
            
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = MAIL_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USERNAME; // email hosting
                $mail->Password   = MAIL_PASSWORD; // isi password email kamu
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // pakai SSL
                $mail->Port       = MAIL_PORT;

                $mail->setFrom('info@online-farm.com', 'Online Farm');
                $mail->addAddress($email);
                $mail->Subject = 'Reset Password Online Farm';
                $mail->isHTML(true);
                $mail->Body = "<h2>Reset Password Online Farm</h2>
                  <p>ada perminatan untuk mereset password pada akun online-farm Anda. Silahkan klik link berikut untuk mereset password Anda:</p>
                  <a href='$reset_link'>Reset Password</a>
                  <p>Kalau anda tidak membuat permintaan reset password, abaikan email ini.</p>" ;  

                $mail->send();
                $success = "<p style='color:green'>✅ Email berhasil dikirim ke $email </p>";
            } catch (Exception $e) {
                $error = "<p style='color:red'>❌ Email gagal dikirim. Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            $error = 'Email tidak terdaftar';
        }
    }
} 
//================================================================================
//=========================== bagian html ========================================
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>🔐 Reset Password Online Farm</h4>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label for="email" class="form-label">Alamat Email</label>
              <input type="email" name="email" id="email" class="form-control" required placeholder="contoh@email.com">
            </div>
            <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
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
</script>
