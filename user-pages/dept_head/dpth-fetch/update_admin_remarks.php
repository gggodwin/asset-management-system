<?php
include '../../../core/dbsys.ini';

$data = json_decode(file_get_contents("php://input"), true);
$prs_code = $data['prs_code'];
$remarks = $data['admin_remarks'];

$sql = "UPDATE dbpis_prs SET admin_remarks = ?, updated_at = NOW() WHERE prs_code = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$remarks, $prs_code]);

echo json_encode(['message' => 'Admin remarks updated.']);
?>
