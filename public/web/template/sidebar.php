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
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-table"></i>
                         <p>
                             Node Role
                             <i class="fas fa-angle-left right"></i>
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
                             <a href="../node_role" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>NodeRole Baru</p>
                             </a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>

 <!-- Control Sidebar -->
 <aside class="control-sidebar control-sidebar-dark">
     isi aside control_sidebar
     <!-- Control sidebar content goes here -->
 </aside>
 <!-- /.control-sidebar -->