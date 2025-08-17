<?php
session_start();
require_once '../../../query/requestor.qry';
require_once '../../../core/dbsys.ini';

$requestor = new REQUESTOR();
$requested_by = $_SESSION['name'];  // User's name
$modid = $_SESSION['modid'];  // Get modid from session

$stats = $requestor->getUserPRStats($db, $requested_by, $modid);

echo json_encode([
    'total_prs' => $stats['total_prs'] ?? 0,
    'pending_prs' => $stats['pending_prs'] ?? 0,
    'approved_prs' => $stats['approved_prs'] ?? 0,
    'rejected_prs' => $stats['rejected_prs'] ?? 0
]);
?>
