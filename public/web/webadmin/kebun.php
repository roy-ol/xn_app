<?php  
require_once __DIR__ . '/menu.php';  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kebun</title>  
</head>
<body>
<?php 

$sURL_Action = "../fungsi/addKebun"; 
?>
<form action="<?=$sURL_Action;?>" method="post">
    <table style="width: 50%;">
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
            <td><label for="nama">Nama Kebun:</label></td>
            <td><input type="text" id="nama" name="nama" required></td>
        </tr>
        <tr>
            <td><label for="apikey">API Key:</label></td>
            <td><input type="text" id="apikey" name="apikey"></td>
        </tr>
        <tr>
            <td><label for="keterangan">Keterangan:</label></td>
            <td><input type="text" id="keterangan" name="keterangan" required></td>
        </tr>
        <tr>
            <td><label for="log_limit">Batas Log Data:</label></td>
            <td><input type="number" id="log_limit" name="log_limit" value="259200"></td>
        </tr>
        <!-- Tanggal waktu dan edit akan otomatis di-generate oleh MySQL -->
        <!-- Tidak perlu dimasukkan melalui formulir -->

        <!-- Flag -->
        <input type="hidden" name="flag" value="1">
    </table>
    <br>
    <input type="submit" value="Submit">
</form>

<?php

echo "<br>Daftar Kebun";
echo bikinTabelSQL("select * from kebun order by id desc");
?>

</body>
</html>