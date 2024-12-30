<?php
// Get the line and machine parameters from the URL
$line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';
$machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Unknown Machine';

// Database connection
$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "pdd";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve data from form_data
$sql = "SELECT * FROM form_data WHERE machineType = ? AND machineNumber = ? ORDER BY created_at DESC LIMIT 1"; // Fetching only the latest entry
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $line, $machine);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Assign variables from form_data
        $part_number = isset($row['part_number']) ? htmlspecialchars($row['part_number']) : 'N/A';
        $shift = isset($row['shift']) ? htmlspecialchars($row['shift']) : 'N/A';
        $machineStatus = isset($row['machineStatus']) ? htmlspecialchars($row['machineStatus']) : 'N/A';
        $transaction_date = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'N/A'; // Ensure 'created_at' is the date used
        
        // Query to fetch additional information from products table
        $sql_fetch_info = "SELECT work_instruction, packaging, master_parameter, customerID, productName FROM products WHERE productID = ?";
        $stmt_parts = $conn->prepare($sql_fetch_info);

        if ($stmt_parts) {
            $stmt_parts->bind_param("s", $part_number);
            $stmt_parts->execute();
            $result_parts = $stmt_parts->get_result();

            if ($result_parts->num_rows > 0) {
                $parts_row = $result_parts->fetch_assoc();
                $work_instruction = isset($parts_row['work_instruction']) ? htmlspecialchars($parts_row['work_instruction']) : '#';
                $information = isset($parts_row['information']) ? htmlspecialchars($parts_row['information']) : '#';
                $packaging = isset($parts_row['packaging']) ? htmlspecialchars($parts_row['packaging']) : '#';
                $master_parameter = isset($parts_row['master_parameter']) ? htmlspecialchars($parts_row['master_parameter']) : '#';
                $current_parameter = isset($parts_row['current_parameter']) ? htmlspecialchars($parts_row['current_parameter']) : '#'; // Tambahkan Current Parameter

                // Assign customerID and productName
                $customerID = isset($parts_row['customerID']) ? htmlspecialchars($parts_row['customerID']) : 'Unknown Customer';
                $productName = isset($parts_row['productName']) ? htmlspecialchars($parts_row['productName']) : 'Unknown Product';
            } else {
                $work_instruction = '#';
                $information = '#';
                $packaging = '#';
                $master_parameter = '#';
                $current_parameter = '#'; // Current Parameter default
                $customerID = 'Unknown Customer';
                $productName = 'Unknown Product';
            }
            $stmt_parts->close();
        }
    } else {
        $part_number = 'No Data';
        $shift = 'No Data';
        $machineStatus = 'No Data';
        $transaction_date = 'No Data';
        $work_instruction = '#';
        $information = '#';
        $packaging = '#';
        $master_parameter = '#';
        $current_parameter = '#'; // Current Parameter default
        $customerID = 'Unknown Customer';
        $productName = 'Unknown Product';
    }

    $stmt->close();
} else {
    die("Error preparing statement for form_data: " . $conn->error);
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD</title>
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

    <div class="wrapper">
        <!-- Navbar -->
        <?php include ('Back-end/line.php'); ?>
        <?php include ('../../include/nav.php'); ?>
 

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Line - <?php echo htmlspecialchars($line) . ' Machine ' . htmlspecialchars($machine); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Machine</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            </section>

<?php include('Back-end/machine_detail.php'); ?>


<div class="btn" id="btn-back">
    <a href="javascript:history.back()" class="btn-primary btn-outline-white btn-sm mt-3 px-2 py-6 rounded">
      <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
  </div>
  
<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mt-3">
            <?php if ($machineDetails): ?>
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Machine Details - <?php echo htmlspecialchars($machine); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-industry"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Brand</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['brand']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Model</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['model']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-barcode"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Serial No</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['serial_no']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-weight-hanging"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tonnage</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['tonnage']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-tag"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Asset No</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['asset_no']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-secondary"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Date of Machine</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineDetails['date']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-lg-12">
                    <div class="card card-danger">
                        <div class="card-body">
                            <p>No details available for this machine.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cogs"></i> Machine Running Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Part Number Currently Running -->
                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Part Number Currently Running</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($part_number); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Part Name</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($productName); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Customer</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($customerID); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Machine Status -->
                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-tachometer-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Machine Status</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($machineStatus); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Shift -->
                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Shift</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($shift); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date Start Part Number -->
                                <div class="col-md-6 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Date Start Part Number</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($transaction_date))); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="container-fluid">
    <div class="row">
        <!-- Work Instruction Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card card-outline card-primary shadow-lg hover-shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> Work Instruction</h3>
                </div>
                <div class="card-body">
                    <p>Instructions for operating the machine or assembling the product.</p>
                    <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($work_instruction); ?>')">
                        <i class="fas fa-eye"></i> View File
                    </button>
                </div>
            </div>
        </div>

        <!-- Packaging Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card card-outline card-primary shadow-lg hover-shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-box-open"></i> Packaging</h3>
                </div>
                <div class="card-body">
                    <p>Instructions on how to package the part.</p>
                    <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($packaging); ?>')">
                        <i class="fas fa-eye"></i> View File
                    </button>
                </div>
               
            </div>
        </div>

        <!-- Master Parameter Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card card-outline card-primary shadow-lg hover-shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Master Parameter</h3>
                </div>
                <div class="card-body">
                    <p>The master parameters for the machine operation.</p>
                    <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($master_parameter); ?>')">
                        <i class="fas fa-eye"></i> View File
                    </button>
                </div>
            </div>
        </div>

        
        <?php include ('Back-end/machine_documen_dash.php'); ?>

        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="card card-outline card-primary shadow-lg hover-shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-tachometer-alt"></i>  Current Parameter</h3>
                </div>
                <div class="card-body">
                <p>The current parameters used for the machine's operation.</p>
                    <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($current_parameter_path); ?>')">
                        <i class="fas fa-eye"></i> View File
                    </button>
                </div>
               
            </div>
        </div>

    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card card-outline card-primary shadow-lg hover-shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-database"></i> Master Molding Data</h3>
        </div>
        <div class="card-body">
          <p>Injection Molding Data Parameters specifications</p> 
            <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($master_molding_data_path); ?>')">
                <i class="fas fa-eye"></i> View File
            </button>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card card-outline card-primary shadow-lg hover-shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-check-circle"></i> First Piece Approval</h3>
        </div>
        <div class="card-body">
           <p>Initial Machine setup Approval</p>
            <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($first_piece_path); ?>')">
                <i class="fas fa-eye"></i> View File
            </button>
        </div>
    </div>
</div>

<div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card card-outline card-primary shadow-lg hover-shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-map"></i> Visual Mapping</h3>
        </div>
        <div class="card-body">
            <p>Quality Condition of the Part during Initial Setup</p>
            <button class="btn btn-primary btn-block" onclick="openPDFInNewTab('<?php echo urlencode($visual_mapping_path); ?>')">
                <i class="fas fa-eye"></i> View File
            </button>
        </div>
    </div>
</div>
</div>
</div>
</section>

        </div>

    </div> 
       
      </div>
    </section>
  </div>
    <?php include('../../include/footer.php'); ?>

  <script src="../../plugins/jquery/jquery.min.js"></script>
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../dist/js/adminlte.min.js"></script>
  <script src="../../dist/js/dark_buton.js"></script>
  <script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
  <script src="../../dist/sweetalert2/sweetalert2.js"></script>

<script>
    function openPDFInNewTab(filePath) {
        window.open('pdf_viewer.php?file=' + filePath, '_blank');
    }
</script>


<script>
    let pdfDoc = null;
    let pageNum = 1;

    async function viewPDF(filePath) {
        const loadingTask = pdfjsLib.getDocument(`file_proxy.php?path=${filePath}`);
        pdfDoc = await loadingTask.promise;
        renderPage(pageNum);
        document.getElementById('pdf-viewer').style.display = 'block';
    }

    async function renderPage(num) {
    const page = await pdfDoc.getPage(num);
    const scale = 1.5;
    const viewport = page.getViewport({ scale });

    // Menyesuaikan canvas dengan viewport
    const canvas = document.getElementById('pdf-canvas');
    const context = canvas.getContext('2d');
    
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render halaman ke dalam canvas
    const renderContext = {
        canvasContext: context,
        viewport: viewport
    };
    await page.render(renderContext).promise;

    document.getElementById('prev').disabled = (num <= 1);
    document.getElementById('next').disabled = (num >= pdfDoc.numPages);
}

</script>
</body>
</html>
