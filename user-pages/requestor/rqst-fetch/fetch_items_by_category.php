<?php
// rqst-fetch/fetch_items_by_category.php

require '../../../core/dbsys.ini'; // Adjust the path if needed
require '../../../query/requestor.qry'; // Adjust the path if needed

$requestor = new REQUESTOR();

// Get the category ID from the query string
$categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

if ($categoryId !== null) {
    // Call the function from your REQUESTOR class
    $items = $requestor->getItemsByCategory($db, $categoryId); // Assuming you create this function in REQUESTOR Class

    // Return the results as JSON
    echo json_encode($items);
} else {
    // Handle missing category ID
    echo json_encode(array("error" => "Category ID not provided."));
}
?>