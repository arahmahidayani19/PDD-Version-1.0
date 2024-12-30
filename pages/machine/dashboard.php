<?php include ('sidebar.php'); ?>
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
</head>

<body>
  <?php 
    // Including necessary files
    include ('Back-end/dashbord.php');
    include ('../../include/nav.php');
  ?>

<div class="content-wrapper">
  
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Production Display</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Line</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <?php
        // Check if there are lines to display
        if (!empty($lines)) {
            foreach ($lines as $line): ?>
                <div class="col-lg-4 col-md-6">
                    <!-- Card -->
                    <div class="card card-primary card-outline shadow-sm hover-shadow">
                        <a href="line.php?line=<?php echo urlencode($line); ?>" class="text-decoration-none">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-3">
                                <i class="fas fa-industry fa-2x mb-2 text-info"></i> <!-- Icon -->
                                <h6 class="text-primary font-weight-bold mb-0">Line <?php echo htmlspecialchars($line); ?></h6> <!-- Line Name -->
                            </div>
                        </a>
                        <div class="card-footer text-center py-2">
                            <a href="line.php?line=<?php echo urlencode($line); ?>" class="btn btn-sm btn-primary">
                                View Machine <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        } else {
            echo '<div class="col-12 text-center"><div class="alert alert-warning">No production lines found.</div></div>';
        }
        ?>
      </div>
    </div>
  </section>
</div>



  <?php include ('../../include/footer.php');
?>

  <!-- JS, Popper.js, and jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../dist/js/adminlte.min.js"></script>
  <script src="../../dist/js/dark_buton.js"></script>


</body>
</html>
