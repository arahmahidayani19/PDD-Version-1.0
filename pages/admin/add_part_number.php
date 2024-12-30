<?php
include 'Back-end/koneksi.php'; 

// Tambahkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug untuk melihat isi POST dan FILES
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $productID = $_POST['part_number'][0];

    // Initialize variables for file paths
    $wi_path = null;
    $param_path = null;
    $pack_path = null;

    // Proses Work Instruction
    if (!empty($_POST['work_instruction_path'])) {
        $wi_path = $_POST['work_instruction_path'];
    } elseif (isset($_FILES['work_instruction_file']) && $_FILES['work_instruction_file']['error'] == 0) {
        $wi_path = processFile($_FILES['work_instruction_file'], 'work_instruction');
        error_log("Work Instruction file path: " . $wi_path);
    }

    // Proses Master Parameter
    if (!empty($_POST['master_parameter_path'])) {
        $param_path = $_POST['master_parameter_path'];
    } elseif (isset($_FILES['master_parameter_file']) && $_FILES['master_parameter_file']['error'] == 0) {
        $param_path = processFile($_FILES['master_parameter_file'], 'master_parameter');
        error_log("Master Parameter file path: " . $param_path);
    }

    // Proses Packaging
    if (!empty($_POST['packaging_path'])) {
        $pack_path = $_POST['packaging_path'];
    } elseif (isset($_FILES['packaging_file']) && $_FILES['packaging_file']['error'] == 0) {
        $pack_path = processFile($_FILES['packaging_file'], 'packaging');
        error_log("Packaging file path: " . $pack_path);
    }

    // Prepare UPDATE statement with COALESCE
    $sql = "UPDATE products SET 
            work_instruction = COALESCE(?, work_instruction),
            master_parameter = COALESCE(?, master_parameter),
            packaging = COALESCE(?, packaging)
            WHERE productID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $wi_path, $param_path, $pack_path, $productID);
        
        error_log("Executing query with parameters: " . 
                  "wi_path: $wi_path, " . 
                  "param_path: $param_path, " . 
                  "pack_path: $pack_path, " . 
                  "productID: $productID");

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Product updated successfully!',
                'paths' => [
                    'work_instruction' => $wi_path,
                    'master_parameter' => $param_path,
                    'packaging' => $pack_path
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update product: ' . $stmt->error
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $conn->error
        ]);
    }

    $conn->close();
}

function processFile($file, $type) {
    $uploadDir = "../PDD/";
    
    // Buat direktori jika belum ada
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = uniqid() . '_' . $fileName; // Nama file unik
    $filePath = $uploadDir . $newFileName;

    error_log("Processing file: " . $file['name']);
    error_log("Target path: " . $filePath);

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        error_log("File uploaded successfully to: " . $filePath);
        
        // Konversi file jika perlu
        if (in_array($fileExt, ['xls', 'xlsx', 'csv', 'docx'])) {
            try {
                if (in_array($fileExt, ['xls', 'xlsx', 'csv'])) {
                    $pdfPath = convertToPDF($filePath);
                } elseif ($fileExt === 'docx') {
                    $pdfPath = convertWordToPDF($filePath);
                }
                unlink($filePath); // Hapus file asli setelah konversi
                return $pdfPath;
            } catch (Exception $e) {
                error_log("Conversion error: " . $e->getMessage());
                return $filePath; // Kembalikan path file asli jika konversi gagal
            }
        }
        return $filePath;
    } else {
        error_log("Failed to upload file: " . $file['name']);
        return null;
    }
}