<?php
require '../../../core/dbsys.ini';
require '../../../query/custodian.qry';

header('Content-Type: application/json');
session_start();

$modid = $_SESSION['modid'];
$custodian = new CUSTODIAN();

// If barcode is provided, fetch item by barcode
if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $itemData = $custodian->getItemByBarcode($db, $barcode);

    echo json_encode(
        $itemData
            ? ["success" => true, "items" => $itemData]
            : ["success" => false, "message" => "Item with barcode '$barcode' not found"]
    );
    exit;
}

// Fetch inventory data with summary
$data = $custodian->getInventoryWithSummary($db, $modid);
$items = $data['items'];
$summary = $data['summary'];

// Get tagged quantities
$stmt = $db->prepare("
    SELECT it_no, SUM(quantity) AS tagged_quantity
    FROM dbpis_eq_tag_details
    GROUP BY it_no
");
$stmt->execute();
$taggedQuantities = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Filter logic based on modid
$filteredItems = array_filter(
    array_map(function ($item) use ($taggedQuantities, $modid) {
        $barcode = $item['barcode'];
        $item['quantity'] = $item['quantity'];

        if (isset($taggedQuantities[$barcode])) {
            $item['quantity'] = $taggedQuantities[$barcode];
        }

        $isConsumable = strtolower($item['itemcatgrp_name']) === 'consumables';

        if ($modid == 1) {
            return isset($taggedQuantities[$barcode]) ? $item : null;
        } elseif ($modid == 6) {
            return !isset($taggedQuantities[$barcode]) ? $item : null;
        } else {
            return (!$isConsumable && !isset($taggedQuantities[$barcode])) ? $item : null;
        }
    }, $items),
    function($item) {
        return !is_null($item);
    }
);

// Final response
echo json_encode([
    "success" => true,
    "items" => array_values($filteredItems),
    "summary" => $summary
]);
