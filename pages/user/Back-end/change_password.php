<?php
// Include database connection
include('koneksi.php');

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password_baru'];
    $confirm_password = $_POST['konfirmasi_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match!']);
        exit();
    }

    // Get the username from the session
    $username = $_SESSION['username'];

    // Check if the user exists in the database
    $checkUserQuery = "SELECT * FROM users WHERE username = ?";
    if ($checkStmt = $conn->prepare($checkUserQuery)) {
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            echo json_encode(['status' => 'error', 'message' => 'User does not exist.']);
            exit();
        }
        $checkStmt->close();
    }

    // Prepare the SQL statement
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $new_password, $username);

        // Execute the statement
        if ($stmt->execute()) {
            // Password change successful
            echo json_encode(['status' => 'success', 'message' => 'Password changed successfully!']);
        } else {
            // SQL execution error
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Error: ' . $stmt->error]);
        }

        // Close the statement
        $stmt->close();
    } else {
        // SQL preparation error
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL statement.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
