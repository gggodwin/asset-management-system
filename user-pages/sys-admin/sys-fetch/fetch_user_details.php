<?php
include '../../../core/dbsys.ini';
include '../../../query/system.qry';

header('Content-Type: application/json');

$system = new SYSTEM();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uaid'])) {
    $uaid = $_POST['uaid'];

    try {
        $user = $system->getUserAccountDetails($db, $uaid);
        if ($user) {
            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
