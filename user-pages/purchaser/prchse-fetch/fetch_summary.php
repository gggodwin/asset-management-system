<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

$purchaser = new PURCHASER();
$purchaseStats = $purchaser->getPurchaseSummary($db);


echo json_encode([
    'totalApprovedPRs' => $purchaseStats['total_approved_prs'] ?? 0,
    'pendingPriceUpdates' => $purchaseStats['pending_price_updates'] ?? 0,
    'supplierCount' => $purchaseStats['supplier_count'] ?? 0,
    'upcomingPRDeadlines' => $purchaseStats['upcoming_pr_deadlines'] ?? 0
]);


?>
