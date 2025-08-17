<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receiving Report</title>
<style>
body { margin: 0; padding: 0; display: flex; justify-content: center; background: #f4f4f4; }
.rr-slip { width: 210mm; max-width: 100%; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); box-sizing: border-box; }
.header-section { display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
.header-section img { max-height: 100px; margin-right: 20px; }
.company-info h2 { margin: 0; font-size: 2.3em; font-weight: bold; }
.company-info p { margin: 2px 0; font-size: 1em; }
.rr-slip h1 { text-align: center; font-size: 24px; margin: 10px 0; }
.rr-header, .rr-footer { display: flex; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; }
.rr-header span, .rr-footer span { font-weight: bold; }
.rr-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
.rr-table th, .rr-table td { border: 1px solid black; padding: 8px; text-align: center; }
.status-canceled { color: gray; font-style: italic; }
@media print { @page { size: A4 portrait; margin: 10mm; } body { background: white; } .rr-slip { box-shadow: none; border: none; width: 100%; max-width: none; border-bottom: 2px solid black; padding-bottom: 10px; } }
</style>
</head>
<body>
<?php
require_once '../../../core/dbsys.ini';
if (isset($_GET['rr_no'])) {
    $rrNo = $_GET['rr_no'];
    $stmt = $db->prepare("SELECT * FROM dbpis_get_rr WHERE rr_no = :rr_no");
    $stmt->bindParam(':rr_no', $rrNo);
    $stmt->execute();
    $rrData = $stmt->fetch(PDO::FETCH_ASSOC);
    $itemStmt = $db->prepare("SELECT * FROM dbpis_get_rr WHERE rr_no = :rr_no");
    $itemStmt->bindParam(':rr_no', $rrNo);
    $itemStmt->execute();
    $rrItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No RR number provided.";
    exit;
}
?>
<div class="rr-slip">
    <div class="header-section">
        <img src="../../../static/dbclogo.png" alt="Logo">
        <div class="company-info">
            <h2>DON BOSCO COLLEGE, INC.</h2>
            <p>Canlubang, Calamba City, Laguna</p>
            <p>Tel. Nos.: (049) 549-1778; (049) 549-2307; (049) 549-2782</p>
            <p>Website: www@donboscocanlubang.edu.ph</p>
            <p>Non-Vat Reg. TIN: 000-422-649-000</p>
        </div>
        <img src="../../../static/dbclogo2.png" alt="Right Logo">
    </div>
<h1>
    RECEIVING REPORT 
    <span style="font-size: 1em; color: red; margin-left: 15px;">
        0<?php echo $rrData['rr_no']; ?>
    </span>
</h1>
<div class="rr-header">
    <div>Invoice No: <span><?php echo $rrData['invoice_no']; ?></span></div>
    <div>Invoice Date: <span><?php echo $rrData['invoice_date']; ?></span></div>
</div>
<div class="rr-header">
    <div>Received From: <span><?php echo $rrData['received_from']; ?></span></div>
    <div>Date Received: <span><?php echo $rrData['date_received']; ?></span></div>
</div>
    <table class="rr-table">
        <thead>
            <tr style="background-color: lightgray;">
                <th>ITEM NO.</th><th>PRS NO</th><th>PRS DATE</th><th>PARTICULARS</th><th>QTY</th><th>UNIT</th><th>UNIT PRICE</th><th>TOTAL PRICE</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $numItems = count($rrItems);
        $maxRows = 5;
        $grandTotal = 0;
        foreach ($rrItems as $index => $item):
            $isCanceled = $item['status'] == 'Canceled';
            if (!$isCanceled) { $grandTotal += $item['total_price']; }
            $rowClass = $isCanceled ? 'status-canceled' : '';
            $desc = $isCanceled ? '<del>' . $item['item_brand'] . ' - ' . $item['item_particular'] . '</del> (Canceled)' : $item['item_brand'] . ' - ' . $item['item_particular'];
        ?>
            <tr class="<?php echo $rowClass; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo $item['prs_no']; ?></td>
                <td><?php echo $item['prs_date']; ?></td>
                <td><?php echo $desc; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['unit']; ?></td>
                <td>₱<?php echo number_format($item['unit_price'], 2); ?></td>
                <td>₱<?php echo number_format($item['total_price'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php for ($i = $numItems; $i < $maxRows; $i++): ?>
            <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <?php endfor; ?>
        <tr>
            <td colspan="7" style="text-align: right; font-weight: bold;">Grand Total:</td>
            <td style="font-weight: bold;">₱<?php echo number_format($grandTotal, 2); ?></td>
        </tr>
        </tbody>
    </table>
    <div class="rr-footer" style="margin-top: 30px;">
        <div>Received By: <span><?php echo $rrData['received_by']; ?></span></div>
        <div>Department: <span><strong><?php echo $rrData['department']; ?></strong></span></div>
    </div>
    <div class="control-number" style="text-align: right; margin-top: 5px; font-size: 0.5em; font-weight: bold;">
    Control No: <?php echo $prsData['control_number'] ?? 'DBC-2025'; ?>
    </div>
</div>
</body>
</html>
