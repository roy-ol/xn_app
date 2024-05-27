<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}  
if(empty($val1)){$val1 = "";} 
 
$cTemp->setHeaderCap("Node Role"); 
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <button class="btn btn-primary btn-sm" onclick="window.location.href = '../page/node_role_form'">
        + NodeRole
    </button>

    <?php
        $sql = "SELECT nr.id nrid, CONCAT('▶ ',nr.keterangan) NodeRole, CONCAT('R:',nr.relay,'\nX:',nr.exeval)'Rel xVal',
        CONCAT('V1:',nr.exe_v1 , ' V2:',
        nr.exe_v2) 'Val1 Val2', CONCAT('Ref:',nr.reff_node, ' Nil:' , nr.nilai_1) Ref,nr.updated
        FROM `node_role` nr WHERE nr.id_perusahaan = $id_perusahaan ORDER BY nr.id DESC";
        $sHitTabel=bikinTabelSQL3($sql,"../page/node_role_form");
        echo $sHitTabel;
    ?>



    <div class="content">
        <!-- <div class="container-fluid">
            <p>Selamat datang di list!</p>
            <div id="content_dashboard"><?=$val1 ?></div>
        </div>/.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->