<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 

$sTitleFile = "Master Bahan Nutrisi";
 
$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();

// Handle form submission for nut_bahan

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_nut_bahan'])){
  global $cUmum;
  $nama = trim($_POST['nama'] ?? '');
  $generic_id = trim($_POST['generic_id'] ?? ''); 
  $kelarutan = ($_POST['kelarutan'] === '' || !isset($_POST['kelarutan'])) ? null : floatval($_POST['kelarutan']);
  $harga_per_kg = ($_POST['harga_per_kg'] === '' || !isset($_POST['harga_per_kg'])) ? null : intval($_POST['harga_per_kg']);
  $deskripsi = trim($_POST['deskripsi'] ?? '');

  // Jika ada id -> lakukan update, selain itu insert
  $edit_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  if($edit_id > 0){
    $sql = "UPDATE nut_bahan SET nama=:nama, generic_id=:generic_id,  
            kelarutan=:kelarutan, harga_per_kg=:harga_per_kg, deskripsi=:deskripsi, updated=CURRENT_TIMESTAMP()
            WHERE id = :id";
    $params = [
      ':nama' => $nama,
      ':generic_id' => $generic_id, 
      ':kelarutan' => $kelarutan,
      ':harga_per_kg' => $harga_per_kg,
      ':deskripsi' => $deskripsi,
      ':id' => $edit_id
    ];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsg = '<div class="alert alert-success">Data berhasil diperbarui.</div>';
      $val1 = ''; // reset form setelah simpan
    } else {
      $sMsg = '<div class="alert alert-danger">Gagal memperbarui data.</div>';
    }
  } else {
    $sql = "INSERT INTO nut_bahan (nama, generic_id, kelarutan, harga_per_kg, deskripsi) 
            VALUES (:nama, :generic_id,   :kelarutan, :harga_per_kg, :deskripsi)";
    $params = [
      ':nama' => $nama,
      ':generic_id' => $generic_id, 
      ':kelarutan' => $kelarutan,
      ':harga_per_kg' => $harga_per_kg,
      ':deskripsi' => $deskripsi
    ];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected){
      $sMsg = '<div class="alert alert-success">Data berhasil disimpan.</div>';
      $val1 = ''; // reset form setelah simpan
    } else {
      $sMsg = '<div class="alert alert-danger">Gagal menyimpan data.</div>';
    }
  }
}
 
// Jika ada param nbid di link
$is_edit = false;
$edit_id = 0;
if($val1 == "nbid") {
  $edit_id = $val2; 
} 
$nama_val = '';
$generic_val = ''; 
$kelarutan_val = '';
$harga_val = '';
$deskripsi_val = '';
 
if($edit_id > 0){
  $row = $cUmum->ambil1Row("SELECT * FROM nut_bahan WHERE id = :id", [':id'=>$edit_id]);
  if($row){
    $is_edit = true;
    $nama_val = $row['nama'];
    $generic_val = $row['generic_id']; 
    $kelarutan_val = $row['kelarutan'];
    $harga_val = $row['harga_per_kg'];
    $deskripsi_val = $row['deskripsi'];
  }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <h2><?=$sTitleFile ?></h2>
      <span class="text-muted"><?$sTitleFile ?></span>
      <div id="content_header"><?=$deskripsi_val ?></div>
      <?php if(isset($sMsg)) echo $sMsg; ?>
    </div><!-- /.container-fluid -->
  </div>

  <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?=$sTitleFile ?></h3>
      </div>
      <div class="card-body">
        <h5><?php echo $is_edit ? "Edit Bahan Nutrisi" : "Entri Bahan Nutrisi Baru"; ?></h5>
        <!-- Form entri bahan nutrisi -->
        <form method="post" class="mb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required maxlength="50" value="<?=htmlspecialchars($nama_val)?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Generic Kimia</label>
                <select id="generic_id" name="generic_id" class="form-control" onchange="generic_terpilih(this.value)">
                  <option value="0">- - - Generic bahan - - -</option>
                  <?php  
                  $sSqlOp2 = "SELECT  id,nama,rumus_kimia FROM nut_generic ORDER BY nama ASC";
                  bikinOption($sSqlOp2, $generic_val, "nama", " - ", "rumus_kimia");
                  ?>
                </select> 
              </div>
            </div> 
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Kelarutan</label>
                <input type="number" step="any" name="kelarutan" class="form-control" value="<?=htmlspecialchars($kelarutan_val)?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Harga Per Kg (IDR)</label>
                <input type="number" name="harga_per_kg" class="form-control" value="<?=htmlspecialchars($harga_val)?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2"><?=htmlspecialchars($deskripsi_val)?></textarea>
              </div>
            </div>
          </div>

          <div class="form-group">
            <?php if($is_edit): ?>
              <input type="hidden" name="id" value="<?=$edit_id?>">
              <a href="nd_bahan_nutrisi" class="btn btn-secondary">Batal</a>
              <button type="submit" name="save_nut_bahan" class="btn btn-success">Update</button>
            <?php else: ?>
              <button type="submit" name="save_nut_bahan" class="btn btn-primary">Simpan Bahan</button>
            <?php endif; ?>
          </div>
        </form>

        <table id="tbl" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT b.id nbid,b.Nama,g.nama Generic,g.rumus_kimia Kode,b.kelarutan Kelarutan,b.harga_per_kg Harga FROM nut_bahan b
            left join nut_generic g on b.generic_id = g.id
            ORDER BY b.id DESC 
            LIMIT 50;" ;  
          // $sHitTabel=isiTabelSQL($sql);          
          $sHitTabel=isiTabelSQL($sql,"../page/nd_bahan_nutrisi");
          echo $sHitTabel;
          ?>
        </table>
      </div>
    </div>
  </div>
    

</div>

<script> 
 
  $(function () {
    $("#tbl").DataTable({
      "order": [0, 'desc'],
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)');
     
  $('#tbl thead th:contains("Kode")')
    .attr('data-toggle','tooltip')
    .attr('title','Rumus kimia bahan')
    .css('cursor','help');
    

  });
  
</script>