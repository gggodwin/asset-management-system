<?php
require_once '../../../core/dbsys.ini'; // Adjust as needed

$query = "
    SELECT c.itcat_id, c.itcat_name, g.itemcatgrp_name 
    FROM dbpis_item_category c
    LEFT JOIN dbpis_itemcategory_group g ON c.itemcatgrp_id = g.itemcatgrp_id
";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categories);
