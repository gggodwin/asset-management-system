<?php
session_start();
include '../../../core/dbsys.ini'; // Make sure this includes your database connection
include '../../../query/dept_head.qry';
if (!isset($_SESSION['name'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$loggedInUser = $_SESSION['name'];

// 1. Create an instance of the DEPT_HEAD class:
$deptHead = new DEPT_HEAD();

// 2. Call the function, passing the database connection ($db) and user:
$approvedPRs = $deptHead->getApprovedPRs($db, $loggedInUser);

// 3. Encode and output the JSON:
echo json_encode($approvedPRs);

?>