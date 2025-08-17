<?php
// Start session to access user data
session_start();
require '../../../core/dbsys.ini';
require '../../../query/requestor.qry';

$requestor = new REQUESTOR();

// Get current page and search term from request
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$prs_code = isset($_GET['prs_code']) ? trim($_GET['prs_code']) : "";

// User role checks
$modid = $_SESSION['modid'] ?? null;
$isCustodian = ($modid == 4);
$isAdminOrPurchaser = in_array($modid, [4, 5]);

// Fetch PR details
$result = $requestor->getPRDetailsByCode($db, $prs_code);
if (isset($result['error'])) {
    echo $result['error'];
    exit;
}
$prsData = $result['prsData'];
$prsItems = $result['prsItems'];

// Fetch PR list for requestor
$prData = $requestor->fetchPRsByRequestor($db, $page, 10, $search);
$prsList = $prData['prs'];
$totalCount = $prData['total_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Purchase Requisition Slip</title>
<style>
    body { margin: 0; font-family: Arial, sans-serif; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: #000; }
    .prs-slip { width: 98%; max-width: 900px; background-color: #fff; padding: 15px; border: 1px solid #000; box-sizing: border-box; font-size: 1em; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    /* Logo & Company Header */
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
    .grand-total { text-align: right; margin-top: 10px; font-weight: bold; }
    .prs-footer { margin-top: 15px; display: flex; justify-content: space-between; flex-wrap: wrap; font-size: 0.9em; border-top: 1px solid #000; padding-top: 10px; }
    .prs-footer div { margin-bottom: 5px; }
    @media print {
    body { display: block; background: #fff; margin: 0; }
    .prs-slip { margin: 0 auto; border: 1px solid #000; border-bottom: 2px solid #000; box-shadow: none; }
}
</style>
</head>
<body>
<div class="prs-slip">

    <!-- Header Section -->
    <div class="header-section">
        <img src="../../../static/dbclogo.png" alt="Left Logo">
        <div class="company-info">
            <h2>DON BOSCO COLLEGE, INC.</h2>
            <p>Canlubang, Calamba City, Laguna</p>
        </div>
        <img src="../../../static/dbclogo2.png" alt="Right Logo">
    </div>

    <h1>Purchase Requisition Slip</h1>

    <!-- PRS Header Info -->
    <div class="prs-header">
        <div>Requesting Department: <span><?php echo $prsData['department']; ?></span></div>
        <div>PRS No: <span><?php echo $prsData['prs_code']; ?></span></div>
    </div>
    <div class="prs-header">
        <div>Date Prepared: <span><?php echo $prsData['date_requested']; ?></span></div>
        <div>Date Needed: <span><?php echo $prsData['date_needed']; ?></span></div>
    </div>
    <div class="prs-header">
        <div>Purpose: <span><?php echo $prsData['remarks']; ?></span></div>
        <div>Location: <span><?php echo $prsData['unit_name']; ?></span></div>
    </div>

    <!-- Items Table -->
    <table class="prs-table">
    <thead>
        <tr>
            <th>ITEM NO.</th>
            <th>PARTICULARS</th>
            <th>QUANTITY</th>
            <th>UNIT</th>
            <?php if ($isCustodian): ?>
                <th>UNIT PRICE</th>
                <th>TOTAL PRICE</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $numItems = count($prsItems);
        $minRows = 5;
        $grandTotal = 0;

        foreach ($prsItems as $index => $item):
            $isCanceled = isset($item['status']) && $item['status'] == 0;
            $rowClass = $isCanceled ? 'canceled-item' : '';
            $itemDescription = $isCanceled ? '<del>' . $item['item_description'] . '</del> (Canceled)' : $item['item_description'];

            if (!isset($item['status']) || $item['status'] != 0) {
                $grandTotal += $item['total_price'];
            }
        ?>
            <tr class="<?php echo $rowClass; ?>">
                <td style="text-align: center;"><?php echo $index + 1; ?></td>
                <td><?php echo $itemDescription; ?></td>
                <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                <td style="text-align: center;"><?php echo $item['unit_type']; ?></td>
                <?php if ($isCustodian): ?>
                    <td style="text-align: right;"><?php echo number_format($item['unit_price'], 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($item['total_price'], 2); ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        <?php for ($i = $numItems; $i < $minRows; $i++): ?>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <?php if ($isCustodian): ?>
                    <td></td>
                    <td></td>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>

        <?php if ($isCustodian): ?>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Grand Total</td>
                <td style="text-align: right; font-weight: bold;"><?php echo number_format($grandTotal, 2); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


    <!-- Remarks Sections -->
    <?php if ($isAdminOrPurchaser && (!empty($prsData['admin_remarks']) && strtoupper(trim($prsData['admin_remarks'])) !== 'N/A')): ?>
    <div class="remarks-section">
        <strong>Admin Remarks:</strong>
        <span><?php echo $prsData['admin_remarks']; ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($prsData['purchase_remarks']) && strtoupper(trim($prsData['purchase_remarks'])) !== 'N/A'): ?>
    <div class="remarks-section purchase-remarks">
        <strong>Purchase Remarks:</strong>
        <span><?php echo $prsData['purchase_remarks']; ?></span>
    </div>
<?php endif; ?>

    <!-- Footer -->
    <div class="prs-footer">
        <div>Prepared by: <strong><span><?php echo $prsData['requested_by']; ?></span></strong></div>
        <div>Department Head: <strong><span><?php echo $prsData['dept_head'] ?? 'Not yet verified'; ?></span></strong></div>
        <div>Approved by: <strong><span><?php echo !empty($prsData['approved_by']) ? $prsData['approved_by'] : 'Awaiting Approval'; ?></span></strong></div>
    </div>
    
    <div class="control-number" style="text-align: right; margin-top: 5px; font-size: 0.5em; font-weight: bold;">
    Control No: <?php echo $prsData['control_number'] ?? 'DBC-2025'; ?>
    </div>
    

</div>
</body>
</html>
