<?php
session_start();
require_once '../../../core/dbsys.ini';

$groupByDFNo = isset($_GET['groupByDFNo']) && $_GET['groupByDFNo'] === 'true';
$modid = $_SESSION['modid'] ?? null;

// Base query for grouped or full data
if ($groupByDFNo) {
    $query = "
        SELECT 
            df_no, 
            MAX(staff_id) AS staff_id,
            MAX(df_date) AS df_date,
            MAX(dept_name) AS dept_name,
            MAX(unit_name) AS unit_name,
            MAX(df_reqstby) AS df_reqstby,
            MAX(rr_no) AS rr_no,
            MAX(updated_at) AS updated_at,
            COUNT(it_no) AS item_count,
            MAX(equipment_location) AS equipment_location,
            MAX(tagged_date) AS tagged_date
        FROM view_df_full
    ";
} else {
    // Still use SELECT * if you want full details (but will include duplicates) OR category IN (101, 201, 301)
    $query = "SELECT * FROM view_df_full";
}

// Add category filter
if ($modid == 6) {
    $query .= " WHERE category BETWEEN 401 AND 405";
} else {
    $query .= " WHERE category NOT BETWEEN 401 AND 405 AND category";
}

// Add GROUP BY if grouped
if ($groupByDFNo) {
    $query .= " GROUP BY df_no ORDER BY df_date ASC";
} else {
    $query .= " ORDER BY df_date DESC";
}

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
