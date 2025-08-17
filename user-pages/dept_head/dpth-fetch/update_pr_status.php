<?php
session_start();
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/dept_head.qry'; // DEPT_HEAD class definition

$loggedInUser = $_SESSION['name'];
$modid = $_SESSION['modid'] ?? 0; // Get user role ID
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["prs_code"], $_POST["action"])) {
    $prs_code = $_POST["prs_code"];
    $action = $_POST["action"];
    $user_name = $_SESSION['name'] ?? 'System';
    $user_username = $_SESSION['username'] ?? 'unknown';



    $deptHead = new DEPT_HEAD();
    $result = $deptHead->handlePRAction($db, $prs_code, $action, $user_name, $modid); // Call the function

    // Log message based on modid
    $current_timestamp = date("Y-m-d H:i:s");
    if ($modid == 3) {
        $log_message = "[$current_timestamp] PRS verified successfully | PR Code: $prs_code | Verified by: $user_name ($user_username)\n";
    } elseif ($modid == 4) {
        $log_message = "[$current_timestamp] PRS approved successfully | PR Code: $prs_code | Approved by: $user_name ($user_username)\n";
    }

    error_log($log_message, 3, '../../../logs/activity.log');

    echo json_encode($result);
}
?>
