<?php
require '../../../core/dbsys.ini';
require '../../../query/custodian.qry'; // Include your query file

header('Content-Type: application/json');

$it_no = $_GET['it_no'] ?? '';

if (empty($it_no)) {
    echo json_encode(["success" => false, "message" => "Item number is required."]);
    exit;
}

try {
    $custodian = new CUSTODIAN();

    // Fetch data from your functions or write direct queries
    $prs = $custodian->getItemPRSHistory($db, $it_no);
    $rr = $custodian->getItemRRHistory($db, $it_no);
    $eq = $custodian->getItemEQHistory($db, $it_no);
    $transfer = $custodian->getItemTransferHistory($db, $it_no);

    echo json_encode([
        "success" => true,
        "prs" => $prs,
        "rr" => $rr,
        "eq" => $eq,
        "transfer" => $transfer
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
