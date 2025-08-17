<?php
require '../../../core/dbsys.ini';
require '../../../query/purchaser.qry'; // Use the correct file where PURCHASER class is

session_start();
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_description = $_POST['item_description'] ?? null;

    if (!$item_description) {
        echo json_encode(["success" => false, "message" => "Missing item description."]);
        exit;
    }

    $added_by = $_SESSION['name'] ?? 'Unknown';

    $purchaser = new PURCHASER();
    $newItemId = $purchaser->addUncategorizedItem($db, $item_description, $added_by);

    if ($newItemId) {
        echo json_encode($newItemId); // Return item ID only
    } else {
        echo json_encode(0); // Indicate failure
    }
}
?>
