<?php
include 'koneksi.php';

if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Gunakan prepared statements untuk keamanan
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User ID not provided']);
}

$conn->close();
?>
