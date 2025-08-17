<?php
require_once '../../../core/dbsys.ini';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prsdetails_id'])) {
    $prsdetails_id = $_POST['prsdetails_id'];

    try {
        // Prepare the query to update the status of the specific item
        $stmt = $db->prepare("UPDATE dbpis_prsdetails SET status = 0 WHERE prsdetails_id = :prsdetails_id");
        $stmt->execute([':prsdetails_id' => $prsdetails_id]);

        // Return a success response
        echo json_encode([
            'success' => true,
            'message' => 'Item has been successfully canceled.'
        ]);
    } catch (PDOException $e) {
        // Return an error response in case of a failure
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    // Return an error if there's no valid `prsdetails_id` in the request
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
