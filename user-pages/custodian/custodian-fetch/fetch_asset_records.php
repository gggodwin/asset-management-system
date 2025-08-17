<?php

require '../../../core/dbsys.ini';

session_start(); // Always start session if not yet

header('Content-Type: application/json');

try {
    $modid = isset($_SESSION['modid']) ? $_SESSION['modid'] : null;

    // Base query with dynamic capex_type
    $query = "
        SELECT
            CONCAT(i.particular, ' - ', i.brand) AS item_name,
            d.dept_name AS department,
            u.unit_name AS unit,
            rrd.unit_price,
            rr.rr_no,
            rr.date_received,
            prs.prs_code,
            prs.date_requested AS prs_date,
            rrd.quantity,
            (rrd.unit_price * rrd.quantity) AS total_amount,
            CASE 
                WHEN i.category BETWEEN 401 AND 405 THEN 'OPEX'
                ELSE 'CAPEX'
            END AS capex_type
        FROM dbpis_rr_details rrd
        LEFT JOIN dbpis_rr rr ON rrd.rr_no = rr.rr_no
        LEFT JOIN dbpis_items i ON rrd.particulars = i.barcode
        LEFT JOIN dbpis_prs prs ON rrd.prs_id = prs.prs_id
        LEFT JOIN dbpis_department d ON prs.department = d.dept_name  
        LEFT JOIN dbpis_unit u ON prs.unit_id = u.unit_id
    ";

    // Add condition only if modid == 1
    if ($modid == 1) {
        $query .= "
        WHERE NOT (
            i.category BETWEEN 401 AND 405
        )
        ";
    }

    // Always order by prs_date
    $query .= "ORDER BY prs_date ASC";

    // Prepare and execute
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
