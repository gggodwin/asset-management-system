<?php
// Include the database connection
include_once '../../../core/dbsys.ini';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prs_code = $_POST['prs_code'] ?? null;

    if ($prs_code) {
        try {
            $query = "UPDATE dbpis_prs SET approval_status = 'Approved' WHERE prs_code = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$prs_code]);

            echo json_encode(['success' => true, 'message' => 'PR re-approved successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing PRS code']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
