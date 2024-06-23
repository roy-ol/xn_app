<!DOCTYPE html>
<html>

<head>
  <title>Chip Log</title>
</head>

<body>
  <h2>Logging: Update / req Flag to Chip</h2>
  <?php  
require_once __DIR__ . '/menu.php';   

$id = 0 ;
$id_Chip =  0; 
$chip = "";
$keterangan = "";
$kebun = "";
$ketkebun = "";
$memo = "";
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $id_chip = $id;
    $sSQL = "SELECT c.id, c.chip,c.keterangan, k.nama kebun, CONCAT(k.keterangan,' / ',p.nama) ketkebun, m.memo 
    FROM chip c LEFT JOIN memo m ON m.id = c.id_memo JOIN kebun k ON k.id = c.id_kebun 
    JOIN perusahaan p ON p.id = k.id_perusahaan  
    where c.id= :id" ;
    $param["id"] = $id; 
    $hasil = $cUmum->ambil1Row($sSQL,$param);
    if($hasil){
        $hasil = json_encode($hasil);
        $hasil = json_decode($hasil); //dijadikan object biar bisa akses pakai ->

        $id_chip =$hasil->id;
        $chip = $hasil->chip;
        $keterangan = $hasil->keterangan;
        $kebun = $hasil->kebun; 
        $ketkebun = $hasil->ketkebun;
        $memo = $hasil->memo;
    } 
} 
if(isset($_POST['flag'])) {
    $flag =$_POST['flag'];
    $id =$_POST['id'];
    if($id > 0) {      
      $sSQL = "UPDATE chip SET flag=:flag WHERE id=:id";
      $param = array('flag' => $flag, 'id' => $id);
      $cUmum->eksekusi($sSQL, $param);
  }
}


?>
  <table style="width: 50%;">
    <tr>
      <form action="?" method="GET">
        <td style="width: 10%;"><label for="id">Chip:</label></td>
        <td><select id="id" name="id" onchange="this.form.submit();">
            <option value=0> - - - pilih chip - - - </option>
            <?php 
                    $query = "SELECT id,chip,keterangan FROM chip order by id desc"; 
                    bikinOption($query, $id,"chip"," ", "keterangan"); 
                ?>
          </select>
        </td>
      </form>
    </tr>
    <tr>
      <td style="width: 10%;"><label for="Chip">Chip:</label></td>
      <td> <?=$chip;?></td>
    </tr>

    <tr>
      <td style="width: 10%;"><label for="keterangan">Keterangan:</label></td>
      <td> <?=$keterangan;?></td>
    </tr>
    <tr>
      <td style="width: 10%;"><label for="kebun">Kebun:</label></td>
      <td> <?=$kebun;?> </td>
    </tr>
    <tr>
      <td style="width: 10%;"><label for="ketkebun">Ket / Perusahaan:</label></td>
      <td> <?=$ketkebun;?></td>
    </tr>
    <tr>
      <td style="width: 10%;"><label for="memo">Chip Memo:</label></td>
      <td><?=$memo;?></td>
    </tr>
    <tr>
      <td>Flag to Chip:</td>
      <td>
        <form action="?" method="post">
          <input type="hidden" name="id" value="<?=$id?>">
          <select name="flag" id="flag">
            <option value=0>0 none</option>
            <option value=10 selected>10 = req JsonSetting</option>
          </select>
          <input type="submit" value="Submit">
        </form>
      </td>
    </tr>
  </table>
  <br> <br>

  Antrian Chip Log:
  <?php
$sSQL = "SELECT c.* 
FROM chip c WHERE c.flag > 0
ORDER BY c.id DESC LIMIT 9; ";
$tabel = bikinTabelSQL2($sSQL,""); 
echo $tabel; 

echo "<br><br>";
$sSQL = "SELECT c.id,c.chip,c.keterangan,cl.keterangan,cl.waktu , m.memo setting_json FROM chip_log cl JOIN chip c ON c.id = cl.id_chip JOIN memo m ON m.id = cl.id_memo ORDER BY cl.id DESC LIMIT 36; ";
$tabel = bikinTabelSQL2($sSQL,""); 
echo $tabel; 
?>



</body>

</html>