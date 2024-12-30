<?php include ('../../include/session.php');?>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-light-primary elevation-4">
      <a href="#" class="brand-link" style="text-align: center; display: flex; align-items: center; justify-content: center;">
        <span class="brand-text" style="font-size: 1.25rem; font-weight: bold; line-height: 1.5; margin-right: 10px;">PDD</span>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="themeSwitch">
          <label class="custom-control-label" for="themeSwitch"></label>
        </div>
      </a>

      <div class="sidebar">
        <!-- Sidebar user panel -->

        <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-center">
        <div class="info text-center">
          <img src="../../dist/img/sanwa.png" alt="Logo" class="mb-2" style="width: 60px; height: 35px;">
          <a href="#" class="d-block">Production Display System</a>
        </div>
      </div>


        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link active">
                <i class="nav-icon fas fa-home"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-item">
              <p class="nav-link" style="font-weight: bold; font-size: 16px; padding-left: 15px;">
                Main Menu
              </p>
            </li>

            <!-- User List -->
            <li class="nav-item">
              <a href="user_list.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>User List</p>
              </a>
            </li>

            <!-- Daily Transaction -->
            <li class="nav-item">
          <a href="daily_tr.php" class="nav-link">
            <i class="nav-icon fas fa-clipboard-list"></i> <!-- Daily transaction icon -->
            <p>Daily Transaction</p>
          </a>
        </li>

            <!-- Information File -->
            <li class="nav-item menu-open">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-folder-open"></i> <!-- Information file icon -->
            <p>
              Information File
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" style="padding-left: 30px;">
              <a href="information_file.php" class="nav-link">
                <i class="fas fa-file-alt nav-icon"></i> <!-- Document icon -->
                <p>Part Number Document</p>
              </a>
            </li>
            <li class="nav-item" style="padding-left: 30px;">
              <a href="machine_document.php" class="nav-link">
                <i class="fas fa-tools nav-icon"></i> <!-- Machine document icon -->
                <p>Machine Document</p>
              </a>
            </li>
          </ul>
        </li>

            <!-- Machine Master -->
            <li class="nav-item">
              <a href="machine_master.php" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>Machine Master</p>
              </a>
            </li>

            <!-- Part Number Master -->
            <li class="nav-item">
          <a href="partno_master.php" class="nav-link">
            <i class="nav-icon fas fa-layer-group"></i> <!-- Part number master icon -->
            <p>Part Number Master</p>
          </a>
        </li>          
          </ul>
        </nav>
      </div>
    </aside>
