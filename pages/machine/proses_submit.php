<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Get the logged-in username from the session
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$username_db = "root";
$password = "";
$dbname = "pdd";
$conn = new mysqli($servername, $username_db, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['partNumber'])) {
    $part_numbers = $_POST['partNumber'];
    $shifts = $_POST['shift'] ?? [];
    $machineTypes = $_POST['machineType'] ?? [];
    $machineNumbers = $_POST['machineNumber'] ?? [];
    $machineStatuses = $_POST['machineStatus'] ?? [];
    $transactionDates = $_POST['transactionDatetime'] ?? [];
    $jobsitenos = $_POST['jobsiteno'] ?? [];

    // Prepare SQL statement for inserting data, including the username and transaction_date
    $stmt = $conn->prepare("INSERT INTO form_data (username, part_number, shift, machineType, machineNumber, machineStatus, transaction_date, jobsiteno) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Loop through each entry and only insert if all required fields are filled
    for ($i = 0; $i < count($part_numbers); $i++) {
        // Check if the current entry has all required fields filled
        if (!empty($part_numbers[$i]) && !empty($shifts[$i]) && !empty($machineTypes[$i]) && !empty($machineNumbers[$i]) && !empty($machineStatuses[$i]) && !empty($transactionDates[$i]) && !empty($jobsitenos[$i])) {
            // Ensure correct format for datetime (if needed)
            $transactionDate = date("Y-m-d H:i:s", strtotime($transactionDates[$i])); // Convert to MySQL datetime format

            // Bind the parameters (s = string) and execute for each entry
            $stmt->bind_param(
                "ssssssss", // 8 string parameters
                $username, // Bind the username from the session
                $part_numbers[$i], 
                $shifts[$i], 
                $machineTypes[$i], 
                $machineNumbers[$i], 
                $machineStatuses[$i], 
                $transactionDate, // Bind the formatted transaction date
                $jobsitenos[$i] // Bind the jobsiteno
            );

            if (!$stmt->execute()) {
                echo "Error executing query: " . $stmt->error;
            }
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect to the same page or show a success message
    header("Location: daily_tr.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
