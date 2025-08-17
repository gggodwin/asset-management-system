<?php
require_once '../../../core/dbsys.ini'; // Ensure the database connection is included
require_once '../../../query/purchaser.qry'; // Include the purchase query class
session_start(); // Ensure session is started for logging

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $contact_name = $_POST['contact_name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    // Validate required fields
    if (
        empty($supplier_id) || empty($supplier_name) || empty($contact_name) || empty($contact_email) ||
        empty($contact_phone) || empty($address) || empty($status)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $purchase = new PURCHASER();
    $result = $purchase->updateSupplier($db, $supplier_id, $supplier_name, $contact_name, $contact_email, $contact_phone, $address, $status);

    if ($result) {
        // Prepare the log entry
        $current_timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$current_timestamp] Supplier updated successfully | Supplier ID: {$supplier_id} | Supplier Name: {$supplier_name} | Status: {$status} | Updated by: {$_SESSION['name']} ({$_SESSION['username']})\n";

        // Write log to activity.log
        error_log($logEntry, 3, '../../../logs/activity.log');

        echo json_encode(['status' => 'success', 'message' => 'Supplier updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update supplier.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
