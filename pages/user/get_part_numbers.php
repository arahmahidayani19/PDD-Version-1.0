<?php
// Koneksi database
$host = 'localhost'; // Ganti dengan host database Anda
$dbname = 'pdd'; // Ganti dengan nama database Anda
$username = 'root'; // Ganti dengan username Anda
$password = ''; // Ganti dengan password Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Ambil nomor halaman dari request (default ke 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50; // Jumlah data per halaman
$start = ($page - 1) * $perPage;

// Ambil parameter pencarian dari request (jika ada)
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data part number berdasarkan paginasi dan pencarian
$query = "SELECT pn FROM pn WHERE pn LIKE :search LIMIT :start, :perPage";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$partNumbers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menghitung total data untuk paginasi berdasarkan pencarian
$totalQuery = "SELECT COUNT(*) AS total FROM pn WHERE pn LIKE :search";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$totalStmt->execute();
$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($total / $perPage);

// Mengirim data dalam format JSON
echo json_encode([
    'data' => $partNumbers,
    'totalPages' => $totalPages
]);
?>
