<!DOCTYPE html>
<html>
<head>
    <title>Pencarian</title>
</head>

<?php  
require_once __DIR__ . '/menu.php'; 
$sPencarian="";
if(isset($_POST['kataKunci'])) {
    $kataKunci = $_POST['kataKunci'];
    $keyID = 0;
    if(is_numeric($kataKunci))$keyID = $kataKunci;  
    $sSQL="SELECT n.id,n.id NodeID,n.nama Node,c.id ChipID, chip,c.keterangan Ket_Chip
    ,c.id_kebun, k.nama kebun,p.nama perusahaan
    FROM chip c 
    LEFT JOIN node n ON n.id_chip = c.id
    LEFT JOIN kebun k ON k.id=c.id_kebun
    LEFT JOIN perusahaan p ON p.id=k.id_perusahaan
    WHERE c.chip LIKE '%$kataKunci%' OR n.id=$keyID
    OR c.keterangan like '&$kataKunci&' OR n.nama like '%$kataKunci%' ";
    $sTabel = bikinTabelSQL2($sSQL, "#");
    // echo $sSQL . "<br>";
    $sPencarian = "Hasil pencarian untuk: " . htmlspecialchars($kataKunci);
}


?>

<body style="font-family: Arial, sans-serif; text-align: center; padding: 20px;">

    <h2>Search / Pencarian</h2>

    <form method="post" action="#">
        <input type="text" name="kataKunci" placeholder="Masukkan kata kunci..." required style="padding: 8px;">
        <button type="submit" style="padding: 8px;">Cari</button>
    </form>

    <div style="margin-top: 20px;">
        <strong><?php echo $sPencarian; ?></strong>
    </div>

    <?php echo $sTabel; ?>

</body>
</html>
