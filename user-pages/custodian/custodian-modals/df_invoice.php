<?php
require_once '../../../core/dbsys.ini'; // Adjust path as needed
session_start();
$modid = $_SESSION['modid'] ?? null;
if (isset($_GET['df_no'])) {
    $dfNo = $_GET['df_no'];

    try {
        $sql = "SELECT
            df.*,
            d.dept_name,
            u.unit_name,
            s.surname AS requested_by_name,
            di.df_qty,
            di.df_unit,
            di.df_amount,
            i.particular,
            i.brand,
            i.units
        FROM dbpis_df df
        LEFT JOIN dbpis_department d ON df.dept_id = d.dept_id
        LEFT JOIN dbpis_unit u ON df.unit_id = u.unit_id
        LEFT JOIN dbpis_staff s ON df.df_reqstby = s.staff_id
        LEFT JOIN dbpis_df_details di ON df.df_no = di.df_no
        LEFT JOIN dbpis_items i ON di.it_no = i.barcode
        WHERE df.df_no = :df_no ";

        // Category filter (adjust table and column names if needed for DF)
        if ($modid == 6) {
            $sql .= " AND (i.category BETWEEN 401 AND 405 OR i.category IN (101, 201, 301))";
        } else {
            $sql .= " AND i.category NOT BETWEEN 401 AND 405"; // Adjust condition as needed
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':df_no', $dfNo, PDO::PARAM_INT);
        $stmt->execute();
        $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($reportData) > 0) {
            // Extract header data (assuming the first row contains the main DF info)
            $headerData = $reportData[0];

            // Extract item data
            $itemData = $reportData;

        } else {
            echo "No data found for DF No: " . htmlspecialchars($dfNo);
            exit;
        }

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }

} else {
    echo "No DF number provided.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Claim Form</title>
<style>
body { margin: 0; padding: 0; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: Arial, sans-serif; }
.claim-form { width: 210mm; max-width: 100%; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); box-sizing: border-box; border-bottom: 2px solid black; }
.header-section { display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
.header-section img { max-height: 100px; margin-right: 20px; }
.company-info h2 { margin: 0; font-size: 2.3em; font-weight: bold; }
.company-info p { margin: 2px 0; font-size: 1em; }
.claim-title { display: flex; justify-content: center; align-items: center; margin: 15px 0; font-size: 24px; font-weight: bold; }
.claim-title span { color: red; margin-left: 8px; font-size: 1.2em; }
.form-group { display: flex; justify-content: space-between; margin-bottom: 8px; }
.form-group label { font-weight: bold; margin-right: 5px; }
.form-group span { flex-grow: 1; }
.item-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
.item-table th, .item-table td { border: 1px solid black; padding: 8px; text-align: center; }
.item-table th { background-color: lightgray; }
.signature-section { margin-top: 30px; display: flex; justify-content: space-between; }
.signature-block { width: 45%; }
.signature-line { border-top: 1px solid #000; text-align: center; margin-top: 40px; padding-top: 5px; }
@media print { @page { size: A4 portrait; margin: 10mm 10mm 10mm 10mm; } body { background: white; margin: 0; display: block !important; height: auto !important; align-items: normal !important; justify-content: normal !important; } .claim-form { box-shadow: none; border: none; width: 100%; max-width: none; border-bottom: 2px solid black; padding-bottom: 10px; margin-top: 0; } }

</style>
</head>
<body>
<div class="claim-form">
    <div class="header-section">
        <img src="../../../static/dbclogo.png" alt="Logo">
        <div class="company-info">
            <h2>DON BOSCO COLLEGE, INC.</h2>
            <p>Canlubang, Calamba City, Laguna</p>
        </div>
        <img src="../../../static/dbclogo2.png" alt="Logo">
    </div>

    <div class="claim-title">
        <?php 
            $modid = $_SESSION['modid'] ?? null;
            echo $modid == 6 ? "CLAIM FORM (CONSUMABLES)" : "CLAIM FORM (CAPEX)";
        ?>
    </div>

    <div class="form-group" style="display: flex; justify-content: space-between; margin-bottom: 8px;">
    <div>Name: <strong><span style="text-decoration: underline;"><?php echo htmlspecialchars($headerData['staff_id'] ?? ''); ?></span></strong></div>
    <div>Date: <strong><span style="text-decoration: underline;"><?php echo htmlspecialchars($headerData['df_date'] ?? ''); ?></span></strong></div>
</div>
<div class="form-group" style="margin-bottom: 8px;">
    <div>Department: <strong><span style="text-decoration: underline;"><?php echo htmlspecialchars($headerData['dept_name'] ?? ''); ?> (<?php echo htmlspecialchars($headerData['unit_name'] ?? ''); ?>)</span></strong></div>
</div>


    <table class="item-table">
        <thead>
            <tr>
                <th>ITEM No.</th>
                <th>PARTICULARS</th>
                <th>QUANTITY</th>
                <th>UNIT</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grandTotal = 0;
            if ($itemData): 
                foreach ($itemData as $index => $item): 
                    $grandTotal += $item['df_amount'] ?? 0;
            ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($item['particular'] ?? ''); ?> (<?php echo htmlspecialchars($item['brand'] ?? ''); ?>)</td>
                    <td><?php echo htmlspecialchars($item['df_qty'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($item['df_unit'] ?? ''); ?></td>
                    <td><?php echo number_format($item['df_amount'] ?? 0, 2); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5">No items found.</td></tr>
            <?php endif; ?>
            <?php for ($i = count($itemData); $i < 5; $i++): ?>
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
            <?php endfor; ?>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Grand Total:</td>
                <td style="font-weight: bold;">₱<?php echo number_format($grandTotal, 2); ?></td>
            </tr>
        </tbody>
    </table>
    <div class="signature-section">
  <div class="signature-block" style="text-align: left;">
    <strong>Requested By:</strong> <span style="text-decoration: underline;"><?php echo htmlspecialchars($headerData['df_reqstby'] ?? ''); ?></span>
  </div>
  <div class="signature-block" style="text-align: right;">
    <strong>Stockroom Clerk:</strong> <span style="text-decoration: underline;"><?php echo htmlspecialchars($headerData['staff_id'] ?? ''); ?></span>
  </div>
</div>
<div class="control-number" style="text-align: right; margin-top: 10px; font-size: 0.5em; font-weight: bold;">
  Control No: <?php echo $prsData['control_number'] ?? 'DBC-2025'; ?>
</div>


</div>
</body>
</html>

