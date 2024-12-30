<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pdd';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Default Line';
$machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Default Machine';

$sql = "SELECT * FROM machine_documents WHERE line_name = ? AND machine_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $line, $machine);
$stmt->execute();
$result = $stmt->get_result();
$machineDetails = $result->fetch_assoc();

$current_parameter_path = $machineDetails['current_parameter_path'] ?? null;
$master_molding_data_path = $machineDetails['master_molding_data_path'] ?? null;
$first_piece_path = $machineDetails['first_piece_path'] ?? null;
$visual_mapping_path = $machineDetails['visual_mapping_path'] ?? null;
?>

