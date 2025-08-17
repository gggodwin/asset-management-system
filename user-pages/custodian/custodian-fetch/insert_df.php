<?php
include '../../../core/dbsys.ini'; // Make sure this sets up $db as a PDO instance
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'] ?? '';
    $dept_id = $_POST['dept_id'] ?? '';
    $unit_id = $_POST['unit_id'] ?? '';
    $df_date = $_POST['df_date'] ?? date('Y-m-d');
    $df_reqstby = $_POST['df_reqstby'] ?? '';
    $rr_no = $_POST['df_rr_no'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    $item_codes = $_POST['it_no'] ?? [];
    $quantities = $_POST['df_qty'] ?? [];
    $units = $_POST['df_unit'] ?? [];
    $amounts = $_POST['df_amount'] ?? [];
    $eq_nos = $_POST['eq_no'] ?? [];

    if (empty($item_codes)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required item data.']);
        exit;
    }

    try {
        $db->beginTransaction();

        $stmt = $db->prepare("INSERT INTO dbpis_df (staff_id, dept_id, unit_id, df_date, df_reqstby, rr_no, updated_at)
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$staff_id, $dept_id, $unit_id, $df_date, $df_reqstby, $rr_no, $created_at]);

        $df_no = $db->lastInsertId();

        $detailStmt = $db->prepare("INSERT INTO dbpis_df_details (df_no, it_no, df_qty, df_unit, df_amount, eq_no, created_at, updated_at)
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($item_codes); $i++) {
            $eq_no = isset($eq_nos[$i]) && $eq_nos[$i] !== 'N/A' && trim($eq_nos[$i]) !== '' ? $eq_nos[$i] : null;

            $detailStmt->execute([
                $df_no,
                $item_codes[$i],
                $quantities[$i],
                $units[$i],
                $amounts[$i],
                $eq_no,
                $created_at,
                $created_at
            ]);

            // Update dbpis_eq_tagging status if eq_no is not null or an empty string
            if ($eq_no !== null && trim($eq_no) !== '') {
                $updateEqStmt = $db->prepare("UPDATE dbpis_eq_tagging SET status = 'Deployed' WHERE eq_no = ?");
                $updateEqStmt->execute([$eq_no]);
            }
        }

        $updateRRStatus = $db->prepare("UPDATE dbpis_rr_details SET status = 'Deployed' WHERE rr_no = ? AND particulars = ?");
        for ($i = 0; $i < count($item_codes); $i++) {
            $updateRRStatus->execute([$rr_no, $item_codes[$i]]);
        }

        $db->commit();

        echo json_encode(['status' => 'success', 'message' => 'Deployment form successfully submitted.']);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}
?>