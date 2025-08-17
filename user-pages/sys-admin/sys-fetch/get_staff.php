<?php
include '../../../core/dbsys.ini';
include '../../../query/system.qry';

header('Content-Type: application/json');

// Validate the staff ID parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid staff ID']);
    exit;
}

$staff_id = (int)$_GET['id'];
$system = new SYSTEM();

try {
    // Get staff details with department name
    $stmt = $db->prepare("
        SELECT 
            s.staff_id, 
            s.surname, 
            s.midname, 
            s.firstname, 
            s.extension, 
            s.dept_id, 
            d.dept_name,
            s.staff_email 
        FROM dbpis_staff s
        LEFT JOIN dbpis_department d ON s.dept_id = d.dept_id
        WHERE s.staff_id = :staff_id
    ");
    
    $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($staff) {
        // Also fetch all departments for the dropdown
        $deptStmt = $db->query("SELECT dept_id, dept_name FROM dbpis_department ORDER BY dept_name");
        $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $staff,
            'departments' => $departments
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Staff member not found'
        ]);
    }
} catch (PDOException $e) {
    error_log("Database error in get_staff.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred'
    ]);
}
?>