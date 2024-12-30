<?php
include 'Back-end/koneksi.php';  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the machine name and line name from the form
    $machine_name = $_POST['machine_name'];

    // Fetch the line name based on the selected machine name
    $line_name = '';  // Initialize line_name variable
    $sql = "SELECT line_name FROM lines_machines WHERE machine_name = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $machine_name);
        $stmt->execute();
        $stmt->bind_result($line_name);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Check if the machine name already exists
    $checkSql = "SELECT COUNT(*) FROM machine_documents WHERE machine_name = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("s", $machine_name);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Machine name already exists!']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
        exit();
    }

    // Process file uploads (similar to your current logic)
    $master_molding_path = $_POST['Master_Molding_data_path'] ?? null;
    if (isset($_FILES['Master_Molding_data_file']) && $_FILES['Master_Molding_data_file']['error'] == 0) {
        $master_molding_path = processFile($_FILES['Master_Molding_data_file'], 'master_molding');
    }

    // Other file processing for current_parameter_path, first_piece_path, visual_mapping_path...
    // Current Parameter
    $current_parameter_path = $_POST['current_parameter_path'] ?? null;
    if (isset($_FILES['current_parameter_file']) && $_FILES['current_parameter_file']['error'] == 0) {
        $current_parameter_path = processFile($_FILES['current_parameter_file'], 'current_parameter');
    }

    // First Piece Approval
    $first_piece_path = $_POST['first_piece_path'] ?? null;
    if (isset($_FILES['first_piece_file']) && $_FILES['first_piece_file']['error'] == 0) {
        $first_piece_path = processFile($_FILES['first_piece_file'], 'first_piece');
    }

    // Visual Mapping
    $visual_mapping_path = $_POST['visual_mapping_path'] ?? null;
    if (isset($_FILES['visual_mapping_file']) && $_FILES['visual_mapping_file']['error'] == 0) {
        $visual_mapping_path = processFile($_FILES['visual_mapping_file'], 'visual_mapping');
    }

    // Insert data into the machine_documents table
    $sql = "INSERT INTO machine_documents (machine_name, line_name, master_molding_data_path, current_parameter_path, first_piece_path, visual_mapping_path) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $machine_name, $line_name, $master_molding_path, $current_parameter_path, $first_piece_path, $visual_mapping_path);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Machine data added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add machine data.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error, please try again later.']);
    }

    $conn->close();
}

// Function to process the uploaded file (same as before)
function processFile($file, $folder) {
    $uploadDir = '../../PDD/' . $folder . '/';  // Folder path
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);  // Create the folder if it doesn't exist
    }

    $targetFile = $uploadDir . basename($file["name"]);

    // Move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return convertToPDF($targetFile);
        } elseif (in_array($extension, ['docx', 'doc'])) {
            return convertWordToPDF($targetFile);
        }
        return $targetFile;  // Return the original file path if no conversion is needed
    } else {
        throw new Exception("Error moving uploaded file.");
    }
}

// Function to convert Excel/CSV to PDF (same as before)
function convertToPDF($filePath) {
    require '../../vendor/autoload.php';  // Ensure Composer's autoload is included
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);

    $pdfFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.pdf';
    $writer->save($pdfFilePath);

    return $pdfFilePath;
}

// Function to convert Word to PDF (same as before)
function convertWordToPDF($filePath) {
    require '../../vendor/autoload.php';  // Ensure Composer's autoload is included
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

    unlink($tempHtmlFile);  // Delete the temporary HTML file
    return $pdfFilePath;
}
?>
