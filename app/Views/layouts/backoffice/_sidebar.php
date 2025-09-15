<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="https://eminence.id/wp-content/uploads/2022/11/Eminence-Logo-White-Invert.png" alt="Logo" class="brand-image " style="opacity: .8">
    <span class="brand-text font-weight-light">Backoffice</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?=base_url("template/dist/img/user2-160x160.jpg")?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Administrator</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->


    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="<?=base_url('backoffice')?>" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?=base_url('backoffice/tambah-staff')?>" class="nav-link">
            <i class="nav-icon bi bi-file-plus"></i>
            <p>
              Tambah Staff
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?=base_url('backoffice/doctors')?>" class="nav-link">
            <i class="nav-icon bi bi-hospital"></i>
            <p>
              Dokter
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?=base_url('backoffice/therapists')?>" class="nav-link">
            <i class="nav-icon bi bi-heart-pulse"></i>
            <p>
              Therapist
            </p>
          </a>
        </li>

         <li class="nav-item">
          <a href="<?=base_url('backoffice/reservations')?>" class="nav-link">
            <i class="nav-icon bi bi-journal-medical"></i>
            <p>
              Reservasi
            </p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>