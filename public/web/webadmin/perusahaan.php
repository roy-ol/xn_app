<?php  
require_once __DIR__ . '/menu.php';  
?>



<!DOCTYPE html>
<html>
<head>
    <title>Perusahaan</title> 
</head>
<body>
<?php 

$sURL_Action = "../fungsi/addPerusahaan"; 
?>
<form action="<?=$sURL_Action;?>" method="post">
    <table style="width: 50%;">
        <tr>
            <td style="width: 25%;"><label for="nama">Nama Perusahaan:</label></td>
            <td><input type="text" id="nama" name="nama" required></td>
        </tr>
        <tr>
            <td><label for="singkatan">Singkatan:</label></td>
            <td><input type="text" id="singkatan" name="singkatan"></td>
        </tr>
        <tr>
            <td><label for="alamat">Alamat:</label></td>
            <td><input type="text" id="alamat" name="alamat"></td>
        </tr>
        <tr>
            <td><label for="kota">Kota:</label></td>
            <td><input type="text" id="kota" name="kota"></td>
        </tr>
        <tr>
            <td><label for="telp">Telepon:</label></td>
            <td><input type="text" id="telp" name="telp"></td>
        </tr>
        <tr>
            <td><label for="dirut">Direktur Utama:</label></td>
            <td><input type="text" id="dirut" name="dirut"></td>
        </tr>
        <!-- Tanggal waktu dan edit akan otomatis di-generate oleh MySQL -->
        <!-- Tidak perlu dimasukkan melalui formulir -->

        <!-- Flag -->
        <input type="hidden" name="flag" value="0">
    </table>
    <br>
    <input type="submit" value="Submit">
</form>


<?php


echo "<br>List Perusahaan";
echo bikinTabelSQL("select * from perusahaan order by id desc");
?>

</body>
</html>
