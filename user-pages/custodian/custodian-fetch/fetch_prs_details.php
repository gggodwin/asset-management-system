<?php
require_once '../../../core/dbsys.ini';
session_start();

// Get current user's modid and name
$modid = $_SESSION['modid'] ?? null;
$requested_by = $_SESSION['name'] ?? null;

// Build dynamic SQL conditions
$categoryFilter = '';
$requestedByFilter = '';
$params = [];

if (!in_array($modid, [2, 3, 4, 6])) {
    // Exclude category 401–405 for others
    $categoryFilter = "AND (CAST(i.category AS UNSIGNED) NOT BETWEEN 401 AND 405)";
}

if ($modid == 2 && $requested_by) {
    // Add restriction for modid 2
    $requestedByFilter = "AND pr.requested_by = :requested_by";
    $params[':requested_by'] = $requested_by;
}

// Prepare the query to fetch PRS details, approval status, and RR summary
$stmt = $db->prepare("
    SELECT 
        pd.*, 
        pr.approval_status, 
        pr.prs_id,
        rr_summary.total_rr_quantity AS rr_quantity,
        pd.status AS prs_status
    FROM dbpis_prsdetails pd
    JOIN dbpis_prs pr ON pd.prs_code = pr.prs_code
    JOIN dbpis_items i ON pd.item_code = i.barcode
    LEFT JOIN (
        SELECT 
            particulars, 
            prs_id,
            SUM(quantity) AS total_rr_quantity
        FROM dbpis_rr_details
        GROUP BY particulars, prs_id
    ) AS rr_summary 
        ON rr_summary.particulars = pd.item_code 
        AND rr_summary.prs_id = pr.prs_id
    WHERE pr.approval_status = 'approved'
      AND pd.status IN (1, 2, 3)
      AND pd.unit_price IS NOT NULL
      AND pd.total_price IS NOT NULL
      $categoryFilter
      $requestedByFilter
");

$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update PRS detail statuses based on RR quantity
foreach ($results as $item) {
    $totalRRQuantity = getTotalRRQuantity($item['item_code'], $item['prs_id']);
    
    if ($totalRRQuantity == $item['quantity']) {
        $newStatus = 2; // Completed
    } elseif ($totalRRQuantity > 0) {
        $newStatus = 3; // Not Yet Complete
    } else {
        $newStatus = 1; // Pending Delivery
    }

    $updateStmt = $db->prepare("
        UPDATE dbpis_prsdetails
        SET status = :newStatus
        WHERE prsdetails_id = :prsdetails_id
    ");
    $updateStmt->execute([
        ':newStatus' => $newStatus,
        ':prsdetails_id' => $item['prsdetails_id']
    ]);
}

// Function to calculate total received quantity from rr_details
function getTotalRRQuantity($itemCode, $prsId) {
    global $db;
    $stmt = $db->prepare("
        SELECT SUM(quantity) AS total_received_quantity
        FROM dbpis_rr_details
        WHERE particulars = :itemCode AND prs_id = :prsId
    ");
    $stmt->execute([
        ':itemCode' => $itemCode,
        ':prsId' => $prsId
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_received_quantity'] ?? 0;
}

// Re-fetch updated results with the same filters
$stmt = $db->prepare("
    SELECT 
        pd.*, 
        pr.approval_status, 
        rr_summary.total_rr_quantity AS rr_quantity,
        pd.status AS prs_status
    FROM dbpis_prsdetails pd
    JOIN dbpis_prs pr ON pd.prs_code = pr.prs_code
    JOIN dbpis_items i ON pd.item_code = i.barcode
    LEFT JOIN (
        SELECT 
            particulars, 
            prs_id,
            SUM(quantity) AS total_rr_quantity
        FROM dbpis_rr_details
        GROUP BY particulars, prs_id
    ) AS rr_summary 
        ON rr_summary.particulars = pd.item_code 
        AND rr_summary.prs_id = pr.prs_id
    WHERE pr.approval_status = 'approved'
      AND pd.status IN (1, 2, 3)
      AND pd.unit_price IS NOT NULL
      AND pd.total_price IS NOT NULL
      $categoryFilter
      $requestedByFilter
");

$stmt->execute($params);
$updatedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the updated results
echo json_encode($updatedResults);
?>
