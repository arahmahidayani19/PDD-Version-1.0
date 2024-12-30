<?php
include 'koneksi.php'; // Make sure koneksi.php is included correctly

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productID'], $_POST['pn'], $_POST['pn_name'], $_POST['customer_id'])) {
    // Sanitize the inputs to prevent SQL injection
    $productID = mysqli_real_escape_string($conn, $_POST['pn']);
    $productName = mysqli_real_escape_string($conn, $_POST['pn_name']);
    $customerID = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $editPNId = mysqli_real_escape_string($conn, $_POST['productID']); // The ID of the part to be updated

    // Update the part number in the database
    $sql = "UPDATE products SET productID = '$productID', productName = '$productName', customerID = '$customerID' WHERE id = $editPNId";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Return a success response as JSON
        $response = array(
            'success' => true,
            'message' => 'Part number updated successfully!'
        );
        echo json_encode($response);
    } else {
        // Return an error response as JSON
        $response = array(
            'success' => false,
            'message' => 'Error updating part number: ' . mysqli_error($conn)
        );
        echo json_encode($response);
    }
} else {
    // Return a response for missing or invalid fields
    $response = array(
        'success' => false,
        'message' => 'Invalid request. Please provide all required fields.'
    );
    echo json_encode($response);
}
?>
