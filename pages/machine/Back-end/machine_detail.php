<?php
        // Koneksi ke database
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $dbname = 'pdd';

        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ambil line dan machine yang dipilih
        $line = isset($_GET['line']) ? htmlspecialchars($_GET['line']) : 'Unknown Line';
        $machine = isset($_GET['machine']) ? htmlspecialchars($_GET['machine']) : 'Unknown Machine';

        // Ambil detail mesin berdasarkan line dan machine yang dipilih
        $sql = "SELECT * FROM lines_machines WHERE line_name = ? AND machine_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $line, $machine);
        $stmt->execute();
        $result = $stmt->get_result();
        $machineDetails = $result->fetch_assoc();
        ?>