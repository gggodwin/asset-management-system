<?php
include('../../../core/dbsys.ini'); 
include('../../../query/system.qry');

$system = new SYSTEM();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set timezone
    date_default_timezone_set('Asia/Manila');

    // Get form data
    $surname     = $_POST['surname'];
    $midname     = $_POST['midname'] ?? null;
    $firstname   = $_POST['firstname'];
    $extension   = $_POST['extension'] ?? null;
    $dept_id     = $_POST['dept_id'];
    $staff_email = $_POST['staff_email'] ?? null;

    // Logged-in user info (optional for audit/logs)
    $added_by_name     = $_SESSION['name'] ?? 'Unknown';
    $added_by_username = $_SESSION['username'] ?? 'unknown_user';

    // Call the function
    $result = $system->addStaffMember($db, $surname, $midname, $firstname, $extension, $dept_id, $staff_email);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Staff member added successfully!',
            'added_by' => "$added_by_name ($added_by_username)"
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add the staff member.',
            'added_by' => "$added_by_name ($added_by_username)"
        ]);
    }
}
?>
