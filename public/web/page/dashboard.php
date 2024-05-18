<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Home</title>
    <!-- Bootstrap & AdminLTE CSS -->
    <?php include_once "../template/stylesheet.php" ?>
 
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once "../template/navbar.php" ?>
        <?php include_once "../template/sidebar.php" ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <p>Selamat datang di Dashboard!</p>
                    <?php echo $_SERVER['DOCUMENT_ROOT']; ?>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            isi aside control_sidebar
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>XmartNode</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper --> 

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../../adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../adminlte/dist/js/adminlte.min.js"></script>

 
</body>
</html>

