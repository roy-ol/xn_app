<?php
session_start();

// Hapus semua data sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Redirect ke halaman login setelah logout
header("Location: ./");   //kembali ke default file di folder yang sama
exit;
?>
