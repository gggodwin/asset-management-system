<?php
require_once '../../../core/dbsys.ini'; // Adjust path as needed
header('Content-Type: application/json');

$locationCode = $_GET['locationCode'] ?? '';
$itemCode = $_GET['itemCode'] ?? '';

if (!$locationCode || !$itemCode) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$prefix = "00624_" . $locationCode . $itemCode;

try {
    // Assuming $db is your PDO connection object from dbsys.ini
    $sql = "SELECT MAX(CAST(SUBSTRING(pr_code, LENGTH(:prefix) + 1) AS UNSIGNED)) as max_suffix 
            FROM dbpis_eq_tag_details 
            WHERE pr_code LIKE CONCAT(:prefix, '%')";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':prefix', $prefix, PDO::PARAM_STR);
    $stmt->execute();

    $maxSuffix = 0;
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['max_suffix'] !== null) {
        $maxSuffix = (int)$row['max_suffix'];
    }

    echo json_encode(['success' => true, 'maxSuffix' => $maxSuffix]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
