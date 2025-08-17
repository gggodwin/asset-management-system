<?php
require_once '../../../core/dbsys.ini'; // Adjust path if necessary

$groupByRRNo = isset($_GET['groupByRRNo']) && $_GET['groupByRRNo'] == 'true';

if ($groupByRRNo) {
    $stmt = $db->prepare("
SELECT
    rr.rr_no,
    rr.received_from,
    rr.invoice_no,
    rr.invoice_date,
    rr.received_by,
    rr.department,
    MAX(rr.date_received) AS date_received,
    u.unit_name,
    pd.quantity AS prs_detail_quantity, -- Alias for quantity from dbpis_prsdetails
    rr.quantity AS rr_total_quantity     -- Alias for quantity from dbpis_get_rr
FROM
    dbpis_get_rr rr
JOIN
    dbpis_prs p ON rr.prs_no = p.prs_code
JOIN
    dbpis_unit u ON p.unit_id = u.unit_id
JOIN
    dbpis_prsdetails pd ON rr.prs_no = pd.prs_code
GROUP BY
    rr.rr_no,
    rr.received_from,
    rr.invoice_no,
    rr.invoice_date,
    rr.received_by,
    rr.department,
    u.unit_name
ORDER BY
    MAX(rr.date_received) DESC;

    ");
} else {
    $stmt = $db->prepare("SELECT 
    rr.*, 
    CASE 
        WHEN i.category BETWEEN 401 AND 405 THEN 'OPEX'
        ELSE 'CAPEX'
    END AS capex_type
FROM dbpis_get_rr rr
LEFT JOIN dbpis_items i ON rr.item_barcode = i.barcode
ORDER BY rr.date_received DESC;");
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>