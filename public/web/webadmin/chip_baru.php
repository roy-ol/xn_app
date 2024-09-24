<?php  
require_once __DIR__ . '/menu.php';  
?>


<!DOCTYPE html>
<html>
<head>
    <title>Chip Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-tombol { 
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group label {
            width: 150px;
            text-align: right;
            margin-right: 10px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            flex: 1;
            height: 30px;
        }
        .form-group textarea {
        resize: vertical;
        overflow-y: auto;
    }
</style>     
</head>

<body>

<?php
 
$id_chip=0;
$id_kebun=0;
$chip="";
$id_tipe=0;
$keterangan="";
$versi=0;
$build=0;
$id_memo=0;
$sMemo = "";
if(isset($_GET['id_chip'])) {
    $id_chip = $_GET['id_chip'];
    $sSQL = "select * from chip where id= :id_chip" ;
    $param["id_chip"] = $id_chip; 
    $fetch = $cUmum->ambil1Row($sSQL,$param);
    if($fetch){
        $fetch = json_encode($fetch);
        $fetch = json_decode($fetch); //dijadikan object biar bisa akses pakai ->
        $id_kebun = $fetch->id_kebun;
        $chip = $fetch->chip;
        $id_tipe = $fetch->id_tipe;
        $keterangan = $fetch->keterangan;
        $versi = $fetch->versi;
        $build = $fetch->build;
        $id_memo = $fetch->id_memo;
        $sMemo = $cUmum->getMemo($id_memo);
        $sURL_Action = "../fungsi/updateChip"; 
        $act = "Simpan Perubahan";
    }

}else{
    $sURL_Action = "../fungsi/addChip"; 
    $act = "Tambahkan Chip";
}    
?>
    <h1>Modul system Chip XN </h1> 
    <form action="<?=$sURL_Action;?>" method="POST">
        <input type="hidden" name="id_chip" value=<?=$id_chip;?>>
        <input type="hidden" name="id_memo" value=<?=$id_memo;?>>        
        <div class="form-group">
          <label for="kebun">Kebun:</label>
          <select id="kebun" name="kebun">
          <option value=0> - - - pilih Kebun - - - </option>
            <?php 
            $query = "SELECT k.id, k.nama AS kebun,  p.nama AS perusahaan FROM kebun k 
                JOIN perusahaan p ON k.id_perusahaan = p.id"; 
            bikinOption($query, $id_kebun,"kebun"," ", "perusahaan"); 
            ?> 
          </select>
        </div>

        <div class="form-group">
            <label for="chip">Chip:</label>
            <input type="text" id="chip" name="chip" placeholder="Chip" value="<?=$chip;?>">
        </div>

        <div class="form-group">
          <label for="tipe">Tipe:</label>
          <select id="tipe" name="tipe" style="width: 100%"> 
          <option value=0>--- tipe chip ---</option>
            <?php 
            $query = "SELECT id, nama, kelompok, keterangan FROM tipe";
            bikinOption($query, $id_tipe,"nama"," ", "kelompok"," ", "keterangan"); 
            ?>   
          </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan:</label>
            <input type="text" id="keterangan" name="keterangan" placeholder="Keterangan" value="<?=$keterangan; ?>">
        </div>

        <div class="form-group">
            <label for="versi">Versi:</label>
            <input type="text" id="versi" name="versi" placeholder="Versi" value=<?=$versi; ?>>
        </div>

        <div class="form-group">
            <label for="build">Build:</label>
            <input type="text" id="build" name="build" placeholder="Build" value=<?=$build; ?>>

        </div>

        <div class="form-group">
            <label for="memo">Memo:</label>
            <textarea id="memo" name="memo" placeholder="Memo"><?=$sMemo; ?></textarea>
        </div>
        
        <div class="form-tombol">
        <button type="submit"><?=$act; ?></button>
      </div>
  </form>

<?php 
// echo bikinTabelSQL("select * from chip order by id desc limit 36");

$sSQL = "SELECT id as id_chip, chip.* FROM chip ORDER BY id DESC LIMIT 36";
$tabel = bikinTabelSQL2($sSQL,"");
echo "<br>Chip";
echo $tabel; 
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kebun').select2({
            placeholder: 'Pilih Kebun',
            allowClear: true, 
            maximumSelectionLength: 1
        });
        $('#tipe').select2({
            placeholder: 'tipe chip',
            allowClear: true, 
            maximumSelectionLength: 1
        });
    });
</script>



</body>
</html>