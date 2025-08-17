<?php
 session_start();
if (!isset($_SESSION['uaid']) || $_SESSION['status'] !== '1' || !in_array($_SESSION['modid'], [1, 6])) {
  header("Location: ../../utilities/logout.php");
    exit();
}
include ('../../utilities/header.php');
include ('../../utilities/navbar.php');
?>
<style>
  #eqTaggingTable_wrapper, #rrTable_wrapper, #rrDetailsTable_wrapper, #prsDetailsTable_wrapper, #itemsTable_wrapper, #dfTable_wrapper, #dfDetailsTable_wrapper,#categoryTable_wrapper { overflow-x: hidden !important; }
  #eqTaggingTable, #rrTable, #rrDetailsTable, #prsDetailsTable, #itemsTable, #dfTable, #dfDetailsTable, #categoryTable { width: 100% !important; table-layout: fixed; }
  .dataTables_wrapper { overflow-x: auto; }
</style>
<script src="../../dist/js/jquery-3.7.1.min.js"></script>
<link href="../../DataTables/datatables.min.css" rel="stylesheet">
<script src="../../DataTables/datatables.min.js"></script>

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
                  <?php echo ($_SESSION['modid'] == 6) ? "STOCKROOM" : "PROPERTY CUSTODIAN"; ?>
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                <div class="dropdown">
                  <button class="btn btn-primary dropdown-toggle d-none d-sm-inline-block" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ✚ Insert New
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php if ($_SESSION['modid'] != 1): ?>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-items">
                      📦 Item
                      </a>
                      <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-rr">   
                      🫴 Receiving Report
                      </a>
                    <?php endif; ?>
                    <?php if ($_SESSION['modid'] != 6): ?>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-eq-tagging">
                      🏷️ Equipment-Item Tagging
                    </a>
                    <?php endif; ?>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-df">   
                      🚀 Deployment Form
                      </a>
                  </div>
                </div>
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

            include ('custodian-total/total.php');
            include ('custodian-modals/insert_modal.php');   
            include ('custodian-modals/rr_modal.php');     
            include ('custodian-modals/modal_table.php');
            include ('custodian-modals/modal.php'); 
            include ('custodian-modals/eq_modal.php'); 
            include ('custodian-modals/df_modal.php');
            ?>


            </div>
          </div>
        </div>
        
      </div>
        <?php 
        ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script>
const sessionModid = <?= json_encode($_SESSION['modid']) ?>;
const modid = <?= $_SESSION['modid']; ?>;
<?php include ('../../js/custodian.js'); ?>

</script>
<?php
include ('../../utilities/footer.php');
include ('../../utilities/js.php');
?>