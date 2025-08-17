<?php
 require_once '../../../query/requestor.qry';
 require_once '../../../core/dbsys.ini';
 $requestor = new REQUESTOR();

 $items = $requestor->getInventoryItems($db);

 echo json_encode($items);
 ?>