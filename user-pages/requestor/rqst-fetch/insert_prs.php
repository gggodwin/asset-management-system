<?php

require_once '../../../query/requestor.qry';
require_once '../../../core/dbsys.ini';

session_start();
date_default_timezone_set('Asia/Manila');

$requestor = new REQUESTOR();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input data
    $requested_by = filter_input(INPUT_POST, 'requested_by', FILTER_SANITIZE_STRING);
    $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);
    $unit_id = filter_input(INPUT_POST, 'unit_id', FILTER_SANITIZE_NUMBER_INT); // 🔹 New line
    $date_requested = filter_input(INPUT_POST, 'date_requested', FILTER_SANITIZE_STRING);
    $date_needed = filter_input(INPUT_POST, 'date_needed', FILTER_SANITIZE_STRING);
    $approval_status = "Pending";
    $remarks = filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_STRING);

    if (!$requested_by || !$department || !$unit_id || !$date_requested || !$date_needed) {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
        exit();
    }

    $added_by_name = $_SESSION['name'] ?? 'Unknown';
    $added_by_username = $_SESSION['username'] ?? 'unknown_user';
    $current_timestamp = date('[Y-m-d H:i:s]');

    try {
        $db->beginTransaction();

        // 🔐 Generate PR Code safely (fetch max existing code and increment)
        $stmt = $db->query("SELECT MAX(prs_code) AS latest_code FROM dbpis_prs");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $prs_code = $row['latest_code'] + 1;

        // Insert PR main record (Updated to include unit_id)
        $requestor->insertPR($db, $prs_code, $requested_by, $department, $unit_id, $date_requested, $date_needed, $approval_status, $remarks);

        // PR Details
        $items = [];

        if (!empty($_POST['item_code'])) {
            foreach ($_POST['item_code'] as $index => $item_code) {
                $item_description = $_POST['item_description'][$index] ?? "";
                $quantity = $_POST['quantity'][$index] ?? 0;
                $unit_type = $_POST['unit_type'][$index] ?? "";

                if ($item_code && $quantity > 0) {
                    $requestor->insertPRDetails($db, $prs_code, $item_code, $item_description, $quantity, $unit_type);
                    $items[] = [
                        'item_code' => $item_code,
                        'item_description' => $item_description,
                        'quantity' => $quantity,
                        'unit_type' => $unit_type
                    ];
                }
            }
        }

        $db->commit();

        // Logging
        $log_message = "$current_timestamp PRS Inserted | PR Code: $prs_code | Department: $department | Unit ID: $unit_id | Added by: $added_by_name ($added_by_username)\n";
        error_log($log_message, 3, '../../../logs/activity.log');

        echo json_encode([
            "success" => true,
            "message" => "Purchase Requisition Added Successfully!",
            "prs_code" => $prs_code,
            "added_by" => "$added_by_name ($added_by_username)",
            "items" => $items
        ]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
    exit();
}
