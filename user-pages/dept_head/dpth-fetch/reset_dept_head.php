<?php
session_start();
include '../../../core/dbsys.ini';
include '../../../query/dept_head.qry';

if (!isset($_POST['prs_code'])) {
    echo json_encode(["success" => false, "message" => "Missing PR Code"]);
    exit;
}

$prs_code = $_POST['prs_code'];
$modid = $_SESSION['modid'] ?? null;

$deptHead = new DEPT_HEAD();

if ($modid == 4) {
    // If user is an Admin, reset approval_status and approved_by, retain dept_head
    $result = $deptHead->resetApprovalOnly($db, $prs_code);
} else {
    // Otherwise, reset dept_head
    $result = $deptHead->resetDeptHead($db, $prs_code);
}

echo json_encode($result);
?>
