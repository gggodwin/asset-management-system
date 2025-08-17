<?php 
include '../../../core/dbsys.ini';
include '../../../query/system.qry';
date_default_timezone_set('Asia/Manila');
$system = new SYSTEM();

if (isset($_POST['uaid'])) {
    $uaid = $_POST['uaid'];

    // Fetch user details before deletion for logging purposes
    $userDetails = $system->getUserDetails($db, $uaid);  // Assuming you have a function to fetch user details by uaid
    if ($userDetails) {
        $name = $userDetails['name'];
        $username = $userDetails['username'];
        $position = $userDetails['position'];

        // Call the deleteUserAccount function to delete the user
        $success = $system->deleteUserAccount($db, $uaid);

        if ($success) {
            // Fetch the logged-in user's details for logging
            session_start();
            $deletedBy = $_SESSION['name'] ?? 'Unknown';
            $deletedByUsername = $_SESSION['username'] ?? 'unknown';

            // Fetch total user counts
            $total_users = $system->getTotalUsersCount($db);
            $total_activeusers = $system->getTotalActiveUsersCount($db);
            $total_inactiveusers = $system->getTotalInactiveUsersCount($db);

            // Log the successful deletion in the required format
            $current_timestamp = date('Y-m-d H:i:s');
            $logEntry = "[$current_timestamp] User deleted successfully: $name | Username: $username | Position: $position | Deleted by: $deletedBy ($deletedByUsername)\n";
            error_log($logEntry, 3, '../../../logs/activity.log');

            // Return a success response with updated counts
            echo json_encode([
                'success' => true,
                'total_users' => $total_users,
                'total_activeusers' => $total_activeusers,
                'total_inactiveusers' => $total_inactiveusers
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing uaid']);
}
?>
