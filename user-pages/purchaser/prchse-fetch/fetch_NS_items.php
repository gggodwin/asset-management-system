<?php
include '../../../core/dbsys.ini'; // Database connection
include '../../../query/purchaser.qry'; // Include the PURCHASER class

header('Content-Type: application/json');

// Instantiate the PURCHASER class
$purchaser = new PURCHASER();

// Call the function to fetch items
$items = $purchaser->fetchAllItems($db);

// Return the items as a JSON response
echo json_encode($items);
?>
