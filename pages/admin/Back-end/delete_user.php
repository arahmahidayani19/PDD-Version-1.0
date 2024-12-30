<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah benar

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Menggunakan prepared statements untuk keamanan
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Respons JSON saat user berhasil dihapus
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        // Respons JSON saat ada error
        echo json_encode(['status' => 'error', 'message' => 'Error deleting user: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    // Respons JSON saat ID user tidak disediakan
    echo json_encode(['status' => 'error', 'message' => 'User ID not provided']);
}

$conn->close(); // Tutup koneksi ke database
?>
