<?php
require '../../../core/dbsys.ini'; // assumes this sets up $db (PDO)
require '../../../query/requestor.qry'; // assumes this contains REQUESTOR class

header('Content-Type: application/json');

try {
    $requestor = new REQUESTOR();
    $items = $requestor->getCategorizedItems1($db); // expects to return array of items
    echo json_encode($items);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch items', 'details' => $e->getMessage()]);
}
?>
