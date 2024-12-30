<?php
include 'koneksi.php';  // Pastikan file koneksi database sudah benar

$success_message = '';
$error_message = '';

// Handle form submission to add a machine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['machineName']) && !isset($_POST['editMachineId'])) {
    $line_name = $_POST['line_name'];
    $machine_name = $_POST['machineName'];
    $asset_no = $_POST['assetNo'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $serial_no = $_POST['serialNo'];
    $date = $_POST['date'];
    $tonnage = $_POST['tonnage'];

    $sql = "INSERT INTO lines_machines (line_name, machine_name, asset_no, brand, model, serial_no, date, tonnage) 
            VALUES ('$line_name','$machine_name', '$asset_no', '$brand', '$model', '$serial_no', '$date', '$tonnage')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $success_message = "Machine added successfully.";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Handle form submission to update a machine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editMachineId'])) {
    $editMachineId = $_POST['editMachineId'];
    $editMachineName = $_POST['editMachineName'];
    $editAssetNo = $_POST['editAssetNo'];
    $editBrand = $_POST['editBrand'];
    $editModel = $_POST['editModel'];
    $editSerialNo = $_POST['editSerialNo'];
    $editDate = $_POST['editDate'];
    $editTonnage = $_POST['editTonnage'];

    $sql = "UPDATE lines_machines SET machine_name = '$editMachineName', asset_no = '$editAssetNo', brand = '$editBrand', 
            model = '$editModel', serial_no = '$editSerialNo', date = '$editDate', tonnage = '$editTonnage' WHERE id = $editMachineId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $success_message = "Machine updated successfully.";
    } else {
        $error_message = "Error updating machine: " . mysqli_error($conn);
    }
}

// Handle AJAX request to delete a machine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteMachineId'])) {
    $deleteMachineId = $_POST['deleteMachineId'];

    $sql = "DELETE FROM lines_machines WHERE id = $deleteMachineId";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "Machine deleted successfully.";
        exit();
    } else {
        echo "Error deleting machine: " . mysqli_error($conn);
        exit();
    }
}
?>