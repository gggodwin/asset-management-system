<?php
include '../../../core/dbsys.ini';
include '../../../query/custodian.qry';

header('Content-Type: application/json');

$custodian = new CUSTODIAN();
$data = $custodian->getAssetManagement($db);

echo json_encode([
    'status' => 'success',
    'data' => $data
]);
?>
