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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $part_numbers = $_POST['part_number'];
    $shifts = $_POST['shift'];
    $machineTypes = $_POST['machineType'];
    $machineNumbers = $_POST['machineNumber'];
    $machineStatuses = $_POST['machineStatus'];
    $transactionDates = $_POST['transactionDate'];

    // Prepare SQL statement for inserting data, including the username and transaction_date
    $stmt = $conn->prepare("INSERT INTO form_data (username, part_number, shift, machineType, machineNumber, machineStatus, transaction_date) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Loop through each entry and only insert if all required fields are filled
    for ($i = 0; $i < count($part_numbers); $i++) {
        // Check if the current entry has all required fields filled
        if (!empty($part_numbers[$i]) && !empty($shifts[$i]) && !empty($machineTypes[$i]) && !empty($machineNumbers[$i]) && !empty($machineStatuses[$i]) && !empty($transactionDates[$i])) {
            // Bind the parameters (s = string) and execute for each entry
            $stmt->bind_param(
                "sssssss", 
                $username, // Bind the username from the session
                $part_numbers[$i], 
                $shifts[$i], 
                $machineTypes[$i], 
                $machineNumbers[$i], 
                $machineStatuses[$i], 
                $transactionDates[$i] // Bind the transaction date
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
