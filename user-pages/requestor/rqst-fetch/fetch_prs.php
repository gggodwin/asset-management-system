<?php
require_once '../../../query/requestor.qry';
require_once '../../../core/dbsys.ini';
session_start();

$requestor = new REQUESTOR();
$modid = isset($_SESSION['modid']) ? (int)$_SESSION['modid'] : null;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null; // <-- Get current user's name

if ($modid === 0 || $modid === 4) {
    $result = $requestor->fetchAllPRs($db);
    $mode = "Admin/Purchase Custodian";
} elseif ($modid === 3) {
    // Call the updated function for department heads
    $result = $requestor->fetchPendingPRsForDeptHead($db, $name);
    $mode = "Department Head";
} else {
    $result = $requestor->fetchPRsByRequestor($db);
    $mode = "Requestor";
}

echo json_encode([
    "prs" => $result,
    "modid" => $modid,
    "mode" => $mode
]);
?>
