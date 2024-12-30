<?php
include 'koneksi.php';  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $part_number = $_POST['part_number'];

    // Cek apakah part number sudah ada
    $checkSql = "SELECT COUNT(*) FROM parts WHERE part_number = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("s", $part_number);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Part number already exists!']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Work Instruction
    $wi_path = $_POST['work_instruction_path'] ?? null;
    if (isset($_FILES['work_instruction_file']) && $_FILES['work_instruction_file']['error'] == 0) {
        $wi_path = processFile($_FILES['work_instruction_file'], 'work_instruction');
    }

    // Master Parameter
    $param_path = $_POST['master_parameter_path'] ?? null;
    if (isset($_FILES['master_parameter_file']) && $_FILES['master_parameter_file']['error'] == 0) {
        $param_path = processFile($_FILES['master_parameter_file'], 'master_parameter');
    }

    // Packaging
    $pack_path = $_POST['packaging_path'] ?? null;
    if (isset($_FILES['packaging_file']) && $_FILES['packaging_file']['error'] == 0) {
        $pack_path = processFile($_FILES['packaging_file'], 'packaging');
    }

    // Insert data to the database
    $sql = "INSERT INTO parts (part_number, work_instruction, master_parameter, packaging) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $part_number, $wi_path, $param_path, $pack_path);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Part number added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add part number.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
    }

    $conn->close();
}

// Fungsi untuk memproses file
function processFile($file, $folder) {
    $uploadDir = '../../PDD/' . $folder . '/';  // Folder spesifik
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);  // Buat folder jika belum ada
    }

    $targetFile = $uploadDir . basename($file["name"]);

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return convertToPDF($targetFile);
        } elseif (in_array($extension, ['docx', 'doc'])) {
            return convertWordToPDF($targetFile);
        }
        return $targetFile;  // Return original file path if no conversion is needed
    } else {
        throw new Exception("Error moving uploaded file.");
    }
}

// Fungsi untuk mengonversi Excel/CSV ke PDF
function convertToPDF($filePath) {
    require '../../vendor/autoload.php';  // Pastikan autoload file Composer di-include
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);

    $pdfFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
    $writer->save($pdfFilePath);

    return $pdfFilePath;
}

// Fungsi untuk mengonversi Word ke PDF
function convertWordToPDF($filePath) {
    require '../../vendor/autoload.php';  // Pastikan autoload file Composer di-include
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
    $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

    $tempHtmlFile = tempnam(sys_get_temp_dir(), 'phpword_') . '.html';
    $htmlWriter->save($tempHtmlFile);

    // Convert HTML to PDF
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml(file_get_contents($tempHtmlFile));
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
    file_put_contents($pdfFilePath, $dompdf->output());

    unlink($tempHtmlFile);  // Hapus file HTML sementara
    return $pdfFilePath;
}
?>
