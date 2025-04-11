<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="../../adminlte/dist/img/logoXN5c.png" alt="Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">XmartNode</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../adminlte/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?=$_SESSION["username"]?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="../dashboard" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <span class="right badge badge-danger">D1</span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="../dev_log" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
              Dev_Log
              <span class="right badge badge-danger">D2</span>
            </p>
          </a>
        </li>

        <!-- noderole -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link">
            <!-- <i class="nav-icon far fa-circle"></i> -->
            <p>
              Node Role
              <i class="fas fa-angle-left right"></i>
              <span class="right badge badge-danger">NR</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../node_role" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>NodeRole List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../node_role_form" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>NodeRole Baru</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item menu-open">
          <a href="#" class="nav-link">
            <!-- <i class="nav-icon far fa-circle"></i> -->
            <p>Role Schedule
              <i class="fas fa-angle-left right"></i>
              <span class="right badge badge-danger">RS</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../nr_jadwal_list" class="nav-link">
                <i class="far fa-calendar-alt nav-icon"></i>
                <p>Schedule List</p>
              </a>
            </li>
            <!-- noderole jadwal tanggal  -->
            <li class="nav-item">
              <a href="../nr_date" class="nav-link">
                <!-- <i class="far fa-circle nav-icon"></i> -->
                <i class="fas fa-calendar-day nav-icon"></i>
                <p>NodeRole Tanggal</p>
              </a>
            </li>
            <!-- noderole mingguan -->
            <li class="nav-item">
              <a href="../nr_week" class="nav-link">
                <!-- <i class="far fa-circle nav-icon"></i> -->
                <i class="fas fa-clock nav-icon"></i>
                <p>NodeRole Mingguan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <!-- menu untuk XT Execution Test -->
          <a href="xt_aktuator" class="nav-link">
            <i class="nav-icon fas fa-play"></i>
            <p>
              XT Execution Test
              <span class="right badge badge-danger">XT</span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <!-- menu untuk Query Bank -->
          <a href="q_bank" class="nav-link">
            <i class="nav-icon fas fa-search"></i>
            <p>
              Query Bank
              <span class="right badge badge-danger">QB</span>
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <a href="../logout.php" class="nav-link">
      <i class="fas fa-sign-out-alt"> Logout</i>

    </a>
  </div>
</aside>