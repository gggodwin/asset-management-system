<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class
session_start(); // Ensure the session is started
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prs_code = $_POST['prs_code'];
    $item_codes = $_POST['item_code'];
    $suppliers = $_POST['supplier'];
    $unit_prices = $_POST['unit_price'];
    $total_prices = $_POST['total_price'];
    $quantities = $_POST['quantity'];
    $statuses = $_POST['status'];

    $remarksLog = [];

    foreach ($item_codes as $index => $item_code) {
        $supplier = $suppliers[$index];
        $unit_price = $unit_prices[$index];
        $total_price = $total_prices[$index];
        $quantity = $quantities[$index];
        $status = $statuses[$index];

        // Fetch current PRS detail for this item
        $stmt = $db->prepare("SELECT item_description, quantity, original_quantity FROM dbpis_prsdetails WHERE prs_code = :prs_code AND item_code = :item_code");
        $stmt->execute([':prs_code' => $prs_code, ':item_code' => $item_code]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            $itemDesc = $item['item_description'];
            $originalQty = $item['original_quantity'];
            $currentQty = $item['quantity'];
        
            // Set original_quantity if null
            if (is_null($originalQty)) {
                $updateOrig = $db->prepare("UPDATE dbpis_prsdetails SET original_quantity = :orig_qty WHERE prs_code = :prs_code AND item_code = :item_code");
                $updateOrig->execute([
                    ':orig_qty' => $currentQty,
                    ':prs_code' => $prs_code,
                    ':item_code' => $item_code
                ]);
                $originalQty = $currentQty;
            }
        
            // If quantity changed, log remark
            if ($quantity != $originalQty) {
                $remarksLog[] = "‘{$itemDesc}’ quantity changed from {$originalQty} to {$quantity}";
            }

            // Update PRS detail
            $update = $db->prepare("UPDATE dbpis_prsdetails 
                SET quantity = :quantity, 
                    supplier = :supplier, 
                    unit_price = :unit_price, 
                    total_price = :total_price,
                    status = :status,
                    updated_at = NOW()
                WHERE prs_code = :prs_code AND item_code = :item_code");
            $update->execute([
                ':quantity' => $quantity,
                ':supplier' => $supplier,
                ':unit_price' => $unit_price,
                ':total_price' => $total_price,
                ':status' => $status,
                ':prs_code' => $prs_code,
                ':item_code' => $item_code
            ]);
        }
    }

    // Save remarks to dbpis_prs if there are changes
    if (!empty($remarksLog)) {
        $remarks = implode("; ", $remarksLog);
        $updateRemarks = $db->prepare("UPDATE dbpis_prs SET purchase_remarks = :remarks WHERE prs_code = :prs_code");
        $updateRemarks->execute([
            ':remarks' => $remarks,
            ':prs_code' => $prs_code
        ]);
    }

    // Logging the update action
    $current_timestamp = date('Y-m-d H:i:s');
    //$logEntry = "[$current_timestamp] PRS updated | PR Code: {$prs_code} | Updated by: {$_SESSION['name']} ({$_SESSION['username']})\n";
    //error_log($logEntry, 3, '../../../logs/activity.log');

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
