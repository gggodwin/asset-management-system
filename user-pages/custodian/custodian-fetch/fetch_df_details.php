<?php
session_start();
include '../../../core/dbsys.ini';

$rrNo = $_GET['rr_no'] ?? '';

if (!$rrNo) {
    echo json_encode(['error' => 'Missing RR number']);
    exit;
}

$modid = $_SESSION['modid'] ?? null;

$sql = "SELECT
            rr.*,
            eq.eq_no,
            eqd.eqd_id,
            eqd.it_no,
            rrd.*,
            i.category,
            d.dept_name,
            prs.requested_by,
            u.unit_id,
            u.unit_name,
            i.brand,
            i.particular,
            i.units,
            rrd.unit_price,
            GROUP_CONCAT(rrd.particulars) AS rr_detail_item_numbers
        FROM dbpis_rr rr
        LEFT JOIN dbpis_rr_details rrd ON rr.rr_no = rrd.rr_no
        LEFT JOIN dbpis_items i ON i.barcode = rrd.particulars
        LEFT JOIN dbpis_prs prs ON rrd.prs_id = prs.prs_id
        LEFT JOIN dbpis_department d ON d.dept_id = rr.department_id
        LEFT JOIN dbpis_unit u ON prs.unit_id = u.unit_id
        LEFT JOIN dbpis_eq_tagging eq ON eq.rr_no = rr.rr_no
        LEFT JOIN dbpis_eq_tag_details eqd ON eqd.eq_no = eq.eq_no AND eqd.it_no = rrd.particulars
        WHERE rr.rr_no = :rr_no ";


// Category filter
if ($modid == 6) {
    $sql .= " AND i.category BETWEEN 401 AND 405";
} else {
    $sql .= " AND i.category NOT BETWEEN 401 AND 405 AND eq.eq_no IS NOT NULL";
}

$sql .= " GROUP BY rr.rr_no, eq.eq_no, eqd.eqd_id, rrd.particulars, rrd.unit_price";

$stmt = $db->prepare($sql);
$stmt->bindParam(':rr_no', $rrNo, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo json_encode(['error' => 'No data found']);
    exit;
}

$response = [
    'department_id' => $rows[0]['department_id'],
    'dept_name' => $rows[0]['dept_name'],
    'unit_id' => $rows[0]['unit_id'],
    'unit_name' => $rows[0]['unit_name'],
    'requested_by' => $rows[0]['requested_by'],
    'items' => []
];

foreach ($rows as $row) {
    $response['items'][] = [
        'item_code' => ($modid == 6) ? $row['particulars'] : $row['it_no'],
        'particular' => $row['particular'],
        'brand' => $row['brand'],
        'quantity' => $row['quantity'],
        'unit' => $row['units'],
        'total_price' => $row['unit_price'],
        'eq_no' => $row['eq_no'] ?? null
    ];
}


echo json_encode($response);
?>