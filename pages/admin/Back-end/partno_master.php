<?php
include 'koneksi.php';  // Ensure this includes your database connection file

// Handle form submission to add a part number
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pn'], $_POST['pn_name'])) {
    $pn = $_POST['pn'];
    $pn_name = $_POST['pn_name'];

    $sql = "INSERT INTO pn (pn, pn_name) VALUES ('$pn', '$pn_name')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Redirect or display success message
        header("Location: index.php"); // Redirect to prevent form resubmission
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle form submission to update a part number
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editPNId'], $_POST['editPNCode'], $_POST['editPNName'])) {
    $editPNId = $_POST['editPNId'];
    $editPNCode = $_POST['editPNCode'];
    $editPNName = $_POST['editPNName'];

    $sql = "UPDATE pn SET pn = '$editPNCode', pn_name = '$editPNName' WHERE id = $editPNId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Handle success, e.g., redirect or return success message
        echo "Part number updated successfully.";
        exit();
    } else {
        echo "Error updating part number: " . mysqli_error($conn);
    }
}

// Handle AJAX request to delete a part number
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletePNId'])) {
    $deletePNId = $_POST['deletePNId'];

    $sql = "DELETE FROM pn WHERE id = $deletePNId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "Part number deleted successfully.";
        exit();
    } else {
        echo "Error deleting part number: " . mysqli_error($conn);
    }
}
?>