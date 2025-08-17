<?php
require_once '../../../core/dbsys.ini'; // DB connection

try {
    $db->beginTransaction();

    // Insert into dbpis_rr (auto-increment rr_no)
    $insertRR = $db->prepare("INSERT INTO dbpis_rr 
        (received_from, date_received, invoice_no, invoice_date, received_by, department_id, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())");

    $insertRR->execute([
        $_POST['received_from'],
        $_POST['date_received'],
        $_POST['invoice_no'],
        $_POST['invoice_date'],
        $_POST['received_by'],
        $_POST['department']
    ]);

    // Get the auto-incremented RR number
    $rr_no = $db->lastInsertId();

    // Insert into dbpis_rr_details
    $particulars = $_POST['particulars'];
    $prs_id = $_POST['prs_id'];
    $prs_date = $_POST['prs_date'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];
    $unit_prices = $_POST['unit_price'];
    $total_prices = $_POST['total_price'];

    $insertDetail = $db->prepare("INSERT INTO dbpis_rr_details 
    (rr_no, prs_id, prs_date, quantity, unit, particulars, unit_price, total_price, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < count($particulars); $i++) {
        list($item_code, $prs_code) = explode('|', $particulars[$i]); // Extract item_code from combined value
    
        $insertDetail->execute([
            $rr_no,
            $prs_id[$i],
            $prs_date[$i],
            $quantities[$i],
            $units[$i],
            $item_code, // Only insert the item code
            $unit_prices[$i],
            $total_prices[$i],
            'Received'
        ]);
    }
    

    $db->commit();

    echo json_encode(['success' => true, 'rr_no' => $rr_no]);

} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
