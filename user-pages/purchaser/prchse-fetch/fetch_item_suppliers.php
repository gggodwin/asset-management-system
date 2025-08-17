<?php
include '../../../core/dbsys.ini';  // DB connection


$sql = "
SELECT 
    isup.item_id,
    i.particular AS item_name,
    i.brand,
    s.supplier_name,
    s.contact_name,
    s.contact_email,
    s.contact_phone,
    s.address,
    c.itcat_name AS category
FROM dbpis_item_suppliers isup
JOIN dbpis_items i ON isup.item_id = i.id
JOIN dbpis_supplier s ON isup.supplier_id = s.supplier_id
JOIN dbpis_item_category c ON i.category = c.itcat_id
";

$stmt = $db->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
