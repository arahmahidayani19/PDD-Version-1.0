<?php
ini_set('memory_limit', '1024M');
$filePath = '../daily.json';

// Cek jika file ada dan bisa dibaca
if (!file_exists($filePath) || !is_readable($filePath)) {
    echo json_encode(['error' => 'Unable to read data file.']);
    exit;
}

$jsonData = file_get_contents($filePath);
$entries = json_decode($jsonData, true);

$partnumber = [];
$moldrepair = [];
$taskStatuses = [];
$dateRange = '';
$workflowSteps = []; // Array untuk menyimpan langkah terakhir per workflowId

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateRange = $_POST['dateRange'];
    list($startDate, $endDate) = getDateRange($dateRange);
    
    // Debugging: Cetak rentang tanggal yang diambil
    error_log("Start Date: $startDate, End Date: $endDate");

    // Validasi rentang tanggal untuk custom date
    if ($dateRange === 'customDate' && (!isValidDate($startDate) || !isValidDate($endDate))) {
        echo json_encode(['error' => 'Invalid date format.']);
        exit;
    }

    if ($entries !== null) {
        foreach ($entries as $entry) {
            if (isset($entry['ListTaskRecord']) && is_array($entry['ListTaskRecord'])) {
                foreach ($entry['ListTaskRecord'] as $taskRecord) {
                    $workflowId = $taskRecord['WorkflowId'] ?? null;
                    $stepId = $taskRecord['StepId'] ?? null;

                    if ($workflowId !== null && $stepId !== null) {
                        // Cek apakah ini adalah step terakhir untuk workflowId ini
                        if (!isset($workflowSteps[$workflowId]) || $stepId > $workflowSteps[$workflowId]['StepId']) {
                            // Simpan step terakhir dan task record
                            $workflowSteps[$workflowId] = [
                                'StepId' => $stepId,
                                'TaskRecord' => $taskRecord // Simpan seluruh task record
                            ];
                        }
                    }
                }
            }
        }

        // Proses langkah terakhir yang sudah dikumpulkan
        foreach ($workflowSteps as $workflowStep) {
            $taskRecord = $workflowStep['TaskRecord'];
            $components = $taskRecord['UIComponentEntityList'] ?? [];
            foreach ($components as $component) {
                if ($component['Label'] === 'Received Date') {
                    $receivedDate = DateTime::createFromFormat('d/m/Y', $component['Data1']);
                    if ($receivedDate) {
                        $formattedDate = $receivedDate->format('Y-m-d');
                        // Debugging: Cetak tanggal yang diformat
                        error_log("Formatted Date: $formattedDate");
                        
                        if ($formattedDate >= $startDate && $formattedDate <= $endDate) {
                            $status = $taskRecord['TaskRecordStatus'] ?? 'Unknown';
                            if (!in_array($status, $taskStatuses)) {
                                $taskStatuses[] = $status;
                            }

                            foreach ($taskRecord['UIComponentEntityList'] as $component) {
                                if ($component['Label'] === 'Mold Number') {
                                    $partno = $component['Data1'] ?? '';
                                    if (!in_array($partno, $partnumber)) {
                                        $partnumber[] = $partno;
                                    }
                                }
                                if ($component['Label'] === 'Mold Repair Repair ID') {
                                    $noTRF = $component['Data1'] ?? '';
                                    if (!in_array($noTRF, $moldrepair)) {
                                        $moldrepair[] = $noTRF;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // Preparing response data for AJAX
    $response = [
        'partNoOptions' => '',
        'noTRFOptions' => '',
        'taskOptions' => ''
    ];

    foreach ($partnumber as $partno) {
        $response['partNoOptions'] .= '<option value="' . htmlspecialchars($partno) . '">' . htmlspecialchars($partno) . '</option>';
    }

    foreach ($moldrepair as $noTRF) {
        $response['noTRFOptions'] .= '<option value="' . htmlspecialchars($noTRF) . '">' . htmlspecialchars($noTRF) . '</option>';
    }

    foreach ($taskStatuses as $status) {
        $response['taskOptions'] .= '<option value="' . htmlspecialchars($status) . '">' . htmlspecialchars($status) . '</option>';
    }

    echo json_encode($response);
}

function getDateRange($range) {
    $startDate = $endDate = '';
    switch ($range) {
        case 'thisWeek':
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
            break;
        case 'lastWeek':
            $startDate = date('Y-m-d', strtotime('monday last week'));
            $endDate = date('Y-m-d', strtotime('sunday last week'));
            break;
        case 'thisMonth':
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            break;
        case 'lastMonth':
            $startDate = date('Y-m-01', strtotime('first day of last month'));
            $endDate = date('Y-m-t', strtotime('last day of last month'));
            break;
        case 'customDate':
            $startDate = $_POST['fromDate'] ?? '';
            $endDate = $_POST['toDate'] ?? '';
            break;
    }
    return [$startDate, $endDate];
}

// Fungsi untuk memvalidasi format tanggal
function isValidDate($date) {
    return DateTime::createFromFormat('Y-m-d', $date) !== false;
}
?>
