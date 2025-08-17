<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

$purchaser = new PURCHASER();
$data = $purchaser->fetchAllPRs($db);

echo json_encode([
    'status' => 'success',
    'data' => $data
]);
?>
