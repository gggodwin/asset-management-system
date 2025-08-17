<?php
include '../../../core/dbsys.ini'; // DB connection
include '../../../query/purchaser.qry'; // Include your PURCHASER class

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve POST data
        $itemId = $_POST['item_id'] ?? null;
        $supplierId = $_POST['supplier_id'] ?? null;

        // Check if any of the required fields are missing
        if (!$itemId || !$supplierId) {
            echo json_encode('missing_fields');
            exit;
        }

        // Instantiate your PURCHASER class
        $purchaser = new PURCHASER();

        // Call the insert function
        $result = $purchaser->insertItemSupplier($db, $itemId, $supplierId);

        if ($result) {
            echo json_encode('success');
        } else {
            echo json_encode('fail');
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode('invalid_request');
}
