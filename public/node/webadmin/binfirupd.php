<!DOCTYPE html>
<html>
<head>
    <title>update firmware binary</title>
</head>

<h2>Binary Firmware Update</h2>
<?php require_once __DIR__ . '/menu.php'; // berisi juga tag <body> 


//======untuk submit2 = assign repo ke chip / ada update untuk chip=============
//==============================================================================
if(isset($_POST['assign_repo'])){
  $idChip = $_POST['idChip'];
  $idRepo = $_POST['idRepo'];
  if($idChip > 0 && $idRepo > 0) {
    $sSQL = "update chip set id_repo = :idRepo , flag=7 where id = :idChip ";
    $param['idRepo'] = $idRepo ;
    $param['idChip'] = $idChip ;
    $cUmum ->eksekusi($sSQL,$param);
    unset($param);
  } else{
    echo "pilih chip dan reponya";
  }
}

?>
<br><h3>Assign repo to chip</h3>

<form action="?" method="post" enctype="multipart/form-data">
  <div class="form-group">
      ID Chip
      <select id="idChip" name="idChip">
        <option value=0> - - - pilih Chip - - - - </option>;
        <?php 
          $query = "select id,chip,keterangan from chip "; 
          bikinOption($query,0,"chip"," - ","keterangan"); 
        ?>
      </select>
  </div>
  <div class="form-group">
      ID Repo
      <select id="idRepo" name="idRepo">
        <option value=0> - - - - - - - pilih binary firmware update - - - - - - </option>;
        <?php 
          $query = "SELECT id,file_repo,build,left(keterangan,36) as keterangan from binfirupd "; 
          bikinOption($query,0,"file_repo"," (","build",") ","keterangan"); 
        ?>
      </select>
  </div>
  <input type="submit" value="Submit Update" name="assign_repo">
</form>
<?php 
$sSQL = "SELECT c.id, c.chip, c.keterangan,c.flag, c.updated, c.build, b.build as repo_build,b.updated upd, 
    b.file_repo,b.keterangan as ket from chip c LEFT JOIN binfirupd b on c.id_repo = b.id";
$tabel = bikinTabelSQL($sSQL);
echo "<br>Chip Repo<br>";
echo $tabel; 
?>

 

<br><h3>Upload File Binary Update :</h3>
<?php 
$targetDirectory = __DIR__ . '/../../repo/';  
if(1 == 0 ) $cUmum = new cUmum();
if(isset($_POST['timestamp']) && isset($_POST['submit'])){
  // Mendapatkan data timestamp dari form
  $timestamp = $_POST['timestamp'];  
  $versi = $_POST['versi'];
  $id_tipe = $_POST['id_tipe'];
  $keterangan = $_POST['keterangan'];
  if($id_tipe == 0){
    echo "pilih tipe peruntukan firmware";
    $uploadOk = 3;
  }

  $namaFileRepo = basename($_FILES['fileToUpload']['name']); 
  $targetFile = $targetDirectory . $namaFileRepo ;
  $uploadOk = 1;

  // Cek apakah file sudah ada
  if (file_exists($targetFile)) {
    if(isset($_POST['replace_file']) && $_POST['replace_file'] === 'on') {
      echo "<h3>file lama dengan nama yang sama replace = .". $_POST['replace_file'] ."</h3>"; 
    }else{
      echo "<h3>File sudah ada.</h3>";
      $uploadOk = 2;
    }
  }

  // Batasi jenis file yang diizinkan (contoh: hanya bin)
  $allowedExtensions = array('bin');
  $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
  if (!in_array($fileExtension, $allowedExtensions)) {
    echo "Hanya file binfirmware yang diizinkan.";
    $uploadOk = 0;
  }

  // Batasi ukuran file (contoh: maksimum 1MB)
  $maxFileSize = 1 * 1024 * 1024;
  if ($_FILES['fileToUpload']['size'] > $maxFileSize) {
    echo "Ukuran file terlalu besar.";
    $uploadOk = 0;
  }
    
  // Jika semua pengecekan berhasil, upload file
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
      echo "File berhasil diunggah.";
      $uploadOk = 9 ; // status 9 upload file berhasil
    } else {
      echo "Terjadi kesalahan saat mengunggah file.";
    }
  }elseif($uploadOk !== 9){
    echo "<h3>ada kesalahan proses / data, periksa entri</h3>";
  }

  if($uploadOk === 9){     
    $sSQL = "INSERT INTO binfirupd(file_repo, build, versi, id_tipe, keterangan) 
      VALUES (:namaFileRepo, :nilaiTimestamp,:versi,:id_tipe, :keterangan) 
      ON DUPLICATE KEY 
      UPDATE build = :nilaiTimestamp, keterangan = :keterangan,
          versi= :versi, id_tipe = :id_tipe";
    $param['namaFileRepo'] = substr($namaFileRepo, 0, -4) ;
    //====nilai menit diamankan agar jamngan sampai tabel repo build nya lebih besar dari XNBuild di firmware sebenarnya
    $param['nilaiTimestamp']= $timestamp - 2 ; //penyesuaian menit build vs proses penulisan file asumsi menit waktu dibutuhkan untuk tulis file bin oleh arduino IDE dari nilai build
    $param['versi']=$versi ; 
    $param['id_tipe']=$id_tipe ; 
    $param['keterangan']=$keterangan ; 

      // VALUES ('$namaFileRepo', $timestamp,$versi,$id_tipe, '$keterangan', 0) 
      // ON DUPLICATE KEY UPDATE build = $timestamp, keterangan ='$keterangan' ";
    $cUmum ->eksekusi($sSQL,$param);
  }

  // if($idchip > 0){
  //   unset($param);
  //   $sSQL = "update chip set id_repo = (select id from binfirupd where file_repo='binfir.bin') where id=3 ";
    
  // }
}
?>  

