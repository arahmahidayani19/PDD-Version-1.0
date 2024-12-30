<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PTR</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <span class="brand-text font-weight-light">PTR</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
  
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li> 
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="../tables/data.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>PTR REPORT</p>
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>PTR REPORT</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              
             
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php
function getDateRange($range) {
    $startDate = $endDate = '';
    switch ($range) {
        case 'thisWeek':
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
            break;
        case 'lastWeek':
            $startDate = date('Y-m-d', strtotime('monday last week'));
            $endDate = date('Y-m-d', strtotime('sunday last week'));
            break;
        case 'thisMonth':
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            break;
        case 'lastMonth':
            $startDate = date('Y-m-01', strtotime('first day of last month'));
            $endDate = date('Y-m-t', strtotime('last day of last month'));
            break;
        case 'customDate':
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? '';
            break;
    }
    return [$startDate, $endDate];
}

function fetchDataBasedOnDate($startDate, $endDate, $noTRF, $partNo, $taskStatus, $dateRange) {
    $workflowSteps = []; // Store latest steps for each WorkflowId

    // Load data from JSON files
    $hourlyData = file_exists('../hourly.json') ? json_decode(file_get_contents('../hourly.json'), true) : [];
    $dailyData = file_exists('../daily.json') ? json_decode(file_get_contents('../daily.json'), true) : [];

    if (empty($hourlyData) && empty($dailyData)) {
        return []; // Return empty if no data is available
    }

// Determine which data to process based on the date range
if ($dateRange === 'thisWeek' || $dateRange === 'lastWeek') {
    $dataSet = $hourlyData; // Use hourly.json for this week and last week
} elseif ($dateRange === 'thisMonth' || $dateRange === 'lastMonth') {
    // Menggunakan data dari hourly.json untuk dua minggu terakhir
    $dataSet = $hourlyData; // Ambil data hourly terlebih dahulu

    // Kemudian ambil data dari daily.json untuk rentang lebih dari dua minggu
    if (!empty($dailyData)) {
        // Gabungkan data dari daily.json
        $dataSet = array_merge($dataSet, $dailyData);
    }
} elseif ($dateRange === 'customDate') {
    $dataSet = $dailyData; // Use daily.json for custom date range
} else {
    return []; // Return empty for invalid date range
}


    // Process the selected dataset
    foreach ($dataSet as $entry) {
        if (isset($entry['ListTaskRecord']) && is_array($entry['ListTaskRecord'])) {
            foreach ($entry['ListTaskRecord'] as $taskRecord) {
                $stepId = (int) $taskRecord['StepId'];
                $workflowId = $taskRecord['WorkflowId'];

                // Store only the step with the highest StepId for each WorkflowId
                if (!isset($workflowSteps[$workflowId]) || $stepId > $workflowSteps[$workflowId]['StepId']) {
                    $workflowSteps[$workflowId] = [
                        'StepId' => $stepId,
                        'TaskRecord' => $taskRecord
                    ];
                }
            }
        }
    }

    // Filter the results based on user input (e.g., date range, No TRF, Part No, status)
    $filteredEntries = [];
    foreach ($workflowSteps as $step) {
        $taskRecord = $step['TaskRecord'];

        // Initialize variables
        $currentNoTRF = '';
        $currentPartNo = '';
        $currentTaskStatus = $taskRecord['TaskRecordStatus'];

        // Extract Received Date and check if it's within the date range
        $isWithinDateRange = false;
        foreach ($taskRecord['UIComponentEntityList'] as $component) {
            if ($component['Label'] === 'Received Date') {
                $receivedDate = DateTime::createFromFormat('d/m/Y', $component['Data1']);
                if ($receivedDate) {
                    $formattedDate = $receivedDate->format('Y-m-d');
                    if ($formattedDate >= $startDate && $formattedDate <= $endDate) {
                        $isWithinDateRange = true;
                    }
                }
            }

            // Extract No TRF and Part No
            if ($component['Label'] === 'Mold Repair Repair ID') {
                $currentNoTRF = $component['Data1'];
            }
            if ($component['Label'] === 'Mold Number') {
                $currentPartNo = $component['Data1'];
            }
        }

        // Apply filters: Only add to results if it matches all criteria
        if ($isWithinDateRange &&
            ($noTRF === '' || $noTRF === $currentNoTRF) &&
            ($partNo === '' || $partNo === $currentPartNo) &&
            ($taskStatus === '' || $taskStatus === $currentTaskStatus)) {
            
            $filteredEntries[] = $taskRecord;
        }
    }

    return $filteredEntries;
}

