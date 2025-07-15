<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 

$sTitleFile = "Reset Password";
 
$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2><?=$sTitleFile ?></h2>
      <span class="text-muted"><?$sTitleFile ?></span>
      <div id="content_header"><?=$val1 ?></div>
    </div><!-- /.container-fluid -->
  </div>


  <div class="card">
      <div class="card-header">
        <h3 class="card-title">Card, <?=$sTitleFile ?></h3>
      </div>
      <div class="card-body">
        <form action="../../web/fungsi/pwdUpdate" method="post">
          <div class="form-group">
            <label for="old_password">Password Lama:</label>
            <input type="password" class="form-control" id="old_password" name="passwordLama" required>
          </div>
          <div class="form-group">
            <label for="new_password">Password Baru:</label>
            <input type="password" class="form-control" id="new_password" name="passwordBaru" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Konfirmasi Password Baru:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>

          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>      
      </div>
    <label id="msg" style="color:red;"> *)</label>
    </div>
  </div>
</div>

<script> 
  document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
      const new_password = document.getElementById("new_password").value;
      const confirm_password = document.getElementById("confirm_password").value;
      
      if (new_password !== confirm_password) {
        alert("Password baru dan konfirmasi password tidak cocok.");
        document.getElementById("msg").textContent = "Password baru dan konfirmasi password tidak cocok.";
        event.preventDefault(); // Mencegah formulir dari pengiriman
      }
    });
  });
  
</script>