<?php
if (1 == 0) { // IDE dummy params
  $val1 = $val2 = $val3 = "";
  $cUmum = new cUmum();
  $cUser = new cUser();
  $cTemp = new cTemplate($sNamaFile);
}

$val1 = $val1 ?? "";
$cTemp->setTitle("Node Role Date");
$cTemp->loadHeader();

$id_node = 0;
$id_role = 0;
$id_nrd = 0;
$tanggal = "";
$jam_mulai = "";
$jam_selesai = "";
$KetNode = "Node Aktuator";
$id_perusahaan = $_SESSION['id_perusahaan'];
$error_messages = [];
$class_card_detail= "card  collapsed-card";

//periksa apakah ada post data
if (isset($_POST['id_node'])) {
  // Validasi input
  $id_node = intval($_POST['id_node'] ?? 0);
  $id_role = intval($_POST['id_role'] ?? 0);
  $tanggal = trim($_POST['tanggal'] ?? '');
  $jam_mulai = trim($_POST['jam_mulai'] ?? '');
  $jam_selesai = trim($_POST['jam_selesai'] ?? '');
  $id_nrd = intval($_POST['id_nrd'] ?? 0);
  
  // Validasi data
  if ($id_node <= 1) {
    $error_messages[] = "Node harus dipilih";
  }
  
  if ($id_role <= 1) {
    $error_messages[] = "Role harus dipilih";
  }
  
  if (empty($tanggal)) {
    $error_messages[] = "Tanggal harus diisi";
  } elseif (!DateTime::createFromFormat('Y-m-d', $tanggal)) {
    $error_messages[] = "Format tanggal tidak valid";
  }
  
  if (empty($jam_mulai)) {
    $error_messages[] = "Jam mulai harus diisi";
  } elseif (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $jam_mulai)) {
    $error_messages[] = "Format jam mulai tidak valid (HH:MM)";
  }
  
  if (empty($jam_selesai)) {
    $error_messages[] = "Jam selesai harus diisi";
  } elseif (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $jam_selesai)) {
    $error_messages[] = "Format jam selesai tidak valid (HH:MM)";
  }
  
  // Validasi jam selesai harus setelah jam mulai
  if (!empty($jam_mulai) && !empty($jam_selesai)) {
    $time_start = strtotime($jam_mulai);
    $time_end = strtotime($jam_selesai);
    
    if ($time_end <= $time_start) {
      $error_messages[] = "Jam selesai harus setelah jam mulai";
    }
  }
  
  // Jika tidak ada error, proses data
  if (empty($error_messages)) {
    $param = [
      "id_node" => $id_node,
      "id_role" => $id_role,
      "tanggal" => date('Y-m-d', strtotime($tanggal)),
      "jam_mulai" => date('H:i:s', strtotime($jam_mulai)),
      "jam_selesai" => date('H:i:s', strtotime($jam_selesai)),
      "updater" => $cUser->userID()
    ];
    
    try {
      if ($id_nrd > 0) {
        $sSQL = "UPDATE node_role_date 
                SET id_node=:id_node, id_role=:id_role, tanggal=:tanggal,
                mulai=:jam_mulai, selesai=:jam_selesai, updater=:updater
                WHERE id = :id";
        $param["id"] = $id_nrd;
        $cUmum->eksekusi($sSQL, $param);
        $success_message = "Data berhasil diperbarui";
      } else {
        $sSQL = "INSERT INTO node_role_date (id_node, id_role, tanggal, mulai, selesai, updater) 
                VALUES (:id_node, :id_role, :tanggal, :jam_mulai, :jam_selesai, :updater)";
        $cUmum->eksekusi($sSQL, $param);
        $success_message = "Data berhasil disimpan";
      }
      
      // Reset form setelah sukses
      $id_role = 0;
      $id_nrd = 0;
      $tanggal = "";
      $jam_mulai = "";
      $jam_selesai = "";
      $val1 = "id";
      $val2 = $id_node;
    } catch (Exception $e) {
      $error_messages[] = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
    }
  }
}


