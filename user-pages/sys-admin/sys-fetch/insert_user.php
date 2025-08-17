<?php
include ('../../../core/dbsys.ini'); 
include ('../../../query/system.qry');

$system = new SYSTEM();
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set Manila timezone
    date_default_timezone_set('Asia/Manila');

    // Fetch POST data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    $position = $_POST['position'];
    $modid = $_POST['modid'];
    $dept_id = $_POST['dept_id'];

    // Get the currently logged-in user's details
    $added_by_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Unknown';
    $added_by_username = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown_user';

    // Check if username already exists (case-insensitive)
    $checkStmt = $db->prepare("SELECT COUNT(*) FROM dbpis_useraccounts WHERE LOWER(username) = LOWER(?)");
    $checkStmt->execute([$username]);
    $existingCount = $checkStmt->fetchColumn();

    if ($existingCount > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Username already exists. Please choose a different one.',
            'added_by' => "$added_by_name ($added_by_username)"
        ]);
        exit;
    }

    // Call the function to add the user to the database
    $result = $system->addUserAccount($db, $name, $username, $password, $status, $modid, $position, $dept_id);

    if ($result) {
        // Fetch updated total users count, total active users count, and total inactive users count
        $total_users = $system->getTotalUsersCount($db);
        $total_activeusers = $system->getTotalActiveUsersCount($db);
        $total_inactiveusers = $system->getTotalInactiveUsersCount($db);

        // Return success response with the updated counts
        echo json_encode([
            'success' => true,
            'message' => 'User added successfully!',
            'added_by' => "$added_by_name ($added_by_username)",
            'total_users' => $total_users,
            'total_activeusers' => $total_activeusers,
            'total_inactiveusers' => $total_inactiveusers
        ]);
    } else {
        // Return failure response
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add the user.',
            'added_by' => "$added_by_name ($added_by_username)"
        ]);
    }
}
?>
