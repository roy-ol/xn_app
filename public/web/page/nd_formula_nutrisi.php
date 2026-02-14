<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 

$sTitleFile = "FORMULA NUTRISI";
 
$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();
?>

<?php
// Handle form submission for nut_formula
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_nut_formula'])){
  global $cUmum;
  $nama = trim($_POST['nama'] ?? '');
  $jenis_tanaman = trim($_POST['jenis_tanaman'] ?? '');
  $ph_min = floatval($_POST['ph_min'] ?? 0);
  $ph_max = floatval($_POST['ph_max'] ?? 0);
  $ec_min = floatval($_POST['ec_min'] ?? 0);
  $ec_max = floatval($_POST['ec_max'] ?? 0);
  $keterangan = trim($_POST['keterangan'] ?? '');

  // Jika ada id -> lakukan update, selain itu insert
  $edit_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  if($edit_id > 0){
    $sql = "UPDATE nut_formula SET nama=:nama, jenis_tanaman=:jenis_tanaman, ph_min=:ph_min,
            ph_max=:ph_max, ec_min=:ec_min, ec_max=:ec_max, keterangan=:keterangan, updated=CURRENT_TIMESTAMP()
            WHERE id = :id";
    $params = [
      ':nama' => $nama,
      ':jenis_tanaman' => $jenis_tanaman,
      ':ph_min' => $ph_min,
      ':ph_max' => $ph_max,
      ':ec_min' => $ec_min,
      ':ec_max' => $ec_max,
      ':keterangan' => $keterangan,
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
    $sql = "INSERT INTO nut_formula (nama, jenis_tanaman, ph_min, ph_max, ec_min, ec_max, keterangan) 
            VALUES (:nama, :jenis_tanaman, :ph_min, :ph_max, :ec_min, :ec_max, :keterangan)";
    $params = [
      ':nama' => $nama,
      ':jenis_tanaman' => $jenis_tanaman,
      ':ph_min' => $ph_min,
      ':ph_max' => $ph_max,
      ':ec_min' => $ec_min,
      ':ec_max' => $ec_max,
      ':keterangan' => $keterangan
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
 

// Jika ada param id di GET, load data untuk edit
$is_edit = false;

$edit_id = 0;
if($val1 == "id") {
  $edit_id = $val2; 
}  

$nama_val = '';
$jenis_val = '';
$ph_min_val = '';
$ph_max_val = '';
$ec_min_val = '';
$ec_max_val = '';
$keterangan_val = '';
if($edit_id > 0){
  $row = $cUmum->ambil1Row("SELECT * FROM nut_formula WHERE id = :id", [':id'=>$edit_id]);
  if($row){
    $is_edit = true;
    $nama_val = $row['nama'];
    $jenis_val = $row['jenis_tanaman'];
    $ph_min_val = $row['ph_min'];
    $ph_max_val = $row['ph_max'];
    $ec_min_val = $row['ec_min'];
    $ec_max_val = $row['ec_max'];
    $keterangan_val = $row['keterangan'];
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
      <div id="content_header"><?=$keterangan_val ?></div>
      <?php if(isset($sMsg)) echo $sMsg; ?>
    </div><!-- /.container-fluid -->
  </div>

 
  <div class="card">
      <div class="card-header">
        <h3 class="card-title">Card, <?=$sTitleFile ?></h3>
      </div>
      <div class="card-body">
        <!-- Form entri formula nutrisi -->
        <form method="post" class="mb-4">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nama Formula</label>
                <input type="text" name="nama" class="form-control" required maxlength="50" value="<?=htmlspecialchars($nama_val)?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Jenis Tanaman</label>
                <input type="text" name="jenis_tanaman" class="form-control" required maxlength="50" value="<?=htmlspecialchars($jenis_val)?>">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="<?=htmlspecialchars($keterangan_val)?>">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>pH Min</label>
                <input type="number" step="0.1" name="ph_min" class="form-control" required value="<?=htmlspecialchars($ph_min_val)?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>pH Max</label>
                <input type="number" step="0.1" name="ph_max" class="form-control" required value="<?=htmlspecialchars($ph_max_val)?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>EC Min</label>
                <input type="number" step="0.01" name="ec_min" class="form-control" required value="<?=htmlspecialchars($ec_min_val)?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>EC Max</label>
                <input type="number" step="0.01" name="ec_max" class="form-control" required value="<?=htmlspecialchars($ec_max_val)?>">
              </div>
            </div>
          </div>

          <div class="form-group">
          <?php if($is_edit): ?>
            <input type="hidden" name="id" value="<?=$edit_id?>">
            <a href="nd_formula_nutrisi" class="btn btn-secondary">Batal</a>
            <button type="submit" name="save_nut_formula" class="btn btn-success">Update</button>                       
          </div>
          <a href="nd_target_ion$$<?=$edit_id?>"class="btn btn-primary float-end">Target Ion</a>
          <?php else: ?>
            <button type="submit" name="save_nut_formula" class="btn btn-primary">Simpan Formula</button>
          </div>
            <?php endif; ?>              
        </form>
        <table id="tbl" class="table table-bordered table-striped">
          <?php
          $sql = "SELECT * FROM nut_formula
            ORDER BY id DESC 
            LIMIT 50;" ;  
          $sHitTabel=isiTabelSQL($sql,"../nd_formula_nutrisi");
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

  });
  
</script>