// In your POST handling section, make sure to pass the date range as an argument
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateRange = $_POST['dateRange'] ?? '';
    $noTRF = $_POST['noTRF'] ?? '';
    $partNo = $_POST['partno'] ?? '';
    $taskStatus = $_POST['task'] ?? '';

    // Minimum requirement: Date Range must be selected
    if (!$dateRange) {
        die('Please select a valid date range.');
    }

    // Determine date range
    list($startDate, $endDate) = getDateRange($dateRange);

    // Get data based on date range and filters
    $filteredEntries = fetchDataBasedOnDate($startDate, $endDate, $noTRF, $partNo, $taskStatus, $dateRange);

    // Ensure $filteredEntries is initialized as an empty array if no data is returned
    if (!is_array($filteredEntries)) {
        $filteredEntries = [];
    }
}


    // Display results if there are any
    if (!empty($filteredEntries)) {
        ?>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>REC DATE</th>
                      <th>NO TRF</th>
                      <th>PART NO</th>
                      <th>PART NAME</th>
                      <th>CUSTOMER</th>
                      <th>MOLD NUMBER</th>
                      <th>JOB DESCRIPTION</th>
                      <th>JOB CLAS</th>
                      <th>REPAIR CATEGORY</th>
                      <th>JOB SITE</th>
                      <th>ESTIMATE COMPLETED DATE</th>
                      <th>STEP NAME</th>
                      <th>REQUEST</th>
                      <th>TASK</th>
                  </tr>
              </thead>
              <tbody id="tableBody">
              <?php
              $sn = 1;
              foreach ($filteredEntries as $taskRecord) {
                  $moldrepairrepairid = '';
                  $partNumber = '';
                  $partName = '';
                  $jobDescription = '';
                  $Requestor = '';
                  $Received_Date = '';
                  $Mold_Customer = '';
                  $Due_date = '';
                  $jobClass = '';
                  $jobSite = '';
                  $repaircategory = '';
                  $taskrecordstatus = $taskRecord['TaskRecordStatus'];
                  $stepName = $taskRecord['StepName'];
  
                  // Get components for the current task record
                  $components = $taskRecord['UIComponentEntityList'] ?? [];
  
                  foreach ($components as $component) {
                      switch ($component['Label']) {
                          case 'Mold Repair Repair ID':
                              $moldrepairrepairid = $component['Data1'];
                              break;
                          case 'Mold Number':
                              $partNumber = $component['Data1'];
                              break;
                          case 'Mold Name':
                              $partName = $component['Data1'];
                              break;
                          case 'ToolRoom Mold ID':
                              $Mold_number = $component['Data1'];
                              break;
                          case 'Cavity Number #1 Job Description':
                              $jobDescription = $component['Data1'] ?? '';
                              break;
                          case 'Job Classification':
                              $data1 = $component['Data1'] ?? ''; 
                              $jobClassifications = json_decode($data1, true);
                              if (is_array($jobClassifications) && isset($jobClassifications[0]['CurrentAnswer'])) {
                                  $currentAnswer = json_decode($jobClassifications[0]['CurrentAnswer'], true);
                                  if (is_array($currentAnswer)) {
                                      $answers = $jobClassifications[0]['Answers'];
                                      $jobClass = "";
                                      foreach ($currentAnswer as $index => $value) {
                                          if ($value) {
                                              $jobClass .= $answers[$index]['AnswerText'] . ", ";
                                          }
                                      }
                                      $jobClass = rtrim($jobClass, ", ");
                                  }
                              }
                              break;
                          case 'Job Site':
                              $data1 = $component['Data1'] ?? '';
                              $jobSites = json_decode($data1, true);
                              if (is_array($jobSites) && isset($jobSites[0]['CurrentAnswer'])) {
                                  $currentAnswer = json_decode($jobSites[0]['CurrentAnswer'], true);
                                  if (is_array($currentAnswer)) {
                                      $answers = $jobSites[0]['Answers'];
                                      $jobSite = "";
                                      foreach ($currentAnswer as $index => $value) {
                                          if ($value) {
                                              $jobSite .= $answers[$index]['AnswerText'] . ", ";
                                          }
                                      }
                                      $jobSite = rtrim($jobSite, ", ");
                                  }
                              }
                              break;
                          case 'Received Date':
                              $Received_Date = $component['Data1'];
                              break;
                          case 'Requestor':
                              $Requestor = $component['Data1'];
                              break;
                          case 'Mold Customer':
                              $Mold_Customer = $component['Data1'];
                              break;
                          case 'Repair Category':
                              $repaircategory = $component['Data1'];
                              break;
                          case 'Due Date':
                              $Due_date = $component['Data1'];
                              break;
                      }
                  }
                  ?>
                  <tr>
                      <td><?php echo $sn++; ?></td>
                      <td><?php echo $Received_Date; ?></td>
                      <td><?php echo $moldrepairrepairid; ?></td>
                      <td><?php echo $partNumber; ?></td>
                      <td><?php echo $partName; ?></td>
                      <td><?php echo $Mold_Customer; ?></td>
                      <td><?php echo $Mold_number; ?></td>
                      <td><?php echo $jobDescription; ?></td>
                      <td><?php echo $jobClass; ?></td>
                      <td><?php echo $repaircategory; ?></td>
                      <td><?php echo $jobSite; ?></td>
                      <td><?php echo $Due_date; ?></td>
                      <td><?php echo $stepName; ?></td>
                      <td><?php echo $Requestor; ?></td>
                      <td><?php echo $taskrecordstatus; ?></td>
                  </tr>
                  <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    } else {
        echo "<p>No records found for the selected filters.</p>";
    }

?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.1.0
    </div>
    <strong>&copy; AdminLTE <a href="#">PT.Sanwa Engineering Batam 2024</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
