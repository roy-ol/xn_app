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

$sURL_Action = "../fungsi/addUser"; // Sesuaikan dengan URL pengolahan data form
?>
<form action="<?=$sURL_Action;?>" method="post">
    <table style="width: 50%;">
      <tr>
          <td style="width: 25%;"><label for="id_level">Level:</label></td>
          <td><select id="id_level" name="id_level">    
              <option value=0> - - - pilih id_level - - - </option>
              <?php 
                  $query = "SELECT id, nama FROM users_level order by id desc"; 
                  bikinOption($query, 3,"id","-","nama"); 
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
                    bikinOption($query, 0,"nama"); 
                ?>
                </select>          
            </td>
        </tr>
        <tr>
            <td><label for="username">Username:</label></td>
            <td><input type="text" id="username" name="username"></td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" id="email" name="email"></td>
        </tr>
        <tr>
            <td><label for="fullname">Nama Lengkap:</label></td>
            <td><input type="text" id="fullname" name="fullname"></td>
        </tr>
        <tr>
            <td><label for="pwd">Password:</label></td>
            <td><input type="text" id="pwd" name="pwd"></td>
        </tr>
        <tr>
            <td><label for="flag_active">Status Aktif:</label></td>
            <td>
                <select id="flag_active" name="flag_active">
                    <option value="1" selected>Aktif</option>
                    <option value="0">Diblokir</option>
                </select>
            </td>
        </tr> 
    </table>
    <br>
    <input type="submit" value="Submit">
</form>

<?php

echo "<br>List user";
$sSql = "select id,users.* from users order by id desc";
$sUrl = $_SERVER['PHP_SELF'];
echo bikinTabelSQL2($sSql,$sUrl);
?>

</body>
</html>