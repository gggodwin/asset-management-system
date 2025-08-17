<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class
session_start(); // Ensure the session is started

header('Content-Type: application/json');

$supplier_name = $_POST['supplier_name'] ?? '';
$contact_name = $_POST['contact_name'] ?? '';
$contact_email = $_POST['contact_email'] ?? '';
$contact_phone = $_POST['contact_phone'] ?? '';
$address = $_POST['address'] ?? '';
date_default_timezone_set('Asia/Manila');
$purchaser = new PURCHASER();
$result = $purchaser->insertSupplier($db, $supplier_name, $contact_name, $contact_email, $contact_phone, $address);

if ($result) {
    // Prepare the log entry
    $current_timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$current_timestamp] Supplier added successfully | Supplier Name: {$supplier_name} | Added by: {$_SESSION['name']} ({$_SESSION['username']})\n";

    // Write log to activity.log
    error_log($logEntry, 3, '../../../logs/activity.log');

    echo json_encode([
        'status' => 'success',
        'message' => 'Supplier added successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to add supplier.'
    ]);
}
?>
