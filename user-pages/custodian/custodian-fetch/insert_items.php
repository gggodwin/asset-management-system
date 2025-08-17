<?php
require '../../../core/dbsys.ini';
require '../../../query/custodian.qry'; // Include the custodian.qry file

session_start();

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data
    $barcode = $_POST['barcode'];
    $particular = $_POST['particular'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];  // Use quantity instead of current stock and safety stock

    // Initialize variables for optional fields
    $units = $_POST['units'];  // Units are still required

    $added_by_name = $_SESSION['name'] ?? 'Unknown';
    $added_by_username = $_SESSION['username'] ?? 'unknown_user';
    $custodian = new CUSTODIAN();

    // Call the addItem function from custodian.qry
    $result = $custodian->addItem($db, $barcode, $particular, $brand, $category, $quantity, $units);

    // Log the result of the addItem operation
    $current_timestamp = date('Y-m-d H:i:s');
    if ($result) {
        // Log success
        $log_message = "[$current_timestamp] Item added successfully: Barcode: $barcode | Particular: $particular | Brand: $brand  | Added by: $added_by_name ($added_by_username)\n";
        error_log($log_message, 3, '../../../logs/activity.log');
    } else {
        // Log failure
        $log_message = "[$current_timestamp] Failed to add item - Barcode: $barcode, Particular: $particular, Brand: $brand, Category: $category, Quantity: $quantity\n";
        error_log($log_message, 3, '../../../logs/activity.log');
    }

    // Return the result
    echo json_encode([
        "success" => $result,
        "message" => $result ? "Item added successfully." : "Failed to add item."
    ]);
}
?>
