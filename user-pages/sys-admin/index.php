<?php 
session_start();

if (!isset($_SESSION['uaid']) || $_SESSION['status'] !== '1' || $_SESSION['modid'] != 0) {
  header("Location: ../authentication/");
  exit();
}

include ('../../utilities/header.php');
include ('../../utilities/navbar.php');

$total_menu = $system->getTotalMenu($db);
$total_position = $system->getTotalPositions($db);

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
                  SYSTEM ADMIN
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                  <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-add-user">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Add New User
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
              <?php include ('sys-total/total.php'); ?>
              <?php include ('sys-tables/table.php'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- modals -->
    <?php include ('sys-modals/modal.php'); ?>


<script src="../../dist/js/jquery-3.7.1.min.js"></script>
<link href="../../DataTables/datatables.min.css" rel="stylesheet">
<script src="../../DataTables/datatables.min.js"></script>
    <style>
        .clickable-user-card { cursor: pointer; }
        .clickable-user-card:hover { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
        .active-filter-card { border: 2px solid #007bff; }
    </style>
<script>
<?php include ('../../js/sys-admin.js'); ?>
</script>


    
<?php 
include ('../../utilities/footer.php');
include ('../../utilities/js.php');
?>