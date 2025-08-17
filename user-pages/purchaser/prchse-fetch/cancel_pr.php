<?php
include '../../../core/dbsys.ini'; // Your DB connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prsCode = $_POST['prs_code'] ?? '';

    if (!empty($prsCode)) {
        try {
            $stmt = $db->prepare("UPDATE dbpis_prs SET approval_status = 'Canceled', updated_at = NOW() WHERE prs_code = :prsCode");
            $stmt->bindParam(':prsCode', $prsCode);
            $stmt->execute();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Missing PRS code']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
