<?php
session_start();
require_once '../../../core/dbsys.ini'; // your DB connection

$modid = $_SESSION['modid'] ?? null;

// Set category condition based on modid
if ($modid == 6) {
    $categoryCondition = "i.category BETWEEN '401' AND '405'";
} else {
    $categoryCondition = "i.category NOT BETWEEN '401' AND '405'";
}

try {
    $stmt = $db->prepare("
        SELECT DISTINCT rr.rr_no
        FROM dbpis_rr rr
        JOIN dbpis_rr_details rrd ON rr.rr_no = rrd.rr_no
        JOIN dbpis_items i ON rrd.particulars = i.barcode
        LEFT JOIN (
            SELECT df.rr_no, dfd.it_no, SUM(dfd.df_qty) AS deployed_qty
            FROM dbpis_df df
            JOIN dbpis_df_details dfd ON df.df_no = dfd.df_no
            GROUP BY df.rr_no, dfd.it_no
        ) deployed ON rr.rr_no = deployed.rr_no AND rrd.particulars = deployed.it_no
        WHERE $categoryCondition
        AND rr.rr_no NOT IN (
            SELECT DISTINCT rr_no FROM dbpis_eq_tagging
        )
        AND (rrd.quantity > COALESCE(deployed.deployed_qty, 0))
    ");

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
