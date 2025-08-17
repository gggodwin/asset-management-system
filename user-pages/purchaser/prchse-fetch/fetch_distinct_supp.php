<?php
require_once '../../../core/dbsys.ini'; // Adjust path if needed

$stmt = $db->prepare("SELECT supplier_id, supplier_name FROM dbpis_supplier ORDER BY supplier_name ASC");
$stmt->execute();
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($suppliers);
?>
