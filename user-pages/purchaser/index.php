<?php
 session_start();
if (!isset($_SESSION['uaid']) || $_SESSION['status'] !== '1' || !in_array($_SESSION['modid'], [5])) {
    header("Location: ../../utilities/logout.php");
    exit();
}
include ('../../utilities/header.php');
include ('../../utilities/navbar.php');
?>


<!-- jQuery (Required for DataTables) -->
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
                  PURCHASER
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
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-supplier">
                     🚚 Supplier
                    </a>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-item-supplier">
                     🛒 Item Supplier
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
            include ('prchse-total/total.php');
            include ('prchse-table/table.php');
            ?>


            </div>
          </div>
        </div>
      </div>
        <?php
            include ('prchse-modal/modal.php');
         ?>
    </div>

<script>
    var modid = <?php echo $_SESSION['modid']; ?>;
    <?php 
    include ('../../js/purchaser.js');
    ?>

</script>






<?php
include ('../../utilities/footer.php');
include ('../../utilities/js.php');
?>