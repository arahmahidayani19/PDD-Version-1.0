<?php
// Include your database connection
include 'koneksi.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $id = $_POST['id'];  // User ID to identify which user to update
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the username already exists (excluding the current user's ID)
    $checkSql = "SELECT id FROM users WHERE username=? AND id != ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Username already exists
            echo json_encode(['status' => 'error', 'message' => 'Username already exists!']);
            exit();
        }
        $stmt->close();
    } else {
        // Error preparing the statement
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Proceed with updating the user details
    $sql = "UPDATE users SET username=?, password=?, role=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (No password hashing, store as entered)
        $stmt->bind_param("sssi", $username, $password, $role, $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
        } else {
            // Return error response
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user.']);
        }
        $stmt->close();
    } else {
        // Error preparing the statement
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
    }
}

// Close database connection
$conn->close();
?>
