
<?php
session_start();

if (!isset($_SESSION['uaid']) || $_SESSION['status'] !== '1' || !in_array($_SESSION['modid'], [2])) {
    header("Location: ../../utilities/logout.php");
    exit();
}

include ('../../utilities/header.php');
include ('../../utilities/navbar.php');
?>

      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Dashboard
                </div>
                <h2 class="page-title">
                  REQUESTOR
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-prs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Add New PRS
                  </a>
                  <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="row row-deck row-cards">
              <?php 
              include ('rqst-total/total.php');
              include ('rqst-table/table.php'); 
              include ('rqst-modals/modal.php');
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../../dist/js/jquery-3.7.1.min.js"></script>
    <link href="../../DataTables/datatables.min.css" rel="stylesheet">
    <script src="../../DataTables/datatables.min.js"></script>
    <script>
    <?php include ('../../js/requestor.js');  ?>
    </script>
    <?php
    include ('../../utilities/footer.php');
    include ('../../utilities/js.php');
    ?>