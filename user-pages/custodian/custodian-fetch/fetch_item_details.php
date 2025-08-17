<?php
include '../../../core/dbsys.ini';

$itemCode = $_POST['item_code'] ?? '';
$prsCode  = $_POST['prs_code'] ?? '';

// Validate input
if (empty($itemCode) || empty($prsCode)) {
    echo json_encode(['error' => 'Missing item_code or prs_code.']);
    exit;
}

// Step 1: Get specific PRS detail using item_code + prs_code
$query = $db->prepare("
    SELECT 
        p.prs_id,
        pd.item_code,
        pd.item_description,
        pd.quantity AS prs_quantity,
        pd.unit_type,
        pd.unit_price,
        p.prs_code,
        p.date_requested
    FROM dbpis_prsdetails pd
    JOIN dbpis_prs p ON pd.prs_code = p.prs_code
    WHERE pd.item_code = ?
      AND pd.prs_code = ?
      AND pd.status IN (1, 3)
      AND pd.unit_price IS NOT NULL
      AND pd.total_price IS NOT NULL
    LIMIT 1
");
$query->execute([$itemCode, $prsCode]);
$item = $query->fetch(PDO::FETCH_ASSOC);

// If no item found, return error
if (!$item) {
    echo json_encode(['error' => 'Item with matching PRS not found.']);
    exit;
}

// Step 2: Get total quantity already received for this PRS entry
$rrQuery = $db->prepare("
    SELECT SUM(quantity) AS received_qty 
    FROM dbpis_rr_details 
    WHERE particulars = ? AND prs_id = ?
");
$rrQuery->execute([$itemCode, $item['prs_id']]);
$rrResult = $rrQuery->fetch(PDO::FETCH_ASSOC);

$receivedQty = (int) $rrResult['received_qty'];
$pendingQty = max((int)$item['prs_quantity'] - $receivedQty, 0);

// Step 3: Prepare the response
$response = [
    'prs_id'          => $item['prs_id'],
    'prs_code'        => $item['prs_code'],
    'item_code'       => $item['item_code'],
    'item_description'=> $item['item_description'],
    'unit_type'       => $item['unit_type'],
    'unit_price'      => $item['unit_price'],
    'prs_quantity'    => $item['prs_quantity'],
    'received_qty'    => $receivedQty,
    'quantity'        => $pendingQty,
    'date_requested'  => $item['date_requested'],
];

// Return response as JSON
echo json_encode($response);
?>
