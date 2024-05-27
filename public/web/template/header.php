<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Home</title>
    <!-- Bootstrap & AdminLTE CSS -->
    <?php include_once "../template/stylesheet.php" ?>
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
        
