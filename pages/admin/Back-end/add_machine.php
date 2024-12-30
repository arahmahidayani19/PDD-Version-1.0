<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $line_name = $_POST['line_name'];
    $machine_name = $_POST['machineName'];
    $asset_no = $_POST['assetNo'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $serial_no = $_POST['serialNo'];
    $date = $_POST['date'];
    $tonnage = $_POST['tonnage'];

    $sql = "INSERT INTO lines_machines (line_name, machine_name, asset_no, brand, model, serial_no, date, tonnage)
            VALUES ('$line_name', '$machine_name', '$asset_no', '$brand', '$model', '$serial_no', '$date', '$tonnage')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'machineName' => $machine_name]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding machine']);
    }
}
?>


