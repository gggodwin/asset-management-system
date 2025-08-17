<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

$purchaser = new PURCHASER();
$suppliers = $purchaser->fetchAllSuppliers($db);

echo json_encode(['data' => $suppliers]);
