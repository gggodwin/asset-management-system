<?php
require_once '../../../core/dbsys.ini'; // adjust path as needed
header('Content-Type: application/json');

try {
    $db->beginTransaction();

    // Get POST data
    $eq_no = $_POST['eq_no'] ?? '';
    $old_unit = $_POST['old_unit'] ?? '';
    $dept_unit = $_POST['dept_unit'] ?? '';
    $transfer_date = $_POST['transfer_date'] ?? '';
    $received_by = $_POST['received_by'] ?? '';

    // Basic validation
    if (!$eq_no || !$dept_unit || !$transfer_date || !$received_by) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Insert into dbpis_transfer
    $insertTransfer = $db->prepare("INSERT INTO dbpis_transfer (eq_no, old_unit, dept_unit, transfer_date, received_by) 
                                    VALUES (?, ?, ?, ?, ?)");
    $insertTransfer->execute([$eq_no, $old_unit, $dept_unit, $transfer_date, $received_by]);

    // Update eq_loc in dbpis_eq_tagging
    $updateEq = $db->prepare("UPDATE dbpis_eq_tagging SET eq_loc = ? WHERE eq_no = ?");
    $updateEq->execute([$dept_unit, $eq_no]);

    $db->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
