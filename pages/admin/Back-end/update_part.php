<?php
include 'koneksi.php';  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the part number and other data
    $productID = $_POST['productID'];
    $workInstructionPath = $_POST['work_instruction_path'] ?? null;
    $masterParameterPath = $_POST['master_parameter_path'] ?? null;
    $packagingPath = $_POST['packaging_path'] ?? null;
    $id = $_POST['id'];  // The product ID for the record to update

    // Handle file uploads and paths
    if (isset($_FILES['work_instruction_file']) && $_FILES['work_instruction_file']['error'] == 0) {
        $workInstructionPath = processFile($_FILES['work_instruction_file'], 'work_instruction');
    }

    if (isset($_FILES['master_parameter_file']) && $_FILES['master_parameter_file']['error'] == 0) {
        $masterParameterPath = processFile($_FILES['master_parameter_file'], 'master_parameter');
    }

    if (isset($_FILES['packaging_file']) && $_FILES['packaging_file']['error'] == 0) {
        $packagingPath = processFile($_FILES['packaging_file'], 'packaging');
    }

    // Update the record in the database
    $updateSql = "UPDATE products SET productID = ?, work_instruction = ?, master_parameter = ?, packaging = ? WHERE id = ?";
    if ($stmt = $conn->prepare($updateSql)) {
        $stmt->bind_param("sssss", $productID, $workInstructionPath, $masterParameterPath, $packagingPath, $id);

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

// Function to process files (uploads and convert if necessary)
function processFile($file, $type) {
    $uploadDir = "../PDD/";  // Directory for storing uploaded files
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $filePath = $uploadDir . basename($fileName);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($fileTmpName, $filePath)) {
        // If the file is an Excel/CSV or Word, convert it to PDF
        if (in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['xls', 'xlsx', 'csv'])) {
            return convertToPDF($filePath);
        } elseif (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) == 'docx') {
            return convertWordToPDF($filePath);
        }
        return $filePath;  // Return the file path if no conversion needed
    }
    return null;  // Return null if file upload failed
}

// Function to convert Excel/CSV files to PDF
function convertToPDF($filePath) {
    require '../vendor/autoload.php';  // Ensure PhpSpreadsheet is included
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);

    $pdfFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
    $writer->save($pdfFilePath);

    return $pdfFilePath;
}

// Function to convert Word documents to PDF
function convertWordToPDF($filePath) {
    require '../vendor/autoload.php';  // Ensure PhpWord and Dompdf are included
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
