<?php include ('sidebar.php');?>
<?php include('Back-end/information_file.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDD</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/nav.css">
    <link rel="stylesheet" href="../../dist/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>

<body>
<?php include ('../../include/nav.php'); ?>
<div class="wrapper">

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Part Number Documents</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Part Number Documents</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- User Table Card -->
                        <div class="card">
                            <div class="card-body">
                                <!-- Add Data Button -->
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                    <i class="fa fa-plus mr-2"></i> Add Data
                                </button>

                                <!-- Data Table -->
                                <div class="card">
                                    <div class="card-body">
                                    <div class="table-responsive">
                                            <table id="partsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Part Number</th>
                                                        <th>Work Instruction</th>
                                                        <th>Master Parameter</th>
                                                        <th>Packaging</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // PHP untuk menampilkan data dari database
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            // Generate URLs
                                                            $wi_url = 'file_proxy.php?path=' . urlencode($row["work_instruction"]);
                                                            $param_url = 'file_proxy.php?path=' . urlencode($row["master_parameter"]);
                                                            $pack_url = 'file_proxy.php?path=' . urlencode($row["packaging"]);

                                                            echo "<tr>";
                                                            echo "<td>" . htmlspecialchars($row["productID"]) . "</td>";
                                                            echo "<td><a href='" . htmlspecialchars($wi_url) . "' target='_blank'>" . htmlspecialchars(basename($row["work_instruction"])) . "</a></td>";
                                                            echo "<td><a href='" . htmlspecialchars($param_url) . "' target='_blank'>" . htmlspecialchars(basename($row["master_parameter"])) . "</a></td>";
                                                            echo "<td><a href='" . htmlspecialchars($pack_url) . "' target='_blank'>" . htmlspecialchars(basename($row["packaging"])) . "</a></td>";
                                                            echo "<td>
                                                                    <a href='#' class='btn btn-warning btn-sm edit-part'
                                                                        data-id='" . htmlspecialchars($row["id"]) . "'
                                                                        data-part-number='" . htmlspecialchars($row["productID"]) . "'
                                                                        data-work-instruction='" . htmlspecialchars($row["work_instruction"]) . "'
                                                                        data-master-parameter='" . htmlspecialchars($row["master_parameter"]) . "'
                                                                        data-packaging='" . htmlspecialchars($row["packaging"]) . "'
                                                                        data-toggle='modal' data-target='#editPartNumberModal'>Edit</a>
                                                                    <a href='#' class='btn btn-danger btn-sm deleteBtn' data-id='" . htmlspecialchars($row["productID"]) . "'>Delete</a>
                                                                </td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='5'>Data not found</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.Data Table -->
                            </div>
                        </div>
                        <!-- /.User Table Card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.Main Content -->
    </div>
    <!-- /.Content Wrapper -->

</div>


<div class="modal fade" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="inlineFormLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inlineFormLabel">Add Part Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addPartNumberForm" action="add_part_number.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Part Number -->
                    <div class="form-group mb-3">
                        <label for="partNumber" class="form-label">Part Number</label>
                        <select id="partNumber" name="part_number[]" class="select2" style="width: 100%;" required>
                            <option value="" disabled selected>Select a part number</option>
                            <?php
                            $sqlpart_number = "SELECT productID FROM products";
                            $resultpart_number = $conn->query($sqlpart_number);
                            if ($resultpart_number->num_rows > 0) {
                                while ($rowpart_number = $resultpart_number->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($rowpart_number['productID']) . '">' . htmlspecialchars($rowpart_number['productID']) . '</option>';
                                }
                            } else {
                                echo '<option>No Part Number data found.</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Work Instruction -->
                    <div class="form-group mb-3">
                        <label for="work_instruction">Work Instruction</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="work_instruction_path" name="work_instruction_path" class="form-control" placeholder="Input path here" onchange="checkInput('work_instruction_path', 'work_instruction_file')">
                            </div>
                            <div class="col">
                                <input type="file" id="work_instruction_file" name="work_instruction_file" class="form-control" accept=".pdf, .xlsx" onchange="checkInput('work_instruction_path', 'work_instruction_file')">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file (PDF or XLSX only)</small>
                    </div>

                    <!-- Master Parameter -->
                    <div class="form-group mb-3">
                        <label for="master_parameter">Master Parameter</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="master_parameter_path" name="master_parameter_path" class="form-control" placeholder="Input path here" onchange="checkInput('master_parameter_path', 'master_parameter_file')">
                            </div>
                            <div class="col">
                                <input type="file" id="master_parameter_file" name="master_parameter_file" class="form-control" accept=".pdf, .xlsx" onchange="checkInput('master_parameter_path', 'master_parameter_file')">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file (PDF or XLSX only)</small>
                    </div>

                    <!-- Packaging -->
                    <div class="form-group mb-3">
                        <label for="packaging">Packaging</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="packaging_path" name="packaging_path" class="form-control" placeholder="Input path here" onchange="checkInput('packaging_path', 'packaging_file')">
                            </div>
                            <div class="col">
                                <input type="file" id="packaging_file" name="packaging_file" class="form-control" accept=".pdf, .xlsx" onchange="checkInput('packaging_path', 'packaging_file')">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file (PDF or XLSX only)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Part Number</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editPartNumberModal" tabindex="-1" role="dialog" aria-labelledby="editPartNumberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPartNumberModalLabel">Edit Part Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPartNumberForm" method="POST" action="Back-end/update_part.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_part_id" value="">
                <div class="modal-body">
                    <!-- Part Number -->
                    <div class="form-group mb-3">
                        <label for="editPartNumberInput">Part Number</label>
                        <input type="text" class="form-control" id="editPartNumberInput" name="productID" required>
                    </div>

                    <!-- Work Instruction -->
                    <div class="form-group mb-3">
                        <label>Work Instruction</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="editWorkInstructionPath" name="work_instruction_path" class="form-control" placeholder="Input path here">
                            </div>
                            <div class="col">
                                <input type="file" id="editWorkInstructionFile" name="work_instruction_file" class="form-control">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file</small>
                    </div>

                    <!-- Master Parameter -->
                    <div class="form-group mb-3">
                        <label>Master Parameter</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="editMasterParameterPath" name="master_parameter_path" class="form-control" placeholder="Input path here">
                            </div>
                            <div class="col">
                                <input type="file" id="editMasterParameterFile" name="master_parameter_file" class="form-control">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file</small>
                    </div>

                    <!-- Packaging -->
                    <div class="form-group mb-3">
                        <label>Packaging</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" id="editPackagingPath" name="packaging_path" class="form-control" placeholder="Input path here">
                            </div>
                            <div class="col">
                                <input type="file" id="editPackagingFile" name="packaging_file" class="form-control">
                            </div>
                        </div>
                        <small class="form-text text-muted">Choose to input a path or upload a file</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Footer -->
    <?php include ('../../include/footer.php'); ?>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
<script src="../../dist/sweetalert2/sweetalert2.js"></script>
<script src="../../dist/js/dark_buton.js"></script>
    
<script>
$(document).ready(function() {
    // Inisialisasi Select2 untuk part number
    $('#partNumber').select2({
        dropdownParent: $('#inlineForm'), // Sesuaikan dengan ID modal yang benar
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select a part number',
        allowClear: true
    });

    // Pastikan modal events dihandle dengan benar
    $('#inlineForm').on('shown.bs.modal', function () {
        $('#partNumber').select2('open');
    });

    // Destroy dan reinit select2 saat modal ditutup untuk mencegah masalah
    $('#inlineForm').on('hidden.bs.modal', function () {
        $('#partNumber').select2('destroy');
        $('#partNumber').select2({
            dropdownParent: $('#inlineForm'),
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Select a part number',
            allowClear: true
        });
    });
});

 $(document).ready(function () {
    // Initialize DataTable
    initializeDataTable();

    // Show modal on "Add Data" button click
    handleAddDataButtonClick();

    // Form submission for adding part number
    handleAddPNFormSubmit();

    // Form submission for editing part number
    handleEditPartNumberModalSubmit();

    // Delete button click handler with confirmation
    handleDeleteButtonClick();



// Initialize DataTable
function initializeDataTable() {
    $('#partsTable').DataTable({
        responsive: true,
        columnDefs: [
            { targets: [2], orderable: false } // Disable sorting on 'Action' column
        ],
        lengthMenu: [10, 25, 50],
        pageLength: 10,
    });
}

// Handle "Add Data" button click
function handleAddDataButtonClick() {
    $('button[data-bs-toggle="modal"]').on('click', function () {
        $('#inlineForm').modal('show');
    });
}

// Handle "Edit" button click and show modal
$(document).on('click', '.edit-part', function () {
    const id = $(this).data('id');
    const partNumber = $(this).data('part-number');
    const workInstruction = $(this).data('work-instruction');
    const masterParameter = $(this).data('master-parameter');
    const packaging = $(this).data('packaging');

    // Set values in modal form fields
    $('#editPartNumberInput').val(partNumber);
    $('#editWorkInstructionPath').val(workInstruction);
    $('#editMasterParameterPath').val(masterParameter);
    $('#editPackagingPath').val(packaging);
    $('#edit_part_id').val(id);

    // Show the modal
    $('#editPartNumberModal').modal('show');
});

// Handle "Edit Part Number" form submission
function handleEditPartNumberModalSubmit() {
    $('#editPartNumberForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'Back-end/update_part.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        $('#editPartNumberModal').modal('hide');
                        location.reload(); // Reload the page to refresh data
                    });
                } else {
                    showErrorMessage(response.message);
                }
            },
            error: function () {
                showErrorMessage('An error occurred while updating the part number.');
            },
        });
    });
}
// Handle "Add Part Number" form submission
function handleAddPNFormSubmit() {
    document.getElementById('addPartNumberForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch('add_part_number.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                }).then(() => {
                    $('#inlineForm').modal('hide');
                    // Refresh halaman atau tabel jika perlu
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: ' + data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while processing your request.',
            });
        });
    });
}

// Fungsi untuk mengecek input
function checkInput(pathId, fileId) {
    const pathInput = document.getElementById(pathId);
    const fileInput = document.getElementById(fileId);

    if (pathInput.value) {
        fileInput.disabled = true;
    } else {
        fileInput.disabled = false;
    }

    if (fileInput.files.length > 0) {
        pathInput.disabled = true;
    } else {
        pathInput.disabled = false;
    }
}

// Handle Delete button click with SweetAlert confirmation
function handleDeleteButtonClick() {
    $(document).on('click', '.deleteBtn', function () {
        const partId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'Back-end/delete_part.php',
                    type: 'GET',
                    data: { id: partId },
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            $('#partsTable').DataTable().row(row).remove().draw();
                        } else {
                            showErrorMessage(res.message);
                        }
                    },
                    error: function () {
                        showErrorMessage('An error occurred while deleting the part number.');
                    },
                });
            }
        });
    });
}

// Display error messages using SweetAlert
function showErrorMessage(message) {
    Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: message,
        showConfirmButton: true,
    });
}

});
</script>
    

</body>
</html>