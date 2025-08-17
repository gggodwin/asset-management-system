<?php
require '../../../core/dbsys.ini';
require '../../../query/custodian.qry'; // Include the custodian.qry file

header('Content-Type: application/json');

try {
    $custodian = new CUSTODIAN();

    // Call the method to fetch transfer records
    $transfers = $custodian->getTransfers($db);

    echo json_encode(["success" => true, "transfers" => $transfers]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
