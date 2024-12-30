<?php
// Konfigurasi koneksi ke database
$servername = "localhost";
$username = "root";
$password = ""; // Ganti dengan password database Anda jika ada
$dbname = "pdd";

// Buat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $sql = "DELETE FROM machine_documents WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data successfully deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data failed to delete']);
    }
    
    $stmt->close();
    $conn->close();
}
?>
