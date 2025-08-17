<?php
require_once '../../../core/dbsys.ini'; // Ensure this is correct
require_once '../../../query/custodian.qry'; // Include the custodian query file
date_default_timezone_set('Asia/Manila');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize input
    $barcode = isset($_POST['barcode']) ? trim($_POST['barcode']) : null;
    $particular = isset($_POST['particular']) ? trim($_POST['particular']) : '';
    $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;  // Replaced current_stock
    $units = isset($_POST['units']) ? trim($_POST['units']) : '';

    // Log received data for debugging
    error_log("Received data - Barcode: $barcode, Particular: $particular, Brand: $brand, Category: $category, Quantity: $quantity, Units: $units");

    if (empty($barcode)) {
        // Log error when barcode is missing
        error_log("Error: Barcode is required");
        echo json_encode(['success' => false, 'message' => 'Barcode is required']);
        exit;
    }

    // Instantiate the CUSTODIAN class and call the updateItem function
    $custodian = new CUSTODIAN(); // Adjusted to use your class name
    $updateResult = $custodian->updateItem($db, $barcode, $particular, $brand, $category, $quantity, $units);  // Updated to use quantity

    $updated_by_name = $_SESSION['name'] ?? 'Unknown';
    $updated_by_role = $_SESSION['username'] ?? 'unknown_user';

    // Log the result of the update operation
    $current_timestamp = date('Y-m-d H:i:s');
    if ($updateResult) {
        $log_message = "[$current_timestamp] Item updated: Barcode: $barcode | Particular: $particular | Brand: $brand | Category: $category | Quantity: $quantity | Updated by: $updated_by_name ($updated_by_role)\n";

        error_log($log_message, 3, '../../../logs/activity.log'); // Log success
    } else {
        $log_message = "[$current_timestamp] Item Update Failed | Barcode: $barcode | Particular: $particular | Brand: $brand | Category: $category | Quantity: $quantity\n";
        error_log($log_message, 3, '../../../logs/activity.log'); // Log failure
    }

    // Return the result of the update operation
    echo json_encode($updateResult);
} else {
    // Log error when invalid request method is used
    error_log("Error: Invalid request method");
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
