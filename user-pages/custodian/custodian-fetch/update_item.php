<?php
require_once('../../../core/dbsys.ini'); // Adjust the path if needed
require_once('../../../query/custodian.qry'); // Include the custodian query file

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the incoming JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the required fields
    if (!isset($input['barcode']) || empty($input['barcode'])) {
        echo json_encode(['success' => false, 'message' => 'Barcode is required']);
        exit;
    }

    // Collect data from the request
    $barcode = $input['barcode'];
    $itemName = $input['itemName'] ?? '';
    $itemBrand = $input['itemBrand'] ?? '';
    $itemCategory = $input['itemCategory'] ?? '';
    $itemStock = $input['itemStock'] ?? 0;

    $custodian = new CUSTODIAN(); // Adjust the class name if needed
    $updateResult = $custodian->updateItemBarcode($db, $barcode, $itemName, $itemBrand, $itemCategory, $itemStock, $itemStock, ''); // Passing empty for 'safety_stock' and 'units'

    // Return the result of the update operation
    echo json_encode($updateResult);
} else {
    // Handle invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
