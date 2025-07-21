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
$id_user = 0;
$sURL_Action = "../fungsi/addUser"; // Sesuaikan dengan URL pengolahan data form
$username ="";
$email ="";
$fullname ="";
$id_level = 3;
$id_perusahaan = 0;
$flag_active = 1;    
//   $param["email"] = $data->email;
//   $param["fullname"] = $data->fullname;
//   $param["flag_active"] = intval($data->flag_active); 
 
if(isset($_GET['id'])) {
  $id_user = $_GET['id'];
  $sURL_Action = "../fungsi/updateUser";  
  $sSQL = "select * from users where id= :id" ;
  $param["id"] = $id_user; 
  $fetch = $cUmum->ambil1Row($sSQL,$param);
  if($fetch){
    $fetch = json_encode($fetch);
    $fetch = json_decode($fetch); //dijadikan object biar bisa akses pakai ->
    $username = $fetch->username;
    $email = $fetch->email;
    $fullname = $fetch->fullname;
    $iIdLevel = $fetch->id_level;
    $id_perusahaan = $fetch->id_perusahaan;
    $flag_active = $fetch->flag_active;
  }
}
?>
<form action="<?=$sURL_Action;?>" method="post">
    <input type="hidden" name="id_user" value="<?=$id_user?>">
    <table style="width: 50%;">
      <tr>
          <td style="width: 25%;"><label for="id_level">Level:</label></td>
          <td><select id="id_level" name="id_level">    
              <option value=0> - - - pilih id_level - - - </option>
              <?php 
                  $query = "SELECT id, nama FROM users_level order by id desc"; 
                  bikinOption($query, $id_level,"id","-","nama"); 
              ?>
              </select>          
          </td>
      </tr>
        <tr>
            <td style="width: 25%;"><label for="id_perusahaan">Perusahaan:</label></td>
            <td><select id="id_perusahaan" name="id_perusahaan">    
                <option value=0> - - - pilih perusahaan - - - </option>
                <?php 
                    $query = "SELECT id,nama FROM perusahaan order by id desc"; 
                    bikinOption($query, $id_perusahaan,"nama"); 
                ?>
                </select>          
            </td>
        </tr>
        <tr>
            <td><label for="username">Username:</label></td>
            <td><input type="text" id="username" name="username" value="<?=$username?>"></td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" id="email" name="email" value="<?=$email?>"></td>
        </tr>
        <tr>
            <td><label for="fullname">Nama Lengkap:</label></td>
            <td><input type="text" id="fullname" name="fullname" value="<?=$fullname?>"></td>
        </tr>
        <tr>
            <td><label for="pwd">Password:</label></td>
            <td><input type="text" id="pwd" name="pwd"></td>
        </tr>
        <tr>
            <td><label for="flag_active">Status Aktif:</label></td>
            <td>
                <select id="flag_active" name="flag_active">
                    <option value="1" <?= ($flag_active == 1) ? 'selected' : ''?>>Aktif</option>
                    <option value="0" <?= ($flag_active == 0) ? 'selected' : ''?>>Diblokir</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <button type="submit" class="btn btn-success">Simpan</button>
            </td>
        </tr>
    </table>
    <br>
    <button type="button" class="btn btn-primary" onclick="newUser()">New</button>  
</form>

<?php

echo "<br>List user";
$sSql = "select id,users.* from users order by id desc";
$sUrl = $_SERVER['PHP_SELF'];
echo bikinTabelSQL2($sSql,$sUrl);
?>

</body>
<script>
    function newUser() {
        window.location.href = "user.php";
    }
</script>
</html>