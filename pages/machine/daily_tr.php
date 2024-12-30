<?php include('sidebar.php'); ?>
<?php include('Back-end/koneksi.php'); ?>
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
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px); /* Samakan dengan input lainnya */
            padding: 0.375rem 0.75rem;   /* Padding seragam */
            border: 1px solid #ced4da;   /* Border seragam */
            border-radius: 0.25rem;      /* Border radius seragam */
            box-sizing: border-box;
        }
    </style>

</head>

<body>
<div class="wrapper">
    <!-- Navbar -->
    <?php include ('../../include/nav.php'); ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daily Transaction</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Daily Transaction</li>
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
                            <!-- Card Body -->
                            <div class="card-body">
                                
                                <!-- Add User Button -->
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#formModal">
                                    Add Data
                                </button>

                                <!-- Filter Section -->
                                <div class="filter-container row mb-3">
                                    <div class="col-md-4">
                                        <label for="startDate">Start Date:</label>
                                        <input type="date" id="startDate" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="endDate">End Date:</label>
                                        <input type="date" id="endDate" class="form-control">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button id="filterBtn" class="btn btn-primary mr-2">Filter</button>
                                        <button id="resetBtn" class="btn btn-secondary">Reset Filter</button>
                                    </div>
                                </div>

                                <!-- Transactions Table -->
                                <section class="section">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                            <table id="transactionsTable" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Line</th>
                                                        <th>Machine</th>
                                                        <th>Tonnage</th>
                                                        <th>JS No.</th>
                                                        <th>Part Number</th>
                                                        <th>Product Name</th>
                                                        <th>Customer</th>    
                                                        <th>Shift</th>
                                                        <th>Date</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Query to fetch transaction data and join with lines_machines and products
                                                    $sql = "SELECT form_data.*, 
                                                                lines_machines.tonnage, 
                                                                products.customerID, 
                                                                products.productName 
                                                            FROM form_data
                                                            LEFT JOIN lines_machines 
                                                                ON form_data.machineNumber COLLATE utf8mb4_unicode_ci = lines_machines.machine_name COLLATE utf8mb4_unicode_ci
                                                            LEFT JOIN products 
                                                                ON form_data.part_number = products.productID
                                                            ORDER BY form_data.transaction_date DESC";
                                                    
                                                    $result = $conn->query($sql);

                                                    if (!$result) {
                                                        die("Error executing query: " . $conn->error);
                                                    }

                                                    if ($result->num_rows > 0) {
                                                        // Display transaction data in the table
                                                        while($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                                    <td>" . htmlspecialchars($row['machineType']) . "</td>
                                                                    <td>" . htmlspecialchars($row['machineNumber']) . "</td>
                                                                    <td>" . htmlspecialchars($row['tonnage']) . "</td>
                                                                    <td>" . htmlspecialchars($row['jobsiteno']) . "</td>
                                                                    <td>" . htmlspecialchars($row['part_number']) . "</td>
                                                                    <td>" . htmlspecialchars($row['productName']) . "</td>
                                                                    <td>" . htmlspecialchars($row['customerID']) . "</td>
                                                                    <td>" . htmlspecialchars($row['shift']) . "</td>
                                                                    <td>" . date('d-m-Y H:i', strtotime($row['transaction_date'])) . "</td>
                                                                    
                                                                </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='9'>No data found</td></tr>"; // Adjusted colspan to 9 to match the number of columns
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<div class="modal fade text-left w-100" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 98%; width: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Daily Transactions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="proses_submit.php" method="post">
                    <div class="row">
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <div class="col-md-12 mb-4 entry" id="entry-<?php echo $i; ?>" style="<?php echo $i > 0 ? 'display:none;' : ''; ?>">
                                <h6>Entry <?php echo $i + 1; ?></h6>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label for="partNumber<?php echo $i; ?>" class="form-label">Part Number</label>
                                        <select id="partNumber<?php echo $i; ?>" name="partNumber[]" class="form-control select2" style="width: 100%;" <?php echo $i === 0 ? 'required' : ''; ?>>
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
                                    <div class="col-md-2 mb-3">
                                        <label for="shift<?php echo $i; ?>" class="form-label">Shift</label>
                                        <select id="shift<?php echo $i; ?>" name="shift[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="1st Shift">1st Shift</option>
                                            <option value="2nd Shift">2nd Shift</option>
                                            <option value="3rd Shift">3rd Shift</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineType<?php echo $i; ?>" class="form-label">Line</label>
                                        <select id="machineType<?php echo $i; ?>" name="machineType[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?> onchange="loadMachines(<?php echo $i; ?>)">
                                            <option value="">Select Line</option>
                                            <?php
                                            $sqlline = "SELECT DISTINCT line_name FROM lines_machines";
                                            $resultline = $conn->query($sqlline);
                                            if ($resultline->num_rows > 0) {
                                                while ($rowline = $resultline->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rowline['line_name']) . '">' . htmlspecialchars($rowline['line_name']) . '</option>';
                                                }
                                            } else {
                                                echo '<option>No Line data found.</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineNumber<?php echo $i; ?>" class="form-label">Machine</label>
                                        <select id="machineNumber<?php echo $i; ?>" name="machineNumber[]" class="form-control">
                                            <option value="" disabled selected>Select a machine</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="machineStatus<?php echo $i; ?>" class="form-label">Status</label>
                                        <select id="machineStatus<?php echo $i; ?>" name="machineStatus[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                            <option value="running">Running</option>
                                            <option value="stopped">Stopped</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="transactionDatetime<?php echo $i; ?>" class="form-label">Date & Time</label>
                                        <input type="datetime-local" id="transactionDatetime<?php echo $i; ?>" name="transactionDatetime[]" class="form-control" <?php echo $i === 0 ? 'required' : ''; ?>>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="jobsiteno<?php echo $i; ?>" class="form-label">JS No</label>
                                        <select id="jobsiteno<?php echo $i; ?>" name="jobsiteno[]" class="form-control select2" style="width: 100%;" <?php echo $i === 0 ? 'required' : ''; ?>>
                                        <option value="" disabled selected>Select JS No</option>
                                            <?php
                                            $sqljob_order = "SELECT jobOrderID FROM job_orders";
                                            $resultjob_order = $conn->query($sqljob_order);
                                            if ($resultjob_order->num_rows > 0) {
                                                while ($rowjob_order = $resultjob_order->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($rowjob_order['jobOrderID']) . '">' . htmlspecialchars($rowjob_order['jobOrderID']) . '</option>';
                                                }
                                            } else {
                                                echo '<option>No JS data found.</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="addEntryBtn" class="btn btn-primary">Add Entry</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
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
    // Inisialisasi Select2 untuk semua entry
    for(let i = 0; i < 10; i++) {
        $(`#partNumber${i}`).select2({
            dropdownParent: $('#formModal'),
            width: 'resolve'
        });
        
        
        $(`#jobsiteno${i}`).select2({
            dropdownParent: $('#formModal'),
            width: 'resolve'
        });
    }
});
$('#transactionsTable').DataTable({
    "order": [[7, 'desc']]  // Mengubah dari index 5 ke 7 karena kolom Date ada di index 7
});
</script>


<script>

$('button[data-bs-toggle="modal"]').on('click', function() {
    $('#formModal').modal('show');
});




        // JavaScript to load machines based on selected line
    function loadMachines(entryIndex) {
    const line = document.getElementById(`machineType${entryIndex}`).value;
    const machineDropdown = document.getElementById(`machineNumber${entryIndex}`);
    
    fetch(`get_machines.php?line=${encodeURIComponent(line)}`)
        .then(response => response.json())
        .then(data => {
            machineDropdown.innerHTML = ''; // Clear current options
            data.forEach(machine => {
                const option = document.createElement('option');
                option.value = machine.machine_name;
                option.textContent = machine.machine_name;
                machineDropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}
         document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form"); // Sesuaikan dengan selector form Anda
        form.addEventListener("submit", function() {
            const dateInputs = document.querySelectorAll("input[name='transactionDate[]']");
            
            dateInputs.forEach(dateInput => {
                const datetimeInput = document.createElement("input");
                datetimeInput.type = "hidden";
                datetimeInput.name = "transactionDateTime[]";
                
                // Ambil tanggal dari input date, lalu tambahkan waktu sekarang
                const selectedDate = dateInput.value;
                const now = new Date();
                const currentTime = now.toTimeString().split(" ")[0]; // Format HH:MM:SS
                datetimeInput.value = `${selectedDate} ${currentTime}`;
                
                form.appendChild(datetimeInput); // Tambahkan input hidden ke form
            });
        });
    });

        document.addEventListener('DOMContentLoaded', function() {
            var table = $('#transactionsTable').DataTable();

            document.getElementById('filterBtn').addEventListener('click', function() {
                var startDate = new Date(document.getElementById('startDate').value);
                var endDate = new Date(document.getElementById('endDate').value);

                if (startDate > endDate) {
                    alert('Start date must be less than or equal to end date.');
                    return;
                }

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var date = new Date(data[7]); // Kolom tanggal (index 5)
                    if (
                        (startDate === "" && endDate === "") ||
                        (startDate === "" && date <= endDate) ||
                        (endDate === "" && date >= startDate) ||
                        (date >= startDate && date <= endDate)
                    ) {
                        return true;
                    }
                    return false;
                });

                table.draw();
            });

            document.getElementById('resetBtn').addEventListener('click', function() {
                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';
                table.search('').draw(); 
            });

        });

            // Add Entry Button functionality
            document.getElementById('addEntryBtn').addEventListener('click', function() {
                var entries = document.querySelectorAll('.entry');
                for (var i = 0; i < entries.length; i++) {
                    if (entries[i].style.display === 'none') {
                        entries[i].style.display = 'block';
                        break;
                    }
                }
            });

            // Initialize DataTable
            $('#transactionsTable').DataTable();

            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    // Populate the edit modal with data (using AJAX if needed)
                    document.getElementById('editId').value = id;
                    // Add other fields as needed
                    $('#editModal').modal('show'); // Show the edit modal
                });
            });
        
            // Handle Delete button click
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var row = this.closest('tr'); 
                    row.style.display = 'none'; 
                 
                });
            });
        
    </script>
    </div>
    </body>
    </html> 

<?php
$conn->close();
?>
