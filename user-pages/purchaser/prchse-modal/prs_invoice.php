<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchase Requisition Slip</title>
<style>
body { margin: 0; font-family: Arial, sans-serif; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: #000; }
.prs-slip { width: 98%; max-width: 900px; background-color: #fff; padding: 15px; border: 1px solid #000; box-sizing: border-box; font-size: 1em; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.header-section { display: flex; align-items: center; justify-content: center; border-bottom: 0px solid #000; padding-bottom: 10px; margin-bottom: 15px; gap: 20px; flex-wrap: wrap; }
.header-section img { max-height: 70px; }
.company-info { text-align: center; }
.company-info h2 { margin: 0; font-size: 1.2em; }
.company-info p { margin: 2px 0; font-size: 0.9em; }
.prs-slip h1 { text-align: center; margin: 10px 0; font-size: 1.3em; text-transform: uppercase; }
.prs-header { display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; }
.prs-header div { margin-bottom: 5px; }
.prs-header span { font-weight: bold; }
.prs-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.prs-table th, .prs-table td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 0.95em; }
.prs-table th { background-color: #f5f5f5; font-weight: bold; text-align: center; }
.prs-table tbody tr:nth-child(even) { background-color: #fafafa; }
.canceled-item td { color: #777; font-style: italic; }
.remarks-section { margin-top: 15px; padding: 8px; border: 1px solid #000; font-size: 0.9em; }
.grand-total-row td { font-weight: bold; background-color: #f5f5f5; }
.prs-footer { margin-top: 15px; display: flex; justify-content: space-between; flex-wrap: wrap; font-size: 0.9em; border-top: 1px solid #000; padding-top: 10px; }
.prs-footer div { margin-bottom: 5px; }
@media print { body { display: block; background: #fff; margin: 0; } .prs-slip { margin: 0 auto; border: 1px solid #000; border-bottom: 2px solid #000; box-shadow: none; } }
</style>
</head>
<body>
<?php
require_once '../../../core/dbsys.ini';
if (isset($_GET['prs_code'])) {
    $prsCode = $_GET['prs_code'];
    $stmt = $db->prepare("SELECT * FROM dbpis_prs WHERE prs_code = :prs_code");
    $stmt->bindParam(':prs_code', $prsCode);
    $stmt->execute();
    $prsData = $stmt->fetch(PDO::FETCH_ASSOC);
    $itemStmt = $db->prepare("SELECT * FROM dbpis_prsdetails WHERE prs_code = :prs_code");
    $itemStmt->bindParam(':prs_code', $prsCode);
    $itemStmt->execute();
    $prsItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    session_start();
    $modid = $_SESSION['modid'] ?? null;
} else {
    echo "No PRS code provided.";
    exit;
}
?>
<div class="prs-slip">
    <div class="header-section">
        <img src="../../../static/dbclogo.png" alt="Left Logo">
        <div class="company-info">
            <h2>DON BOSCO COLLEGE, INC.</h2>
            <p>Canlubang, Calamba City, Laguna</p>
        </div>
        <img src="../../../static/dbclogo2.png" alt="Right Logo">
    </div>
    <h1>Purchase Requisition Slip</h1>
    <div class="prs-header">
        <div>Requesting Department: <span><?php echo $prsData['department']; ?></span></div>
        <div>PRS No: <span><?php echo $prsData['prs_code']; ?></span></div>
    </div>
    <div class="prs-header">
        <div>Date Prepared: <span><?php echo $prsData['date_requested']; ?></span></div>
        <div>Date Needed: <span><?php echo $prsData['date_needed']; ?></span></div>
    </div>
    <div>Purpose: <span><?php echo $prsData['remarks']; ?></span></div>
    <table class="prs-table">
        <thead>
            <tr>
                <th>ITEM NO.</th>
                <th>PARTICULARS</th>
                <th>QUANTITY</th>
                <th>UNIT</th>
                <th>SUPPLIER</th>
                <th>UNIT PRICE</th>
                <th>TOTAL PRICE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $numItems = count($prsItems);
            $maxRows = 5;
            $grandTotal = 0;
            foreach ($prsItems as $index => $item):
                $isCanceled = $item['status'] == 0;
                if (!$isCanceled) { $grandTotal += $item['total_price']; }
                $rowClass = $isCanceled ? 'canceled-item' : '';
                $itemDescription = $isCanceled ? '<del>'.$item['item_description'].'</del> (Canceled)' : $item['item_description'];
            ?>
            <tr class="<?php echo $rowClass; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $itemDescription; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['unit_type']; ?></td>
                <td><?php echo $item['supplier']; ?></td>
                <td style="text-align:right;">₱<?php echo number_format($item['unit_price'], 2); ?></td>
                <td style="text-align:right;">₱<?php echo number_format($item['total_price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php for ($i = $numItems; $i < $maxRows; $i++): ?>
            <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endfor; ?>
            <tr class="grand-total-row">
                <td colspan="6" style="text-align:right;">Grand Total:</td>
                <td style="text-align:right;">₱<?php echo number_format($grandTotal, 2); ?></td>
            </tr>
        </tbody>
    </table>
    <?php if (!empty($prsData['admin_remarks']) && strtoupper($prsData['admin_remarks']) !== 'N/A'): ?>
    <div class="remarks-section"><strong>Admin Remarks:</strong> <?php echo $prsData['admin_remarks']; ?></div>
    <?php endif; ?>
    <?php if (!empty($prsData['purchase_remarks']) && strtoupper($prsData['purchase_remarks']) !== 'N/A'): ?>
    <div class="remarks-section"><strong>Purchase Remarks:</strong> <?php echo $prsData['purchase_remarks']; ?></div>
    <?php endif; ?>
    <div class="prs-footer">
            <div>Prepared by: <span><strong><?php echo $prsData['requested_by']; ?></strong></span></div>
            <div>Department Head: <span><strong><?php echo $prsData['dept_head'] ?? 'Not yet verified'; ?></strong></span></div>
            <div>Approved by: <span><strong><?php echo !empty($prsData['approved_by']) ? $prsData['approved_by'] : 'Awaiting Approval'; ?></strong></span></div>
    </div>
    <div class="control-number" style="text-align: right; margin-top: 5px; font-size: 0.5em; font-weight: bold;">
    Control No: <?php echo $prsData['control_number'] ?? 'DBC-2025'; ?>
    </div>
</div>
</body>
</html>
