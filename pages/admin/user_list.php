<?php include ('sidebar.php'); ?>
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
                        <h1 class="m-0">User List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">User List</li>
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inlineForm">
                                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Add User
                                </button>
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="userTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Data will be loaded dynamically via JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>


    <!-- Add User Modal -->
    <div class="modal fade" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="inlineFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inlineFormLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addUserForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addUsername">Username</label>
                            <input type="text" class="form-control" id="addUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="addPassword">Password</label>
                            <input type="password" class="form-control" id="addPassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="addRole">Role</label>
                            <select class="form-control" id="addRole" name="role" required>
                                <option value="Admin">Admin</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editUserForm">
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password" required>
                        </div>
                        <div class="form-group">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include ('../../include/footer.php'); ?>
</div>

<!-- JS Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/sweetalert2/sweetalert2.min.js"></script>
<script src="../../dist/sweetalert2/sweetalert2.js"></script>

<script>
   $(document).ready(function() {
    loadUserTable(); // Load data when page is ready

    // Function to load user data into the table
    function loadUserTable() {
        $.ajax({
            url: 'Back-end/user_data.php', // Ensure the URL is correct
            method: 'GET',
            success: function(data) {
                try {
                    const users = JSON.parse(data); // Parse the JSON response
                    let rows = '';

                    users.forEach(user => {
                        rows += `
                            <tr>
                                <td>${user.username}</td>
                                <td>${user.role}</td>
                                <td>
                                   <button class="btn btn-warning btn-sm" data-id="${user.id}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                    });

                    // Destroy and reinitialize the DataTable to ensure it reloads with new data
                    if ($.fn.DataTable.isDataTable('#userTable')) {
                        $('#userTable').DataTable().clear().destroy();
                    }

                    $('#userTable tbody').html(rows); // Insert the rows into the table
                    $('#userTable').DataTable({
                        "retrieve": true,  // Retrieve existing instance of DataTable
                        "autoWidth": false,
                    });
                } catch (e) {
                    console.error("Error parsing JSON data", e); // Error handling for JSON parsing
                }
            },
            error: function(xhr, status, error) {
                console.error(`Error loading user data: ${status} - ${error}`);
            }
        });
    }

    // Event delegation for edit buttons
    $(document).on('click', '.btn-warning', function() {
        const userId = $(this).data('id');
        editUser(userId); // Call the edit function when the edit button is clicked
    });

    // Function to delete user
    window.deleteUser = function(id) {
        console.log('Attempting to delete user with ID:', id); // Log the ID being passed

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this user!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'Back-end/delete_user.php',
                    method: 'POST',
                    data: { userId: id },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response); // Log the response from PHP

                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success');
                            loadUserTable(); // Refresh table after deletion
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error!', 'Failed to delete user.', 'error');
                        console.error(`Error deleting user: ${status} - ${error}`);
                    }
                });
            }
        });
    };
    // Function to edit user
    window.editUser = function(userId) {
        $.ajax({
            url: 'Back-end/get_user.php',
            method: 'POST',
            data: { userId: userId },
            dataType: 'json',
            success: function(data) {
                if (data.status === 'error') {
                    Swal.fire('Error', data.message, 'error');
                } else {
                    $('#editUserId').val(data.id);
                    $('#editUsername').val(data.username);
                    $('#editPassword').val(data.password);
                    $('#editRole').val(data.role);
                    $('#editUserModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error(`Error fetching user data: ${status} - ${error}`);
            }
        });
    };

    // Handle Edit User Form Submission
    $('#editUserModal').on('shown.bs.modal', function () {
    console.log('Modal is now visible');
});

$('#editUserForm').submit(function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    console.log('Submit clicked');
    $.ajax({
        url: 'Back-end/edit_user.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            console.log(response);  // Debug log
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                }).then(() => {
                    $('#editUserModal').modal('hide');
                    loadUserTable(); // Reload table
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message,
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(`Error: ${status} - ${error}`);
        }
    });
});
 
    // Handle Add User Form Submission
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'Back-end/add_user.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#inlineForm').modal('hide');
                    loadUserTable(); // Reload table after adding user
                } else {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    title: 'An error occurred. Please try again.',
                    showConfirmButton: false,
                    timer: 1500
                });
                console.error(`Error adding user: ${status} - ${error}`);
            }
        });
    });
});
</script>


</body>
</html>
