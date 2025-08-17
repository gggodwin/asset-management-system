<?php
require '../../../core/dbsys.ini'; // Ensure correct PDO connection
require '../../../query/custodian.qry'; // Include the custodian.qry file

header('Content-Type: application/json'); // Ensure JSON output

// Instantiate the CUSTODIAN class
$custodian = new CUSTODIAN();

if (isset($_GET['barcode'])) {
    // Fetch a single item by barcode (only if it's a Consumable)
    $barcode = $_GET['barcode'];
    $item = $custodian->getConsumableItemByBarcode($db, $barcode);

    if ($item) {
        echo json_encode(["success" => true, "item" => $item]);
    } else {
        echo json_encode(["success" => false, "message" => "Item not found or not a Consumable."]);
    }
} else {
    // Fetch only Consumables
    $result = $custodian->getAllConsumableItemsWithSummary($db);
    echo json_encode(["success" => true, "items" => $result['items'], "summary" => $result['summary']]);
}
?>
