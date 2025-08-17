<?php
require '../../../core/dbsys.ini'; // Ensure correct PDO connection
require '../../../query/custodian.qry'; // Include the custodian.qry file

header('Content-Type: application/json'); // Ensure JSON output

session_start(); // Start session to access modid
$modid = $_SESSION['modid']; // Get user role

// Instantiate the CUSTODIAN class
$custodian = new CUSTODIAN();

if (isset($_GET['barcode'])) {
    // Fetch a single item by barcode
    $barcode = $_GET['barcode'];
    $item = $custodian->getItemByBarcode($db, $barcode);
    echo json_encode(["success" => true, "item" => $item]);
} else {
    // Fetch all items
    $result = $custodian->getAllItemsWithSummary($db);

    // Get tagged quantities
    $stmt = $db->prepare("
        SELECT it_no, SUM(quantity) as tagged_quantity
        FROM dbpis_eq_tag_details
        GROUP BY it_no
    ");
    $stmt->execute();
    $taggedQuantities = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Filter items and adjust quantities
    $filteredItems = array_map(function ($item) use ($taggedQuantities, $modid) {
        $barcode = $item['barcode'];
        $originalQuantity = $item['quantity'];

        // Adjust quantity if item is tagged
        if (isset($taggedQuantities[$barcode])) {
            $item['quantity'] -= $taggedQuantities[$barcode];
        }

        // Exclude items with zero or negative remaining quantity
        if ($item['quantity'] <= 0) {
            return null; // Remove this item from the filtered array
        }

        // If user is not modid 6, filter out consumables
        if ($modid != 6 && strtolower($item['category']) === 'consumables') {
            return null; // Remove this item from the filtered array
        }

        return $item;
    }, $result['items']);

    // Remove null entries from the filtered array
    $filteredItems = array_filter($filteredItems);

    // Reset array keys after filtering
    $filteredItems = array_values($filteredItems);

    echo json_encode(["success" => true, "items" => $filteredItems, "summary" => $result['summary']]);
}
?>