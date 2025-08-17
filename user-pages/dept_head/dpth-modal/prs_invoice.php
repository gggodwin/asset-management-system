<?php
// Start session to access user data
require '../../../core/dbsys.ini'; // Ensure database connection
require '../../../query/requestor.qry'; // Include requestor query class

// Create an instance of REQUESTOR
$requestor = new REQUESTOR();

// Get current page and search term from request
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Get the PR code from the URL
$prs_code = isset($_GET['prs_code']) ? trim($_GET['prs_code']) : "";

// Call the function to fetch PR details and items using the prs_code
$result = $requestor->getPRDetailsByCode($db, $prs_code);

// Check for errors or retrieve the data
if (isset($result['error'])) {
    echo $result['error'];
    exit;
}

$prsData = $result['prsData'];
$prsItems = $result['prsItems'];

// Fetch PRs for the logged-in requestor
$prData = $requestor->fetchPRsWithoutDeptHead($db, $page, 10, $search);
$prsList = $prData['prs'];
$totalCount = $prData['total_count'];

// Continue with rendering the PR data and items...
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requisition Slip</title>
    <style>
        body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f4f4f4; overflow-x: hidden; }
        .prs-slip { width: 90vw; max-width: 1200px; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; box-sizing: border-box; }
        .prs-slip h1 { text-align: center; font-size: 24px; }
        .prs-header, .prs-footer { display: flex; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; }
        .prs-header span, .prs-footer span, span { font-weight: bold; }
        .prs-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        .prs-table th, .prs-table td { border: 1px solid black; padding: 10px; text-align: center; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .status-default { color: gray; font-weight: bold; }
        .prs-footer {margin: 10px}
    </style>
</head>
<body>
    <div class="prs-slip">
        <h1>PURCHASE REQUISITION SLIP</h1>

        <div class="prs-header">
            <div>Requesting Department: <span id="department"><?php echo $prsData['department']; ?></span></div>
            <div>PRS No: <span id="prsNo"><?php echo $prsData['prs_code']; ?></span></div>
        </div>

        <div class="prs-header">
            <div>Date Prepared: <span id="datePrepared"><?php echo $prsData['date_requested']; ?></span></div>
            <div>Date Needed: <span id="dateNeeded"><?php echo $prsData['date_needed']; ?></span></div>
        </div>

        <div>Purpose: <span id="purpose"><?php echo $prsData['remarks']; ?></span></div>

        <table class="prs-table">
            <thead>
                <tr style="background-color: lightgray;">
                    <th>ITEM NO.</th>
                    <th>PARTICULARS</th>
                    <th>QUANTITY</th>
                    <th>UNIT</th>

                </tr>

            </thead>
            <tbody id="prsItems">
                <?php
                $numItems = count($prsItems);
                $maxRows = 5;
                $grandTotal = 0;

                foreach ($prsItems as $index => $item):
                    $grandTotal += $item['total_price'];
                ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $item['item_description']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['unit_type']; ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php for ($i = $numItems; $i < $maxRows; $i++): ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endfor; ?>

            </tbody>
        </table>

        <div class="prs-footer">
            <div>Prepared by: <span id="preparedBy"><?php echo $prsData['requested_by']; ?></span></div>
            <div>Department Head: <span id="deptHead"><?php echo $prsData['dept_head'] ? $prsData['dept_head'] : 'Not yet verified'; ?></span></div>
            <div>Approved by:  <strong><?php echo !empty($prsData['approved_by']) ? $prsData['approved_by'] : 'Awaiting Approval'; ?></strong>
                <!--<span id="approvedBy" class="<?php echo getStatusClass($prsData['approval_status']); ?>">
                    <?php echo !empty($prsData['approved_by']) ? $prsData['approved_by'] : 'Awaiting Approval'; ?>
                </span>-->
            </div>
        </div>
    </div>
</body>
</html>
