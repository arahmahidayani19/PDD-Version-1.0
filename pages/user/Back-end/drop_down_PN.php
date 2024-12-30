<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'pdd';

$connection = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

$searchTerm = $_GET['term'] ?? ''; // Mendapatkan input pencarian dari AJAX
$sql = "SELECT id, pn FROM pn WHERE pn LIKE ? LIMIT 10";
$stmt = $connection->prepare($sql);
$searchTerm = "%" . $searchTerm . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ['id' => $row['id'], 'text' => $row['pn']];
}

echo json_encode($data); // Mengembalikan data dalam format JSON
$stmt->close();
$connection->close();
?>
