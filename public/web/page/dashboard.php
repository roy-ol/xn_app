<?php  
if( 1 == 0 ){ //dummy param agar dikenali ide
    $val1 = "";$val2 = "";$val3 = ""; 
    $cUmum = new cUmum();
    $cUser = new cUser();
    $cTemp = new cTemplate($sNamaFile); 
}


if(empty($val1)){$val1 = "";} 

$cTemp->setHeaderCap("Dashboard");
 
$cTemp->loadHeader();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <div class="content-header"> -->
        <!-- <div class="container-fluid"> -->
            <!-- <div class="row mb-2">
                <h1 class="m-0">Dashboard</h1>
            </div>/.row -->
        <!-- </div>/.container-fluid -->
    <!-- </div> -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <p>Selamat datang di Dashboard!</p>
            <div id="content_dashboard"><?=$val1 ?></div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
$cTemp->loadFooter();
?>