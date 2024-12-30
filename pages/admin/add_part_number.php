<?php
include 'Back-end/koneksi.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil productID dari dropdown (dianggap dropdown memberikan productID)
    $productID = $_POST['part_number'][0];  // Menggunakan indeks 0 jika hanya satu productID dipilih

    // Cek apakah productID sudah ada di database
    $checkSql = "SELECT COUNT(*) FROM products WHERE productID = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("s", $productID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Product ID not found in the database!']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Initialize variables for file paths
    $wi_path = $_POST['work_instruction_path'] ?? null;
    $param_path = $_POST['master_parameter_path'] ?? null;
    $pack_path = $_POST['packaging_path'] ?? null;

    // Check and upload Work Instruction file
    if (isset($_FILES['work_instruction_file']) && $_FILES['work_instruction_file']['error'] == 0) {
        $wi_path = processFile($_FILES['work_instruction_file'], 'work_instruction');
    }

    // Check and upload Master Parameter file
    if (isset($_FILES['master_parameter_file']) && $_FILES['master_parameter_file']['error'] == 0) {
        $param_path = processFile($_FILES['master_parameter_file'], 'master_parameter');
    }

    // Check and upload Packaging file
    if (isset($_FILES['packaging_file']) && $_FILES['packaging_file']['error'] == 0) {
        $pack_path = processFile($_FILES['packaging_file'], 'packaging');
    }

    // Update the product record with the file paths (only if paths are not empty)
    $sql = "UPDATE products SET work_instruction = ?, master_parameter = ?, packaging = ? WHERE productID = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters and execute the statement
        $stmt->bind_param("ssss", $wi_path, $param_path, $pack_path, $productID);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
    }

    $conn->close();
}

// Function for processing files (converting to PDF if necessary)
function processFile($file, $type) {
    $uploadDir = "../PDD/";
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    // Move file to designated directory
    if (move_uploaded_file($fileTmpName, $filePath)) {
        // If the file is an Excel/CSV or Word, convert to PDF
        if (in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['xls', 'xlsx', 'csv'])) {
            return convertToPDF($filePath);
        } elseif (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'docx') {
            return convertWordToPDF($filePath);
        }
        return $filePath;  // Return the file path if no conversion needed
    }
    return null;
}

// Function to convert Excel/CSV to PDF
function convertToPDF($filePath) {
    require '../vendor/autoload.php';  // Make sure autoload is included
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);

    $pdfFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
    $writer->save($pdfFilePath);

    return $pdfFilePath;
}

// Function to convert Word document to PDF
function convertWordToPDF($filePath) {
    require '../vendor/autoload.php';  // Make sure autoload is included
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

    unlink($tempHtmlFile);  // Delete temporary HTML file
    return $pdfFilePath;
}
?>