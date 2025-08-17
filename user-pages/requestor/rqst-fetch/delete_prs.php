<?php
include '../../../core/dbsys.ini';
include '../../../query/requestor.qry';

$requestor = new REQUESTOR(); // Use REQUESTOR instead of SYSTEM
date_default_timezone_set('Asia/Manila');

if (isset($_POST['prs_code'])) {
    $prs_code = $_POST['prs_code'];

    // Delete the PR
    $success = $requestor->deletePR($db, $prs_code);

    if ($success) {
        session_start();
        $deletedBy = $_SESSION['name'] ?? 'Unknown';
        $deletedByUsername = $_SESSION['username'] ?? 'unknown';

        // Fetch updated PR statistics for the current user
        $stats = $requestor->getUserPRStats($db, $deletedBy);

        // Log deletion
        $current_timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$current_timestamp] PRS deleted successfully | PR Code: $prs_code | Deleted by: $deletedBy ($deletedByUsername)\n";
        error_log($logEntry, 3, '../../../logs/activity.log');

        echo json_encode([
            'success' => true,
            'total_prs' => $stats['total_prs'],
            'total_pending_prs' => $stats['pending_prs'],
            'total_approved_prs' => $stats['approved_prs'],
            'total_rejected_prs' => $stats['rejected_prs']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing prs_code']);
}
//<!--<button class="dropdown-item delete-btn" data-prs-code="${pr.prs_code}" ${isApproved? "disabled": ""} ${isApproved? `title="Approved PRs cannot be deleted."`: ""} style="transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;" onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#000'; this.style.cursor='pointer'" onmouseout="this.style.backgroundColor=''; this.style.color=''">Delete</button>-->
?>

