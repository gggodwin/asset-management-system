<?php
include '../../../core/dbsys.ini'; // your PDO connection file
require '../../../query/custodian.qry'; // Include the custodian.qry file

header('Content-Type: application/json'); // Ensure JSON output
session_start();
// Instantiate the CUSTODIAN class
$custodian = new CUSTODIAN();
date_default_timezone_set('Asia/Manila');
$current_timestamp = date('Y-m-d H:i:s');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$deleted_by_name = $_SESSION['name'] ?? 'Unknown';
$deleted_by_username = $_SESSION['username'] ?? 'unknown_user';
// Check if barcode is provided
if (isset($data['barcode'])) {
    $barcode = $data['barcode']; // Get the barcode from the POST body

    // Call the delete function
    $deleteResult = $custodian->deleteItemByBarcode($db, $barcode);

    if ($deleteResult) {
        // Log success for deleting the item
        $log_message = "[$current_timestamp] Item deleted successfully: Barcode: $barcode | Deleted by: $deleted_by_name ($deleted_by_username)\n";
        error_log($log_message, 3, '../../../logs/activity.log');

        // Return success response
        echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
    } else {
        // Return error response if delete fails
        echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
    }
} else {
    // If 'barcode' is not set in the request, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request. Barcode not provided.']);
}
?>
