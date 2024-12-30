<?php
session_start();
include('Back-end/koneksi.php'); // Pastikan koneksi database

$username = $_SESSION['username'];

// Cek apakah ada file yang diunggah
if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0){
    $target_dir = "uploads/"; // Direktori untuk menyimpan gambar
    $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi tipe file gambar
    $valid_extensions = ['jpg', 'png', 'jpeg'];
    if(in_array($imageFileType, $valid_extensions)){
        // Pindahkan file ke direktori tujuan
        if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)){
            // Update path gambar di database
            $query = "UPDATE users SET profile_picture = ? WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $target_file, $username);
            if($stmt->execute()){
                // Simpan path gambar di session agar bisa digunakan di navbar
                $_SESSION['profile_picture'] = $target_file;
                header("Location: profile.php"); // Arahkan kembali ke halaman profil
                exit();
            } else {
                echo "Error saat mengupdate gambar profil!";
            }
        } else {
            echo "Gagal mengunggah file!";
        }
    } else {
        echo "Tipe file tidak valid!";
    }
} else {
    echo "Tidak ada file yang diunggah!";
}
?>
