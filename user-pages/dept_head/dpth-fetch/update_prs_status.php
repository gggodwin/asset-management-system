<?php
include '../../../core/dbsys.ini';

$data = json_decode(file_get_contents("php://input"), true);
$prs_code = $data['prs_code'];
$status = $data['status'];

$sql = "UPDATE dbpis_prs SET approval_status = ?, updated_at = NOW() WHERE prs_code = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$status, $prs_code]);

echo json_encode(['message' => 'Status updated to ' . $status]);
?>
