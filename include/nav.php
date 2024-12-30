<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="dash.php" class="nav-link">Dashboard</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto align-items-center">
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

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User Profile and Logout -->
    <li class="nav-item dropdown">
    <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
        <!-- Gambar profil yang diambil dari session -->
        <img src="<?php echo isset($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../../dist/img/profile.png'; ?>" class="img-circle" alt="User Avatar" style="width: 30px; height: 30px; object-fit: cover; margin-right: 8px;">
        <span id="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </a>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="profile.php" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
        </a>
        <a href="change_password.php" class="dropdown-item">
            <i class="fas fa-key mr-2"></i> Change Password
        </a>
        <a href="#" class="dropdown-item" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
  </li>
  </ul>
</nav>

<script>
function confirmLogout() {
    console.log("confirmLogout() dipanggil"); // Cek apakah fungsi dipanggil
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log me out!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        console.log(result); // Debugging untuk melihat hasil
        if (result.isConfirmed) {
            window.location.href = '../../login/logout.php'; // Redirect to logout page
        }
    });
}

</script>