if($val1 == "nrd" && $val2 > 0){
  $sSQL = "SELECT id,id_node,id_role,tanggal,mulai,selesai from node_role_date WHERE id = :id";
  $param["id"] = $val2;
  $r=$cUmum->ambil1Row($sSQL,$param); 
  $id_nrd=$val2;
  $id_node =intval($r["id_node"]);  
  $id_role =intval($r["id_role"]);  
  $tanggal = $r["tanggal"];
  $jam_mulai = $r["mulai"];
  $jam_selesai = $r["selesai"];
  $val1="id";	
  $val2=$id_node;  
  $class_card_detail= "card";
}

if(($val1 == "id" || $val1 == "idnd") && $val2 > 0){
  if($val1 == "idnd") $class_card_detail= "card";
  $val2 = $cUser->isMyNode(intval($val2));;
  $sSQL = "SELECT CONCAT_WS(' => ', nama, keterangan) AS ketNode FROM node WHERE id = :id";
  // $sSQL .= " AND id_perusahaan = $id_perusahaan"; 
  $KetNode = $cUmum->ambil1Data($sSQL, ['id' => $val2]);
  $id_node = $val2;
}


?>

<!-- Tambahkan ini di bagian atas content-wrapper untuk menampilkan pesan error/sukses -->
<div class="content-wrapper">
  <?php if (!empty($error_messages)): ?>
    <div class="container-fluid">
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($error_messages as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>
  
  <?php if (!empty($success_message)): ?>
    <div class="container-fluid">
      <div class="alert alert-success">
        <?= htmlspecialchars($success_message) ?>
      </div>
    </div>
  <?php endif; ?>  

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="form-group">
        <label><?=$KetNode?></label>
        <select id="node_id" name="id_node" class="form-control" onchange="node_terpilih(this.value)">
          <option value="0">- - - Pilih Node aktuator - - -</option>
          <?php  
          $sSqlOp2 = "SELECT n.id,n.nama,c.chip from node n INNER JOIN chip c ON c.id = n.id_chip 
          INNER JOIN tipe t ON t.id=c.id_tipe INNER JOIN kebun k ON k.id=c.id_kebun 
          where t.kelompok > 1 and k.id_perusahaan=" . $id_perusahaan;
          bikinOption($sSqlOp2, $id_node, "chip", "-", "nama");
          ?>
        </select> 
      </div>

      <div class="row">
        <!-- Kolom Kiri untuk Tabel Data -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Jadwal</h3>
            </div>            
            <div class="card-body">
              <table class="table table-bordered">
                
                  <?php
                  // Query untuk menampilkan data jadwal yang sudah ada dari satu node
                  $sSql = "SELECT nr.id as nrd, nr.tanggal,nr.mulai,nr.selesai,r.keterangan rule, m.memo ket_rule
                  FROM node_role_date nr 
                  JOIN node_role r ON nr.id_role = r.id 
                  LEFT JOIN memo m ON m.id = r.id_memo
                  WHERE nr.id_node=$id_node ORDER BY tanggal,mulai";
                  $sTabelJadwal=isiTabelSQL($sSql,"../page/nr_date");
                  echo $sTabelJadwal;
                  ?>
                 
              </table>
              <br>
              <button class="btn btn-primary btn-sm float-right" 
              onclick="window.location.href = '../page/nr_date$$idnd$$<?=$id_node?>'"> 
              <!-- onclick="bukaCardDetail()"> -->
              <!-- onclick="document.getElementById('card_detail').classList.remove('collapsed-card')"> -->
              +Jadwal Baru
            </button> 
          </div>
          </div>
        </div>

        <!-- Kolom Kanan untuk Form Entry -->
        <div class="col-md-6">
          <div  class="<?=$class_card_detail?>" id="card_detail">
            <div class="card-header">
              <h3 class="card-title">Detail Jadwal</h3>
               <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <!-- bikin form post ke file ini -->
              <form action="" method="post" id="form_nrd">
                <input type="hidden" name="id_node" value=<?=$id_node;?>>
                <input type="hidden" name="id_nrd" value=<?=$id_nrd;?>> 
                <div class="form-group">
                  <label>Role</label> 
                  <select id="id_role" name="id_role" class="form-control" required>
                    <option value=0>--pilih Role--</option>
                    <?php  
                      $sSqlOp1 = "SELECT nr.id, nr.keterangan FROM node_role nr 
                      WHERE nr.id_perusahaan =" . $id_perusahaan;
                      bikinOption($sSqlOp1, $id_role,"keterangan");
                    ?>
                  </select>  
                </div>
                
                <div class="form-group">
                  <label>Tanggal</label> 
                  <input type="date" name="tanggal" class="form-control" 
                    value="<?=htmlspecialchars($tanggal); ?>" required />
                </div>
                
                <div class="row align-items-end">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Batas Jam Mulai</label> 
                      <div class="input-group date" id="timepicker0" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" data-target="#timepicker0" 
                        value="<?=htmlspecialchars($jam_mulai); ?>" name="jam_mulai" required 
                        pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" title="Format jam (HH:MM)"/>                        -->
                        <div class="input-group-append" data-target="#timepicker0" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Batas Jam Selesai</label>
                      <div class="input-group date" id="timepicker1" data-target-input="nearest">   
                        <input type="text" class="form-control datetimepicker-input" data-target="#timepicker1" 
                          value="<?=htmlspecialchars($jam_selesai); ?>" name="jam_selesai" required 
                          pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" title="Format jam (HH:MM)"/>                     -->
                        <div class="input-group-append" data-target="#timepicker1" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary"><?php 
                    if($id_nrd == 0){
                      echo "Simpan / tambahkan";
                    }else{  
                      echo "Update";  
                    }                  
                  ?>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  //Timepicker dengan format 24 jam
  $('#timepicker0').datetimepicker({
    format: 'HH:mm',
    stepping: 1
  });
  $('#timepicker1').datetimepicker({
    format: 'HH:mm',
    stepping: 1
  });

  //buat fungsi node_terpilih
  function node_terpilih(id) {  
    // const kode = 'id'; // Ganti dengan kode yang sesuai
    window.location.href = `../nr_date$$id$$${id}`; 
  }
// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
  const idRole = document.getElementById('id_role').value;
  const tanggal = document.querySelector('[name="tanggal"]').value;
  const jamMulai = document.querySelector('[name="jam_mulai"]').value;
  const jamSelesai = document.querySelector('[name="jam_selesai"]').value;
  const errorMessages = [];
  
  if (idRole == 0) {
      errorMessages.push('Role harus dipilih');
  }
  
  if (!tanggal) {
      errorMessages.push('Tanggal harus diisi');
  }
  
  if (!jamMulai) {
      errorMessages.push('Jam mulai harus diisi');
  } else if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(jamMulai)) {
      errorMessages.push('Format jam mulai tidak valid (HH:MM)');
  }
  
  if (!jamSelesai) {
      errorMessages.push('Jam selesai harus diisi');
  } else if (!/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(jamSelesai)) {
      errorMessages.push('Format jam selesai tidak valid (HH:MM)');
  }
  
  if (jamMulai && jamSelesai) {
      const start = new Date('1970-01-01T' + jamMulai + ':00');
      const end = new Date('1970-01-01T' + jamSelesai + ':00');
      
      if (end <= start) {
          errorMessages.push('Jam selesai harus setelah jam mulai');
      }
  }
  
  if (errorMessages.length > 0) {
      e.preventDefault();
      alert('Error:\n' + errorMessages.join('\n'));
      return false;
  }
});


function bukaCardDetail() {
  const card = document.getElementById('card_detail');
  if (card.classList.contains('collapsed-card')) {
    // Pastikan card ter-expand jika terlipat
    $(card).CardWidget('expand'); 
  }
} 

</script>