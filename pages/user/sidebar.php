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

          

            <!-- Daily Transaction -->
            <li class="nav-item">
          <a href="daily_tr.php" class="nav-link">
            <i class="nav-icon fas fa-clipboard-list"></i> <!-- Daily transaction icon -->
            <p>Daily Transaction</p>
          </a>
        </li>

                    
          </ul>
        </nav>
      </div>
    </aside>
