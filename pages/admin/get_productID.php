<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menerima input pencarian dari Select2
$search = $_POST['search'] ?? '';

// Query ke database dengan filter pencarian
$sql = "SELECT productID FROM products WHERE productID LIKE ?";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Format hasil untuk Select2
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['productID'], // Nilai yang dikembalikan ke server
        'text' => $row['productID'] // Teks yang ditampilkan di dropdown
    ];
}

// Mengembalikan data dalam format JSON
echo json_encode($data);
$stmt->close();
$conn->close();
