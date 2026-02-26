<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
if(empty($val2)){$val2 = "";} 
if(empty($val3)){$val3 = 0;} 

$tangki_id = $val1 ? intval($val1) : 0;

// $sql_tangki = "SELECT * FROM nut_tangki WHERE id = :id LIMIT 1";
// $data_tangki = $cUmum->ambil1Row($sql_tangki, [':id' => $tangki_id]);

// $nama_tangki = $data_tangki ? $data_tangki['nama'] : '';
// $volume_liter = $data_tangki ? $data_tangki['volume_liter'] : '';

$sTitleFile = "KOMPOSISI TANGKI" ;

$cTemp->setTitle($sTitleFile); 
$cTemp->loadHeader();

?>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_komposisi'])){
  $tangki_id = intval($_POST['tangki_id'] ?? 0);
  $bahan_id = intval($_POST['bahan_id'] ?? 0);
  $konsentrasi_stok = floatval($_POST['konsentrasi_stok'] ?? 0);
  $edit_id = intval($_POST['id'] ?? 0);
  if($edit_id > 0){
    $sql = "UPDATE nut_tangki_komposisi 
            SET tangki_id=:tangki_id, bahan_id=:bahan_id, konsentrasi_stok=:konsentrasi_stok 
            WHERE id=:id";
    $params = [ ':tangki_id'=>$tangki_id,
                ':bahan_id'=>$bahan_id,
                ':konsentrasi_stok'=>$konsentrasi_stok,
                ':id'=>$edit_id ]; 
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsgIon = '<div class="alert alert-success">Komposisi tangki berhasil diperbarui.</div>';
    } else {
      $sMsgIon = '<div class="alert alert-danger">Gagal memperbarui komposisi tangki.</div>';
    }
  } else {
    $sql = "INSERT INTO nut_tangki_komposisi (tangki_id,bahan_id,konsentrasi_stok)
            VALUES (:tangki_id,:bahan_id,:konsentrasi_stok)";
    $params = [
        ':tangki_id'=>$tangki_id,
        ':bahan_id'=>$bahan_id,
        ':konsentrasi_stok'=>$konsentrasi_stok
    ];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsgIon = '<div class="alert alert-success">Komposisi tangki berhasil disimpan.</div>';
    } else {
      $sMsgIon = '<div class="alert alert-danger">Gagal menyimpan komposisi tangki.</div>';
    }
  }
}

// Handle delete
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_komposisi'])){
  $delete_id = intval($_POST['id'] ?? 0);
  if($delete_id > 0){
    $sql = "DELETE FROM nut_tangki_komposisi WHERE id = :id";
    $params = [':id' => $delete_id];
    $affected = $cUmum->eksekusi($sql, $params);
    if($affected !== false){
      $sMsgIon = '<div class="alert alert-success">Komposisi tangki berhasil dihapus.</div>';
      $is_edit = false;
      $edit_id = 0;
      $tangki_id = $bahan_id = $konsentrasi_stok = '';
    } else {
      $sMsgIon = '<div class="alert alert-danger">Gagal menghapus komposisi tangki.</div>';
    }
  }
}
// Jika ada param id di GET, load data untuk edit
$is_edit = false;

$edit_id = 0;
if($val1 == "id") {
  $edit_id = $val2; 
}  
if($edit_id > 0){
  $sql = "SELECT * FROM nut_tangki_komposisi WHERE id = :id LIMIT 1";
  $params = [':id' => $edit_id];
  $data = $cUmum->ambil1Row($sql, $params);
  if($data){
    $is_edit = true;
    $tangki_id=$data['tangki_id'];
    $bahan_id=$data['bahan_id'];
    $konsentrasi_stok=$data['konsentrasi_stok'];
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
      <!-- <div id="content_header"><?=$nama_tangki ?></div> -->
      <?php if(isset($sMsg)) echo $sMsg; ?>
    </div><!-- /.container-fluid -->
  </div>

 
  <div class="card">
      <div class="card-header">
        <!-- <h3 class="card-title"><?=$nama_tangki?></h3> -->
      </div> 
    </div>
    <!-- Ion Target Section -->
    <div class="card mt-4">
      <div class="card-header">
        <h3 class="card-title">Komposisi Tangki</h3>
      </div>
      <div class="card-body">  
        <form method="post" class="mb-6">
          <input type="hidden" name="edit_id" value="<?=$edit_id?>">
          <div class="row"> 
            <div class="col-md-3">
              <div class="form-group">
              <label>Tangki</label>
              <select name="tangki_id" class="form-control">
                <option value="0">- - - Pilih Tangki - - -</option>
                <?php
                $sql_tangki = "SELECT * FROM nut_tangki ORDER BY id";
                bikinOption($sql_tangki,$tangki_id,"nama"," | ","keterangan");
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
              <label>Bahan Nutrisi</label>
              <select name="bahan_id" class="form-control">
                <option value="0">- - - Pilih Bahan - - -</option>
                <?php
                $sql_bahan = "SELECT id,nama,deskripsi FROM nut_bahan ORDER BY nama";
                bikinOption($sql_bahan,$bahan_id,"nama"," ","deskripsi");
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group" title="Konsentrasi stok dalam gram/Liter">
                <label>gram/L</label>
                <input type="number" step="0.01" name="konsentrasi_stok"
                      class="form-control" required title="dalam gram / liter"
                      value="<?=htmlspecialchars($konsentrasi_stok ?? '')?>">
              </div>
            </div>


            <div class="col-md-4"> 
              <div class="form-group">
                <label>&nbsp;</label><br> 
                <?php if($is_edit): ?>
                  <input type="hidden" name="id" value="<?=$edit_id?>">
                  <button type="submit" name="save_komposisi" class="btn btn-success" title="Simpan Update">Save</button>
                  <a href="nd_tangki" class="btn btn-secondary" title="batal/cancel">batal</a>
                  <div class="float-right">
                    <button type="submit" name="delete_komposisi" class="btn btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?');">X Hapus</button>
                  </div>
                <?php else: ?>
                  <button type="submit" name="save_komposisi" class="btn btn-primary">
                    Save / Simpan
                  </button>
                <?php endif; ?>
                </div>
            </div>   
                 
          </div>
        </form>


        <?php if(isset($sMsgIon)) echo $sMsgIon; ?>
        <div class="table-responsive">
        <table id="tbl" class="table table-bordered table-striped">
          <?php
         $sql = "SELECT k.id,t.nama AS tangki,
                b.nama AS bahan,
                k.konsentrasi_stok,
                b.deskripsi
          FROM nut_tangki t
          LEFT JOIN nut_tangki_komposisi k ON k.tangki_id = t.id
          LEFT JOIN nut_bahan b ON k.bahan_id = b.id order by t.nama, b.nama";

          $sHitTabel=isiTabelSQL($sql,"../nd_tangki");
          echo $sHitTabel;
          ?>
        </table>
      </div>
    </div>
      </div>
    </div>
  </div>
</div>

<script> 
  function ion_terpilih(id) {  
    // const kode = 'id'; // Ganti dengan kode yang sesuai
    // alert("Ion terpilih: " + id);
  }
 
  $(function () {
    $("#tbl").DataTable({
      // "order": [0, 'asc'],
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('.col-md-6:eq(0)');

  });
  
</script>