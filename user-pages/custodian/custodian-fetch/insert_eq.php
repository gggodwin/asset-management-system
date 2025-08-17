<?php
require_once '../../../core/dbsys.ini'; // Adjust path as needed
session_start();
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (
        empty($_POST['emp_id']) ||
        empty($_POST['eq_loc']) ||
        empty($_POST['eq_date']) ||
        empty($_POST['eq_dept']) ||  // Department
        empty($_POST['it_no']) ||
        empty($_POST['pr_code']) ||
        empty($_POST['quantity']) ||
        empty($_POST['eq_remarks'])
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
        exit;
    }

    try {
        $db->beginTransaction();

        $emp_id = $_POST['emp_id'];
        $eq_loc = $_POST['eq_loc'];
        $eq_date = $_POST['eq_date'];
        $eq_dept = $_POST['eq_dept'];
        $rr_no = $_POST['eq_rr_no'];

        $it_no = $_POST['it_no'];
        $pr_codes = $_POST['pr_code'];
        $quantities = $_POST['quantity'];
        $lifespans = $_POST['eq_lifespan'];
        $remarks = $_POST['eq_remarks'];

        // Consistency checks...

        $allEqData = [];

        // Track max suffix per prefix
        $suffixCounters = [];

        for ($i = 0; $i < count($it_no); $i++) {
            $itemCode = $it_no[$i];
            $basePrCode = $pr_codes[$i];
            $quantity = intval($quantities[$i]);
            $remarksItem = $remarks[$i];
            $lifespanItem = floatval($lifespans[$i]);

            $prefix = substr($basePrCode, 0);

            if (!isset($suffixCounters[$prefix])) {
                $sql = "SELECT MAX(CAST(SUBSTRING(pr_code, LENGTH(?) + 1) AS UNSIGNED)) as max_suffix FROM dbpis_eq_tag_details WHERE pr_code LIKE CONCAT(?, '%')";
                $stmt = $db->prepare($sql);
                $stmt->execute([$prefix, $prefix]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $suffixCounters[$prefix] = $row && $row['max_suffix'] !== null ? (int)$row['max_suffix'] : 0;
            }

            // Now, for each unit, create its own header + detail
            for ($j = 1; $j <= $quantity; $j++) {
                $suffixCounters[$prefix]++;
                $suffix = str_pad($suffixCounters[$prefix], 3, '0', STR_PAD_LEFT);
                $fullPrCode = $prefix . $suffix;

                // Insert header for this unit
                $queryTagging = "INSERT INTO dbpis_eq_tagging (emp_id, eq_loc, eq_date, eq_dept, rr_no) VALUES (?, ?, ?, ?, ?)";
                $stmtTagging = $db->prepare($queryTagging);
                $stmtTagging->execute([$emp_id, $eq_loc, $eq_date, $eq_dept, $rr_no]);
                $eq_no = $db->lastInsertId();

                // Insert detail for this header
                $queryDetails = "INSERT INTO dbpis_eq_tag_details (eq_no, it_no, pr_code, quantity, eq_remarks, expected_life_span, status) VALUES (?, ?, ?, 1, ?, ?, 'Good')";
                $stmtDetails = $db->prepare($queryDetails);
                $stmtDetails->execute([$eq_no, $itemCode, $fullPrCode, $remarksItem, $lifespanItem]);

                // Fetch inserted data
                $queryTaggingFetch = "SELECT dbpis_eq_tagging.*, 
                                     CONCAT(dbpis_staff.surname, ' ', dbpis_staff.firstname,
                                            CASE WHEN dbpis_staff.midname IS NOT NULL THEN CONCAT(' ', dbpis_staff.midname) ELSE '' END,
                                            CASE WHEN dbpis_staff.extension IS NOT NULL THEN CONCAT(' ', dbpis_staff.extension) ELSE '' END) AS emp_name
                              FROM dbpis_eq_tagging
                              JOIN dbpis_staff ON dbpis_eq_tagging.emp_id = dbpis_staff.staff_id
                              WHERE dbpis_eq_tagging.eq_no = ?";
                $stmtFetch = $db->prepare($queryTaggingFetch);
                $stmtFetch->execute([$eq_no]);
                $eqData = $stmtFetch->fetch(PDO::FETCH_ASSOC);

                $queryItemsFetch = "SELECT * FROM dbpis_eq_tag_details WHERE eq_no = ?";
                $stmtItems = $db->prepare($queryItemsFetch);
                $stmtItems->execute([$eq_no]);
                $eqData['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                $allEqData[] = $eqData;
            }
        }

        $db->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Equipment tagging successfully added.',
            'eqData' => $allEqData
        ]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
