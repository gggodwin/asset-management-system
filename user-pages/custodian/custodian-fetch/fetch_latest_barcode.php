<?php
require '../../../core/dbsys.ini';
require '../../../query/custodian.qry'; // Include the custodian.qry file

header('Content-Type: application/json');

try {
    // Instantiate the CUSTODIAN class
    $custodian = new CUSTODIAN();

    // Call the function to get the next barcode
    $nextBarcode = $custodian->getNextBarcode($db);

    // Return the result
    echo json_encode(["success" => true, "latest_barcode" => $nextBarcode]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
