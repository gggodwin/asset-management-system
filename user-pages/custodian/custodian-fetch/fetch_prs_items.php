<?php
require_once '../../../core/dbsys.ini'; // Adjust path if necessary

// Get the supplier value from POST (supplier_id)
$supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : '';

// Prepare the query to fetch PRS details based on supplier_id
$stmt = $db->prepare("
SELECT 
    pd.*, 
    pr.approval_status, 
    rr_summary.total_rr_quantity AS rr_quantity,
    pd.status AS prs_status
FROM dbpis_prsdetails pd
JOIN dbpis_prs pr ON pd.prs_code = pr.prs_code
LEFT JOIN (
    SELECT 
        particulars, 
        SUM(quantity) AS total_rr_quantity
    FROM dbpis_rr_details
    GROUP BY particulars
) AS rr_summary ON rr_summary.particulars = pd.item_code
JOIN dbpis_supplier s ON pd.supplier = s.supplier_name
WHERE pr.approval_status = 'approved'
  AND pd.status IN (1, 3)
  AND pd.unit_price IS NOT NULL
  AND pd.total_price IS NOT NULL
  AND s.supplier_id = :supplier_id
");

// Bind the supplier_id parameter safely to prevent SQL injection
$stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
echo json_encode($results);
?>
