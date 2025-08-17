<?php
require_once '../../../query/requestor.qry';
require_once '../../../core/dbsys.ini';

session_start();
date_default_timezone_set('Asia/Manila');

$requestor = new REQUESTOR();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $items = [];
    foreach ($_POST['item_code'] as $index => $item_code) {
        $items[] = [
            'item_code' => $item_code,
            'item_description' => $_POST['item_description'][$index],
            'quantity' => $_POST['quantity'][$index],
            'unit_type' => $_POST['unit_type'][$index]
        ];
    }

    $result = $requestor->updatePurchaseRequisition(
        $db,
        $_POST['prs_code'],
        $_POST['requested_by'],
        $_POST['department'],
        $_POST['unit_id'],
        $_POST['date_requested'],
        $_POST['date_needed'],
        $_POST['remarks'],
        $items
    );

    if ($result["success"]) {
        // Get current timestamp
        $current_timestamp = date('Y-m-d H:i:s');

        // Log entry format
        $logEntry = "[$current_timestamp] PRS updated successfully | PR Code: {$_POST['prs_code']} | Updated by: {$_SESSION['name']} ({$_SESSION['username']})\n";

        // Write log to activity.log
        error_log($logEntry, 3, '../../../logs/activity.log');
    }

    echo json_encode($result);
}
?>
