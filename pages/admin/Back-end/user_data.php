<?php
include 'koneksi.php';

// Handle the GET request for retrieving user data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT id, username, password, role FROM users";
    $result = mysqli_query($conn, $sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'id' => htmlspecialchars($row['id']),
                'username' => htmlspecialchars($row['username']),
                'password' => htmlspecialchars($row['password']),
                'role' => htmlspecialchars($row['role']),
            ];
        }
    }

    // Return JSON response
    echo json_encode($users);
    $conn->close();
}
?>
