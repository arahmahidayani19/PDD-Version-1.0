<?php
session_start();

// Set zona waktu ke Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

$conn = new mysqli("localhost", "root", "", "pdd");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil input dari form
$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$response = array(); // Prepare the response array

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if ($password == $user['password']) { // Ganti dengan password_verify() jika password terenkripsi
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Dapatkan waktu login sekarang
        $currentTime = date('Y-m-d H:i:s');

        // Update waktu login di database
        $updateQuery = "UPDATE users SET last_login = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $currentTime, $user['id']);
        $updateStmt->execute();
        $updateStmt->close();

        // Catat login ke tabel login_history
        $logQuery = "INSERT INTO login_history (user_id, username, login_time) VALUES (?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("iss", $user['id'], $user['username'], $currentTime);
        $logStmt->execute();
        $logStmt->close();

        // Success response
        $response['success'] = true;
        $response['role'] = $user['role']; // Provide the user role
        $response['redirect_url'] = ($user['role'] == 'admin') ? '../pages/admin/dashboard.php' : '../pages/user/dashboard.php'; // Define redirect URL
    } else {
        // Password salah
        $response['success'] = false;
        $response['message'] = 'Invalid username or password.';
    }
} else {
    // Username tidak ditemukan
    $response['success'] = false;
    $response['message'] = 'Invalid username or password.';
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>
