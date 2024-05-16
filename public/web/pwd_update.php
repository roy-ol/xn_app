<?php  
require_once __DIR__ . '/menu.php';  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input User</title> 
</head>
<body>
<?php 

$sURL_Action = "../web/fungsi/pwdUpdate"; // Sesuaikan dengan URL pengolahan data form
?>
<form action="<?=$sURL_Action;?>" method="post">
  <table>
      <tr>
          <td><label for="passwordLama">Password Lama:</label></td>
          <td><input type="text" id="passwordLama" name="passwordLama" required></td>
      </tr>
      <tr>
          <td><label for="passwordBaru">Password Baru:</label></td>
          <td><input type="text" id="passwordBaru" name="passwordBaru" required></td>
      </tr>
      <tr>
          <td><label for="konfirmasiPassword">Konfirmasi Password Baru:</label></td>
          <td><input type="text" id="konfirmasiPassword" name="konfirmasiPassword" required></td>
      </tr>
      <tr>
          <td colspan="2"><button type="submit">Simpan Perubahan</button></td>
      </tr>
  </table>
</form>
<label id="msg" style="color:red;"> *)</label>
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

</body>
</html>
</html>