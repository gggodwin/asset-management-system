<?php
 session_start();
 if (!isset($_SESSION['uaid']) || $_SESSION['status'] !== '1' || !in_array($_SESSION['modid'], [ 3, 4])) {
  header("Location: ../../utilities/logout.php");
     exit();
 }

 if ($_SESSION['modid'] == 4) {
   $pageTitle = "ADMINISTRATOR";
 } else {
   $pageTitle = "DEPARTMENT HEAD";
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
                  <?php echo $pageTitle; ?>
                </h2>
              </div>
              <!-- Page title actions -->
              <?php if (isset($_SESSION['modid']) && $_SESSION['modid'] == 4): ?>
                <div class="col-auto ms-auto d-print-none">
                  <div class="btn-list">
                  <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assetModal">
                      👁 View Detailed Expenses
                    </a>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="row row-deck row-cards">

            <?php
            include ('../requestor/rqst-total/total.php');
            include ('../requestor/rqst-table/table.php');
            if ($_SESSION['modid'] == 4) {
            //include ('../custodian/custodian-table/asset_table.php');
          } 
            ?>
            
            </div>
          </div>
        </div>
      </div>
      <a href="#" id="scrollToTopBtn" class="btn btn-primary btn-icon" style="position: fixed; bottom: 40px; right: 40px; display: none; z-index: 999;">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M5 12l7 -7l7 7" />
        <path d="M12 5l0 14" />
    </svg>
</a>

            <?php  include ('dpth-modal/modal.php'); ?>

    </div>

    <script src="../../dist/js/jquery-3.7.1.min.js"></script>
    <link href="../../DataTables/datatables.min.css" rel="stylesheet">
    <script src="../../DataTables/datatables.min.js"></script>
    <script>
    var modid = <?php echo $_SESSION['modid']; ?>;
    <?php 
    include ('../../js/depthead.js'); 
    //include ('../../js/sse.js');
    if ($_SESSION['modid'] == 4) {
      include ('../../js/asset.js');
  } 
    ?>

$(document).ready(function () {
    var scrollToTopBtn = $('#scrollToTopBtn');

    // Show or hide the button based on scroll
    $(window).scroll(function () {
      if ($(this).scrollTop() > 200) {
        scrollToTopBtn.fadeIn();
      } else {
        scrollToTopBtn.fadeOut();
      }
    });

    // Smooth scroll to top on click
    scrollToTopBtn.click(function (e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, '300');
    });
  });

    </script>
    <?php
    include ('../../utilities/footer.php');
    include ('../../utilities/js.php');
    ?>