<style>
    .form-group {
        display: block;
        margin-bottom: 10px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    
    /* Gaya CSS untuk input "timestamp" */
    #timestamp {
        display: inline-block;
        width: auto;
        white-space: nowrap;
    }
    
    /* Mengatur lebar textarea secara responsif */
    .form-group textarea {
        width: 100%; /* Gunakan persentase atau vw sesuai preferensi */
        max-width: 100%; /* Optional: untuk memastikan textarea tidak melebar lebih dari lebar layar */
        padding: 8px; /* Optional: tambahkan padding sesuai kebutuhan */
        box-sizing: border-box; /* Pastikan padding tidak mempengaruhi lebar total */
    }
</style>


<form action="?" method="post" enctype="multipart/form-data">
  <div class="form-group">
      <label for="fileToUpload">Pilih File:</label>
      <input type="file" name="fileToUpload" id="fileToUpload" required> 
      <input type="checkbox" name="replace_file" > Replace file
    </div>
    
    <div class="form-group">Tipe
    <select id="id_tipe" name="id_tipe">
      <option value=0> - - - pilih tipe - - - </option>
      <?php 
        $query = "select id,nama,keterangan from tipe "; 
        bikinOption($query,0,"nama"," ","keterangan"); 
      ?>
    </select>
    </div>
    <div class="form-group">
    XN_build 
    <input type="number" name="timestamp" id="timestamp" style="width: 100px; text-align: center;" > 
    &nbsp; Versi <input type="number" name="versi" id="versi" value="1" style="width: 45px; text-align: center;">       
  </div>
  
  <div class="form-group">
    <label for="keterangan">Keterangan:</label>
    <textarea name="keterangan" id="keterangan" rows="2" required></textarea>
  </div> 
    <input type="submit" value="Upload dan Submit" name="submit">
</form>


<?php
$sSQL = "select b.id,b.file_repo,b.build,b.versi as Ver, concat(t.nama,' = ',t.keterangan) as tipe, 
  b.keterangan,b.flag,b.created,b.updated from binfirupd b, tipe t where b.id_tipe = t.id order by b.id desc";
$tabel = bikinTabelSQL($sSQL);
echo "<br>Tabel Binfirupd<br>";
echo $tabel; 


$files = scandir($targetDirectory); 
echo "<h3>Binary Firmware  Folder Repo : </h3>"; 
foreach ($files as $file) {
  if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'bin') {
    $filePath = $targetDirectory . '/' . $file;
    $modifiedTime = date("Y-m-d H:i:s", filemtime($filePath));
    echo "$file -- $modifiedTime <br>"; 
  }
} 
?>


<script>
    document.getElementById('fileToUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            const timestamp = file.lastModified; // Mengambil timestamp terakhir kali dimodifikasi
            const date = new Date(timestamp);

            const year = String(date.getFullYear()).substr(-2); // Ambil 2 digit terakhir tahun
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Januari dimulai dari 0
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            const formattedTimestamp = `${year}${month}${day}${hours}${minutes}`;
            document.getElementById('timestamp').value = formattedTimestamp;
        }
    });
</script>
</body>
</html>
