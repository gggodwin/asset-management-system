<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

// Instantiate the PURCHASER class
$purchaser = new PURCHASER();

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract the PRS code and purchase remarks from the incoming data
$prsCode = $data['prs_code'];
$purchaseRemarks = $data['purchase_remarks'];

// Call the function to update the purchase remarks
$updateStatus = $purchaser->updatePurchaseRemarks($db, $prsCode, $purchaseRemarks);

// Return a response based on the result
if ($updateStatus) {
    echo json_encode(['message' => 'Purchase remarks updated successfully']);
} else {
    echo json_encode(['message' => 'Failed to update purchase remarks']);
}
?>
