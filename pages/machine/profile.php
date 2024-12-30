<?php include ('sidebar.php');?>
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
</head>

<body>
<?php 
include ('../../include/nav.php'); 
include ('Back-end/koneksi.php');

$username = $_SESSION['username'];

// Fetch the current profile details excluding the profile image
$query = "SELECT last_login, role FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($last_login, $role);
$stmt->fetch();
$stmt->close();

// Check if the last login is available, else show 'Never Logged In'
$last_login_display = !empty($last_login) ? $last_login : 'Never Logged In';
?>

<div class="wrapper">
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Profile</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Profile Image Section -->
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="../../dist/img/profile.png" alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center"><?php echo htmlspecialchars($username); ?></h3>
                                <p class="text-muted text-center">Role <?php echo htmlspecialchars($role); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details Section -->
                    <div class="col-md-9">
                        <div class="row">
                            <!-- Last Login Card -->
                            <div class="col-lg-4 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h4>Last Login</h4>
                                        <p><?php echo htmlspecialchars($last_login_display); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Role Card -->
                            <div class="col-lg-4 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h4>Role</h4>
                                        <p><?php echo htmlspecialchars($role); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Username Card -->
                            <div class="col-lg-4 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h4>Username</h4>
                                        <p><?php echo htmlspecialchars($username); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Profile Information -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Additional Profile Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Last Login</label>
                                        <p><?php echo htmlspecialchars($last_login_display); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Role</label>
                                        <p><?php echo htmlspecialchars($role); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include ('../../include/footer.php'); ?>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/dark_buton.js"></script>
</body>
</html>
