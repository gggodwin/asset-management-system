<?php
include '../../../core/dbsys.ini';
include '../../../query/custodian.qry';

header('Content-Type: application/json');

if (isset($_POST['eq_no'])) {
    $eq_no = $_POST['eq_no'];

    // Instantiate the CUSTODIAN class
    $custodian = new CUSTODIAN();

    // Fetch equipment tagging details for the given eq_no
    $data = $custodian->fetchEqTaggingDetails($db, $eq_no);

    if ($data) {
        echo json_encode([
            'success' => true,
            'eq_tagging' => $data['eq_tagging'],
            'eq_tagging_details' => $data['eq_tagging_details']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'eq_no is required']);
}
?>
