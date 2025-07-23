<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  echo "<meta http-equiv='refresh' content='9; url=dashboard'>";
  echo "<h3>Status login masih aktif </h3><br> user : " . $_SESSION['username'] .
    "<br><H4>lanjut / dialihkan setelah 9 detik kembali ke <a href='dashboard'>dashboard</a>
    <br> atau <a href='logout.php'>Logout</a> untuk login ulang</h4>";
  exit;
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .login-container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.54);
        max-width: 300px;
        width: 90%;
    }
    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: calc(100% - 22px); /* Lebar input dikurangi jarak kanan */
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .login-container img {
        display: block; /* Membuat gambar menjadi blok */
        width: 100px; /* Customize the width of the logo */
        margin: 0 auto 18px; /* Margin atas dan bawah 20px, dan otomatis terletak di tengah */
    }
    .login-form input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .show-password {
        position: relative;
        cursor: pointer;
    }
    .show-password svg {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
    }
</style>
</head>
<body>
<div class="login-container">
    <img src="../images/xnlogo1.png" alt="Logo" />
    <form class="login-form" action="index.php" method="post">
        <input type="text" name="username" placeholder="Username/Email" required>
        <div class="show-password">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" onclick="togglePasswordVisibility()">
                <path fill-rule="evenodd" d="M8 0a7.988 7.988 0 0 0-7.48 5.303A2 2 0 0 0 0 8c0 1.103.897 2 2 2a1 1 0 0 0 1-1 1 1 0 0 1 1-1 2 2 0 0 0 1.695-.928C4.864 7.037 5.879 6 8 6c2.121 0 3.136 1.037 3.305 1.072A2 2 0 0 0 16 8a2 2 0 0 0-.521-1.368A7.988 7.988 0 0 0 8 0zM1 8a5.978 5.978 0 0 1 .281-1.713 8.034 8.034 0 0 0 1.25.648A6.013 6.013 0 0 1 8 5c1.208 0 2.322.35 3.468.935.445.223.881.472 1.282.736A5.978 5.978 0 0 1 15 8a5.978 5.978 0 0 1-.281 1.713 8.034 8.034 0 0 0-1.25-.648A6.013 6.013 0 0 1 8 11c-1.208 0-2.322-.35-3.468-.935a14.755 14.755 0 0 1-1.282-.736A5.978 5.978 0 0 1 1 8zm7 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            </svg>
        </div>
        <input type="submit" name="login" value="Login">
    </form>    
    <br>
    <p style="font-size: x-small; font-style: italic;">
    <a href="passsword_reset.php" title="Reset / Lupa Password?">
        🔐 lupa password</a></p>
 
</div>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }

 
fetch('http://ip-api.com/json/')
  .then(res => res.json())
  .then(data => {
    document.cookie = "geo_info=" + encodeURIComponent(JSON.stringify(data));
  });

 
</script>

</body>
</html>
