<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Selamat datang di Halaman Dashboard!</h2>

    <p>Ini adalah halaman yang hanya dapat diakses setelah login.</p>

    <h3>Informasi Pengguna:</h3>
    <p>Username: <?php echo $_SESSION['username']; ?></p>

    <a href="logout.php">Logout</a>
</body>
</html>
