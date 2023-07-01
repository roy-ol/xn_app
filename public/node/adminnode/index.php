<?php
session_start(); // Memulai sesi

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

    $input_username = $_POST['username'];
    $input_password = $_POST['password'];
    $userID = $myUser->loadUserHash($input_username,$input_password); 
    if($userID) {
        $_SESSION['logged_in'] = true;
        $_SESSION['userID'] = $userID;
        $_SESSION['username'] =$myUser->fullname();
        header("Location: dashboard.php"); // Redirect ke halaman dashboard setelah login berhasil
        exit;
    } else {
        $error_message = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
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
</body>
</html>
