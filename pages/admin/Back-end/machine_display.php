<?php
// Get the line and machine parameters from the URL
$line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';
$machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Unknown Machine';

// Database connection
$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "pdd";

$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve data from form_data
$sql = "SELECT * FROM form_data WHERE machineType = ? AND machineNumber = ? ORDER BY transaction_date DESC LIMIT 1"; // Fetching only the latest entry
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $line, $machine);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Assign variables from form_data
        $part_number = isset($row['part_number']) ? htmlspecialchars($row['part_number']) : 'N/A';
        $shift = isset($row['shift']) ? htmlspecialchars($row['shift']) : 'N/A';
        $machineStatus = isset($row['machineStatus']) ? htmlspecialchars($row['machineStatus']) : 'N/A';
        $transaction_date = isset($row['transaction_date']) ? htmlspecialchars($row['transaction_date']) : 'N/A';
        
        // Query to fetch additional information from parts table
        $sql_fetch_info = "SELECT work_instruction, information, packaging, master_parameter FROM parts WHERE part_number = ?";
        $stmt_parts = $conn->prepare($sql_fetch_info);

        if ($stmt_parts) {
            $stmt_parts->bind_param("s", $part_number);
            $stmt_parts->execute();
            $result_parts = $stmt_parts->get_result();

            if ($result_parts->num_rows > 0) {
                $parts_row = $result_parts->fetch_assoc();
                $work_instruction = isset($parts_row['work_instruction']) ? htmlspecialchars($parts_row['work_instruction']) : '#';
                $information = isset($parts_row['information']) ? htmlspecialchars($parts_row['information']) : '#';
                $packaging = isset($parts_row['packaging']) ? htmlspecialchars($parts_row['packaging']) : '#';
                $master_parameter = isset($parts_row['master_parameter']) ? htmlspecialchars($parts_row['master_parameter']) : '#';
            } else {
                $work_instruction = '#';
                $information = '#';
                $packaging = '#';
                $master_parameter = '#';
            }
            $stmt_parts->close();
        }
    } else {
        $part_number = 'No Data';
        $shift = 'No Data';
        $machineStatus = 'No Data';
        $transaction_date = 'No Data';
        $work_instruction = '#';
        $information = '#';
        $packaging = '#';
        $master_parameter = '#';
    }

    $stmt->close();
} else {
    die("Error preparing statement for form_data: " . $conn->error);
}

$conn->close();
?>