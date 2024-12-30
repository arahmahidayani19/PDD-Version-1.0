<?php
include 'koneksi.php'; // Ensure this file is correctly included

session_start();

header('Content-Type: application/json'); // Set header for JSON response

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pn'], $_POST['pn_name'], $_POST['customer_id'])) {
    $pn = mysqli_real_escape_string($conn, $_POST['pn']);
    $pn_name = mysqli_real_escape_string($conn, $_POST['pn_name']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']); // Ensure customerID is properly handled

    // Insert the part number into the products table
    $sql = "INSERT INTO products (productID, productName, customerID) VALUES ('$pn', '$pn_name', '$customer_id')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $response = array(
            'success' => true,
            'message' => 'Part number added successfully!',
            'pn' => $pn,
            'pn_name' => $pn_name,
            'customer_id' => $customer_id
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error adding part number: ' . mysqli_error($conn)
        );
    }
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid request. Please provide all required fields.'
    );
}

echo json_encode($response); // Make sure there's no other output before this
?>
