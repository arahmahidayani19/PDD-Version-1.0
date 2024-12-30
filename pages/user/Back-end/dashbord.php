<?php

// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pdd';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil daftar line dari tabel dan urutkan berdasarkan abjad
$sql = "SELECT DISTINCT line_name FROM lines_machines ORDER BY line_name ASC";
$result = $conn->query($sql);

// Fetch all results to pass to frontend
$lines = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lines[] = $row['line_name'];
    }
}

// Close the database connection
$conn->close();
