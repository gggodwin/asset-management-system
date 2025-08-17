<?php
include '../../../core/dbsys.ini';
include '../../../query/system.qry';

header('Content-Type: application/json');

$system = new SYSTEM();

// Execute and output
$employees = $system->fetchAllEmployees($db);
echo json_encode([
    'success' => !empty($employees),
    'data' => $employees
]);
?>
