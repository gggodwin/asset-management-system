<?php
include('../../../core/dbsys.ini'); // Adjust based on your DB connection

// Fetch the latest eq_no as a number from the database
$query = "SELECT MAX(CAST(eq_no AS UNSIGNED)) FROM dbpis_eq_tagging";
$stmt = $db->prepare($query);
$stmt->execute();
$latestEqNo = $stmt->fetchColumn();

// Generate the new eq_no by adding 1
$newEqNo = $latestEqNo + 1;

// Return the new eq_no as a number
echo json_encode(['success' => true, 'new_eq_no' => $newEqNo]);
?>
