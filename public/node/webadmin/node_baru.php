<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: login.php"); // Redirect ke halaman login jika belum login
  exit;
}

require_once __DIR__ . '../../../../app/init_class.php';
$cUmum = new cUmum(); 
?>


<!DOCTYPE html>
<html>
<head>
    <title>Node Form</title>
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

Dengan mengatur properti seperti di atas, textarea akan secara otomatis memperluas ketinggiannya saat konten teksnya bertambah dan menampilkan bilah pengguliran vertikal jika perlu.

    </style>
    
</head>
<body>
    <h1>Modul system Chip XN / Node </h1> 
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