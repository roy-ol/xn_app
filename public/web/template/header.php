<!DOCTYPE html>
<html lang="en">
<?php 
if(empty($sTitle)) $sTitle = "Xmart Node";
if(empty($sAddOnNavBarLeft)) $sAddOnNavBarLeft="";  
if(empty($sAddOnNavBarRight)) $sAddOnNavBarRight="";  
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $sTitle ; ?></title>
    <!-- Bootstrap & AdminLTE CSS -->
    <?php 
        include_once "../template/stylesheet.php";  
        include_once "../template/js_file.php"; 
    ?>
    <script>
        function fungsiOnLoad() {
            displayElapsedTime();
        }
    </script>
</head>

<body class="hold-transition sidebar-mini" onload="fungsiOnLoad()">
    <div class="wrapper">
        <?php include_once "../template/navbar.php" ?>
        <?php include_once "../template/sidebar.php" ?>