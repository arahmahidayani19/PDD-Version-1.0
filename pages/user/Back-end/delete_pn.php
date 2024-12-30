<?php
include 'koneksi.php'; // Ensure your connection file is included correctly

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletePNId'])) {
    // Prevent SQL injection by escaping the part number ID
    $deletePNId = mysqli_real_escape_string($conn, $_POST['deletePNId']); 

    // Delete query to remove the part number from the database
    $sql = "DELETE FROM products WHERE id = $deletePNId"; // Make sure the table name matches your actual table
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Return success response as JSON
        $response = array(
            'success' => true,
            'message' => 'Part number deleted successfully.'
        );
        echo json_encode($response);
    } else {
        // Return error response as JSON
        $response = array(
            'success' => false,
            'message' => 'Error deleting part number: ' . mysqli_error($conn)
        );
        echo json_encode($response);
    }
} else {
    // Return response for invalid request
    $response = array(
        'success' => false,
        'message' => 'Invalid request. Please provide a valid part number ID.'
    );
    echo json_encode($response);
}
?>
