<?php
session_start(); // Make sure session is started
include '../../../core/dbsys.ini';

$rrNo = $_GET['rr_no'] ?? '';

if (!$rrNo) {
    echo json_encode(['error' => 'Missing RR number']);
    exit;
}

$modid = $_SESSION['modid'] ?? null;

$sql = "SELECT
    rr.rr_no,
    rr.received_from,
    rr.date_received,
    rr.invoice_no,
    rr.invoice_date,
    rr.received_by,
    rr.department_id,
    d.dept_name,

    rrd.rr_detail_id,
    rrd.prs_id,
    rrd.prs_date,
    rrd.po_no,
    rrd.po_date,
    rrd.quantity,
    rrd.unit,
    rrd.particulars AS item_code,
    i.particular,
    i.brand,
    i.category,
    cat.itcat_name,
    rrd.unit_price,
    rrd.total_price,
    rrd.status AS rr_detail_status,

    prs.requested_by,
    prs.department,
    prs.unit_id,
    u.unit_name,
    prs.date_requested,
    prs.date_needed
FROM dbpis_rr rr
JOIN dbpis_rr_details rrd ON rr.rr_no = rrd.rr_no
JOIN dbpis_items i ON rrd.particulars = i.barcode
JOIN dbpis_item_category cat ON i.category = cat.itcat_id
JOIN dbpis_prs prs ON rrd.prs_id = prs.prs_id
LEFT JOIN dbpis_unit u ON prs.unit_id = u.unit_id
LEFT JOIN dbpis_department d ON rr.department_id = d.dept_id
WHERE rr.rr_no = ?
";

// Apply category filter based on modid
if ($modid == 6) {
    $sql .= " AND i.category BETWEEN 401 AND 405";
} else {
    $sql .= " AND i.category NOT BETWEEN 401 AND 405";
}

$sql .= " ORDER BY rr.rr_no, rrd.rr_detail_id;";

$stmt = $db->prepare($sql);
$stmt->execute([$rrNo]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo json_encode(['error' => 'No data found']);
    exit;
}

$response = [
    'department_id' => $rows[0]['department_id'],
    'dept_name' => $rows[0]['department'],
    'unit_id' => $rows[0]['unit_id'],
    'unit_name' => $rows[0]['unit_name'],
    'requested_by' => $rows[0]['requested_by'],
    'items' => []
];

foreach ($rows as $row) {
    $response['items'][] = [
        'item_code' => $row['item_code'],
        'particular' => $row['particular'],
        'brand' => $row['brand'],
        'quantity' => $row['quantity'],
        'unit' => $row['unit'],
        'total_price' => $row['total_price']
    ];
}

echo json_encode($response);
?>
