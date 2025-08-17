<?php
include '../../../core/dbsys.ini';
include '../../../query/system.qry';

header('Content-Type: application/json');

$system = new SYSTEM();

try {
    $users = $system->getAllUsers($db);
    $totalUsers = count($users);
    
    // Count active and inactive users
    $activeUsers = array_filter($users, function($user) {
        return $user['status'] == '1';
    });
    $inactiveUsers = array_filter($users, function($user) {
        return $user['status'] != '1';
    });

    echo json_encode([
        'success' => true,
        'data' => $users,
        'counts' => [
            'totalUsers' => $totalUsers,
            'activeUsers' => count($activeUsers),
            'inactiveUsers' => count($inactiveUsers)
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
