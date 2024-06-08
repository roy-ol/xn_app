<?php
if(empty($sAddOnNavBarLeft)) $sAddOnNavBarLeft="";  
if(empty($sAddOnNavBarRight)) $sAddOnNavBarRight="";  
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <?=$sAddOnNavBarLeft;?>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <?=$sAddOnNavBarRight;?>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-users mr-2"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->