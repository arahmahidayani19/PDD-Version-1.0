<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve the productID from the GET request
    $productID = isset($_GET['id']) ? $_GET['id'] : null;

    if ($productID) {
        // Check if the productID exists
if ($productID) {
    $checkSql = "SELECT COUNT(*) FROM products WHERE productID = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        // Debugging: Log the query result
        error_log("Product ID: " . $productID . " Count: " . $count);  // Logs the productID and count

        $stmt->close();
        if ($count == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Product ID not found in the database!']);
            exit();
        }
    }
}


        // Set the file paths to NULL (delete the files)
        $wi_path = NULL;
        $param_path = NULL;
        $pack_path = NULL;

        // Update the product record to NULLify the file paths
        $sql = "UPDATE products SET work_instruction = ?, master_parameter = ?, packaging = ? WHERE productID = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters and execute the statement
            $stmt->bind_param("ssss", $wi_path, $param_path, $pack_path, $productID);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Product files deleted successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete product files.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product ID is required.']);
    }

    $conn->close();
}
?>
