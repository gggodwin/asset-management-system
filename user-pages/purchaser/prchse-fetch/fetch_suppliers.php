<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

// Get the barcode from the request
$itemBarcode = isset($_GET['barcode']) ? $_GET['barcode'] : ''; // Assuming the barcode is passed as a GET parameter

if ($itemBarcode) {
    try {
        // Prepare and execute the query to fetch the supplier for the item based on barcode
        $query = "
            SELECT s.supplier_name
            FROM dbpis_item_suppliers item_supplier
            JOIN dbpis_supplier s ON item_supplier.supplier_id = s.supplier_id
            JOIN dbpis_items i ON item_supplier.item_id = i.id
            WHERE i.barcode = :itemBarcode
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':itemBarcode', $itemBarcode, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the results
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the suppliers as JSON
        echo json_encode($suppliers);
    } catch (Exception $e) {
        // Handle errors (optional)
        echo json_encode(['error' => 'Failed to fetch suppliers: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No barcode provided']);
}
?>
