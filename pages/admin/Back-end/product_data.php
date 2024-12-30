<?php
include 'koneksi.php';

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT productID, customerID, productName FROM products WHERE productID = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch product data and send it as JSON
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product ID not provided']);
}

$conn->close();
?>
