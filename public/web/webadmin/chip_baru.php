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
 
    </style>
    
</head>
<body>
    <h1>Modul system Chip XN </h1> 
    <form action="update_node.php" method="POST">
        <div class="form-group">
          <label for="kebun">Kebun:</label>
          <select id="kebun" name="kebun">

            <?php
            // Query SELECT
            $query = "SELECT k.nama AS kebun, k.id AS kebunid, p.nama AS perusahaan FROM kebun k 
            JOIN perusahaan p ON k.id_perusahaan = p.id";

            $result = $cUmum->ambilData($query);

            // Memeriksa apakah query berhasil dijalankan
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row['kebunid'] . '">' . $row['kebun'] . ' - ' . $row['perusahaan'] . '</option>';
                }
            } else {
                echo "Error: " . $query . "<br>" . $conn->errorInfo()[2];
            }
            ?>

          </select>
        </div>

        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" placeholder="Nama Chip">
        </div>

        <div class="form-group">
            <label for="chip">Chip:</label>
            <input type="text" id="chip" name="chip" placeholder="Chip">
        </div>

        <div class="form-group">
          <label for="tipe">Tipe:</label>
          <select id="tipe" name="tipe" style="width: 100%">

            <?php
            // Query SELECT
            $query = "SELECT id, nama, kelompok, keterangan FROM tipe";

            $result = $cUmum->ambilData($query);

            // Memeriksa apakah query berhasil dijalankan
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row['id'] . '">' .  $row['id'] . '='. $row['nama'] . ' K' .$row['kelompok']. ' ' . $row['keterangan'] . '</option>';
                }
            } else {
                echo "Error: " . $query . "<br>" . $conn->errorInfo()[2];
            }
            ?>

          </select>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan:</label>
            <input type="text" id="keterangan" name="keterangan" placeholder="Keterangan">
        </div>

        <div class="form-group">
            <label for="versi">Versi:</label>
            <input type="text" id="versi" name="versi" placeholder="Versi">
        </div>

        <div class="form-group">
            <label for="build">Build:</label>
            <input type="text" id="build" name="build" placeholder="Build">
        </div>

        <div class="form-group">
            <label for="memo">Memo:</label>
            <textarea id="memo" name="memo" placeholder="Memo"></textarea>
        </div>
        
        <div class="form-tombol">
        <button type="submit">Simpan</button>
      </div>
  </form>

<?php 
echo bikinTabelSQL("select * from chip order by id desc limit 36");
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