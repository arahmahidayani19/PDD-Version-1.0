<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['editMachineId'];
    $machine_name = $_POST['editMachineName'];
    $asset_no = $_POST['editAssetNo'];
    $brand = $_POST['editBrand'];
    $model = $_POST['editModel'];
    $serial_no = $_POST['editSerialNo'];
    $date = $_POST['editDate'];
    $tonnage = $_POST['editTonnage'];

    $sql = "UPDATE lines_machines SET 
            machine_name='$machine_name', asset_no='$asset_no', brand='$brand', model='$model', 
            serial_no='$serial_no', date='$date', tonnage='$tonnage'
            WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'machineName' => $machine_name]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]); // Menampilkan error SQL
    }
    
}
?>
