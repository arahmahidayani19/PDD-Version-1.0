<?php
// Ambil path file yang diinginkan dari parameter GET
$path = isset($_GET['path']) ? urldecode($_GET['path']) : '';

if (!$path || !file_exists($path)) {
    die('File not found.');
}

// Dapatkan ekstensi file
$ext = pathinfo($path, PATHINFO_EXTENSION);

// Setel header yang tepat berdasarkan jenis file
switch ($ext) {
    case 'pdf':
        header('Content-Type: application/pdf');
        break;
    case 'xlsx':
    case 'xls':
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        break;
    default:
        die('Unsupported file type.');
}

// Kirim konten file untuk ditampilkan
readfile($path);
exit;
?>
