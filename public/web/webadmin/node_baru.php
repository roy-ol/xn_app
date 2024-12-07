<?php  
require_once __DIR__ . '/menu.php';  
?>


<!DOCTYPE html>
<html>

<head>
  <title>Node Form</title>
</head>

<body>
  <h2>Node Form</h2>

<?php

$idChip =  0;
$sub_node =  1;
$id_pola =   0;
$id_satuan =   0;
$nama =  "";
$keterangan = "";
$flag =  3; 
$id_node =0;

if(isset($_GET['id_node'])) {
    $id_node = $_GET['id_node'];
    $sSQL = "select * from node where id= :id_node" ;
    $param["id_node"] = $id_node; 
    $hasil = $cUmum->ambil1Row($sSQL,$param);
    if($hasil){
        $hasil = json_encode($hasil);
        $hasil = json_decode($hasil); //dijadikan object biar bisa akses pakai ->

        $idChip =$hasil->id_chip;
        $sub_node = $hasil->sub_node; 
        $id_pola =$hasil->id_pola;
        $id_satuan =$hasil->id_satuan;
        $nama = $hasil->nama;
        $keterangan =$hasil->keterangan ;
        $flag =$hasil->flag ;
        $sURL_Action = "../fungsi/updateNode"; 
    } 
}else{
    $sURL_Action = "../fungsi/addNode"; 
}


?>
  <form action="<?=$sURL_Action;?>" method="post">
    <input type="hidden" name="id_node" value=<?=$id_node;?>>
    <table style="width: 50%;">
      <tr>
        <td style="width: 10%;"><label for="id_chip">Chip:</label></td>
        <td><select id="id_chip" name="id_chip">
            <option value=0> - - - pilih chip - - - </option>
            <?php 
                    $query = "SELECT id,chip,keterangan FROM chip order by id desc"; 
                    bikinOption($query, $idChip,"chip"," ", "keterangan"); 
                ?>
          </select>
        </td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="sub_node">Sub Node:</label></td>
        <td><input type="number" id="sub_node" name="sub_node" value=<?=$sub_node;?>></td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="id_pola">Pola_Role_aktuator</label></td>
        <td><select id="id_pola" name="id_pola">
            <option value=0> - - - pilih id_pola - - - </option>
            <?php 
                    $query = "SELECT id,keterangan,nilai FROM nrpola;"; 
                    bikinOption($query,$id_pola, "keterangan", " (","nilai",")"); 
                ?>
          </select>
        </td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="id_satuan">Satuan Display</label></td>
        <td><select id="id_satuan" name="id_satuan">
            <option value=0> - - - pilih id satuan - - - </option>
            <?php 
                    $query = "SELECT id,display,nama,keterangan FROM satuan;"; 
                    bikinOption($query,$id_satuan, "display", " (","nama"," ","keterangan",")"); 
                ?>
          </select>
        </td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="nama">Nama_Node:</label></td>
        <td><input type="text" id="nama" name="nama" value="<?=$nama;?>"></td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="keterangan">Keterangan:</label></td>
        <td><input style="width: 50%;" type="text" id="keterangan" name="keterangan" value="<?=$keterangan;?>">
        </td>
        <!-- <td><input style="width: 27rem;" type="text" id="keterangan" name="keterangan"></td> -->
      </tr>
      <tr>
        <td style="width: 10%;"><label for="flag">Flag:</label></td>
        <td><input type="number" id="flag" name="flag" value=<?=$flag;?>></td>
      </tr>
      <tr>
        <td style="width: 10%;"><label for="flagC">Flag:</label></td>
        <td>0 = disActive, 1=hide, 2=no graph, 3=show</td>
      </tr>
    </table>
    <br>
    <input type="submit" value="Submit">
  </form>


<?php
$sSQL = "SELECT node.id AS id_node,node.*,s.display
  FROM node LEFT JOIN satuan s ON s.id = node.id_satuan  
  ORDER BY id DESC LIMIT 36";
$tabel = bikinTabelSQL2($sSQL,"");
echo "<br>ListNode";
echo $tabel; 
?>



</body>

</html>