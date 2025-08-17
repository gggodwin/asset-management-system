<?php
require '../../../core/dbsys.ini';
require '../../../query/requestor.qry';

$requestor = new REQUESTOR();
echo json_encode(isset($_GET['prs_code']) ? $requestor->getPurchaseRequisitionDetails($db, $_GET['prs_code']) : ["success" => false]);

?>
