<?php
    include '../../../core/dbsys.ini';
    include '../../../query/system.qry';

    session_start(); // Ensure session is started

    $system = new SYSTEM();

    if (isset($_POST['uaid'])) {
        date_default_timezone_set('Asia/Manila');
        $uaid = $_POST['uaid'];
        $name = $_POST['name'];
        $username = $_POST['username'];

        // Check if password is set and not empty, otherwise retain the old password
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = $_POST['password']; // Use new password if provided
        } else {
            $password = null; // Set to null if no password is provided
        }

        $status = $_POST['status'];
        $modid = $_POST['modid'];
        $position = $_POST['position'];

        // Fetch user details before update for logging purposes
        $userDetails = $system->getUserAccountDetails($db, $uaid);

        if ($userDetails) {
            // If no password is provided, retain the old password from the database
            if ($password === null) {
                $password = $userDetails['password'];
            }

            // Call the updateUserAccount function to update the user account
            $success = $system->updateUserAccount($db, $uaid, $name, $username, $password, $status, $modid, $position);

            if ($success) {
                // Fetch the updated count of active users
                $total_activeusers = $system->getTotalActiveUsersCount($db);
                $total_inactiveusers = $system->getTotalInactiveUsersCount($db);

                // Return a success response with the updated active users count
                echo json_encode([
                    'success' => true,
                    'activeUsersCount' => $total_activeusers,
                    'inactiveUsersCount' => $total_inactiveusers
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Update failed']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing uaid']);
    }
?>
