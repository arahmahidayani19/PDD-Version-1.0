<?php
require_once 'Back-end/koneksi.php'; // Adjust this path to your DB connection script

header('Content-Type: application/json');

if (isset($_GET['line'])) {
    $line = htmlspecialchars($_GET['line']);
    $stmt = $conn->prepare("SELECT machine_name FROM lines_machines WHERE line_name = ?");
    $stmt->bind_param("s", $line);
    $stmt->execute();
    $result = $stmt->get_result();

    $machines = [];
    while ($row = $result->fetch_assoc()) {
        $machines[] = $row;
    }
    echo json_encode($machines);
}
?>
