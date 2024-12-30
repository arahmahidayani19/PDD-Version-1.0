<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PDD</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">
  <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/nav.css">
  <link rel="stylesheet" href="../../dist/sweetalert2/sweetalert2.min.css">
  

</head>
<body>
  <?php 
    // Including necessary files
    include ('Back-end/line.php');
    include ('../../include/nav.php');
  ?>

<div class="content-wrapper">
  
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Production Line - <?php echo htmlspecialchars($line); ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Line</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Back Button -->
  <div class="btn" id="btn-back">
    <a href="javascript:history.back()" class="btn-primary btn-outline-white btn-sm mt-3 px-2 py-6 rounded">
      <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <?php 
        // Check if there are machines to display
        if (!empty($machines)) {
            foreach ($machines as $machine_name): ?>
                <div class="col-lg-4 col-md-6">
                    <!-- Card -->
                    <div class="card card-primary card-outline shadow-sm hover-shadow">
                        <a href="machine.php?line=<?php echo urlencode($_GET['line']); ?>&machine=<?php echo urlencode($machine_name); ?>" class="text-decoration-none">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3">
                                <i class="fas fa-cogs fa-2x mb-2 text-info"></i> <!-- Ikon -->
                                <h6 class="text-primary font-weight-bold mb-0">Machine <?php echo htmlspecialchars($machine_name); ?></h6> <!-- Line Name -->
                            </div>
                        </a>
                        <div class="card-footer text-center py-2">
                            <a href="machine.php?line=<?php echo urlencode($_GET['line']); ?>&machine=<?php echo urlencode($machine_name); ?>" class="btn btn-sm btn-primary">
                                View Machine Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        } else {
            // If no machines are found, display a message
            echo '<div class="col-12 text-center"><div class="alert alert-warning">No machines found for this line.</div></div>';
        }
        ?>
      </div>
    </div>
  </section>
</div>

  <?php include ('../../include/footer.php'); ?>
  <!-- JS, Popper.js, and jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../dist/js/adminlte.min.js"></script>
  <script src="../../dist/js/dark_buton.js"></script>
  <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
<script src="../../dist/sweetalert2/sweetalert2.js"></script>


</body>
</html>
