<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prs_code'])) {
    $prsCode = $_POST['prs_code'];

    $purchaser = new PURCHASER();
    $result = $purchaser->fetchPRDetailsByCode($db, $prsCode);

    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No PRS details found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
