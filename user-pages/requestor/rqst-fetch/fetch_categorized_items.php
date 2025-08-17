<?php
require '../../../core/dbsys.ini';
require '../../../query/requestor.qry';

$requestor = new REQUESTOR();

echo json_encode($requestor->getCategorizedItems($db));
?>