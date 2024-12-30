<?php
include 'koneksi.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteMachineId'])) {
    $deleteMachineId = $_POST['deleteMachineId'];

    // Validate the ID to ensure it's a positive integer
    if (filter_var($deleteMachineId, FILTER_VALIDATE_INT) === false) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid machine ID.']);
        exit();
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM lines_machines WHERE id = ?");
    $stmt->bind_param('i', $deleteMachineId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Machine deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting machine: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>