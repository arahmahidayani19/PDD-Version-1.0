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

// Ambil line yang dipilih dari URL (jika tersedia)
$line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';

// Ambil mesin berdasarkan line yang dipilih dan urutkan berdasarkan abjad dan angka
$sql = "SELECT machine_name FROM lines_machines WHERE line_name = ? ORDER BY LENGTH(machine_name), machine_name ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $line);
$stmt->execute();
$result = $stmt->get_result();

// Simpan data dalam array untuk dikembalikan ke file front-end
$machines = [];
while ($row = $result->fetch_assoc()) {
    $machines[] = $row['machine_name'];
}

$stmt->close();
$conn->close();

// Kembalikan array mesin
return $machines;
?>
