
<div class="modal modal-blur fade" id="prsModal" tabindex="-1" aria-labelledby="prsModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%; width: auto !important;">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="prsModalLabel">List of Incoming And Completed Orders</h5>
                <button type="button" class="btn-close  btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Place your existing PRS table here -->
                <div class="table-responsive">
                    <table id="prsDetailsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>PRS No.</th>
                                <th>Item Code</th>
                                <th>Item Description</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="prsDetailsTableBody">
                            <!-- Dynamic PRS Item rows will be inserted here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 80%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title" id="expensesModalLabel">Detailed Expenses</h5>
        <div class="row ms-auto align-items-center gx-2">
          <div class="col-md-auto">
            <div style="display: flex; gap: 8px; align-items: center;">
              <label for="minDate" style="font-size: 1rem; margin-bottom: 0; color: #FFF; white-space: nowrap;"></label>
              <input type="date" id="minDate" class="form-control" style="font-size: 1rem; padding: 0.2rem 0.4rem; height: auto; line-height: 1.5;">
            </div>
          </div>
          <div class="col-md-auto">
            <div style="display: flex; gap: 20px; align-items: center;">
              <label for="maxDate" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
              <input type="date" id="maxDate" class="form-control" style="font-size: 1rem; padding: 0.2rem 0.4rem; height: auto; line-height: 1.5;">
            </div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="assetRecordsTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Item Name</th>
                <th>Department</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>RR No.</th>
                <th>Received Date</th>
                <th>PRS No.</th>
                <th>PRS Date</th>
                <th>Total Amount</th>
                <th>Category</th>
              </tr>
            </thead>
            <tbody id="assetRecordsTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="totalItemsModal" tabindex="-1" aria-labelledby="totalItemsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 80%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; align-items: center;">
      <h5 class="modal-title" id="totalItemsModalLabel">
  <?php echo ($_SESSION['modid'] != 6) ? "List of Tagged Items" : "List of Items"; ?>
</h5>
        <div class="row ms-auto align-items-center gx-2">
          <div class="col-md-auto">
            <div style="display: flex; gap: 8px; align-items: center;">
              <label for="minDateItems" style="font-size: 1rem; margin-bottom: 0; color: #FFF;"></label>
              <input type="date" id="minDateItems" class="form-control" style="font-size: 1rem; padding: 0.2rem 0.4rem; height: auto; line-height: 1.5;">
            </div>
          </div>
          <div class="col-md-auto">
            <div style="display: flex; gap: 20px; align-items: center;">
              <label for="maxDateItems" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
              <input type="date" id="maxDateItems" class="form-control" style="font-size: 1rem; padding: 0.2rem 0.4rem; height: auto; line-height: 1.5;">
            </div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="itemsTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Barcode</th>
                <th>Particular</th>
                <th>Brand</th>
                <th>Quantity</th>
                <th>Units</th>
                <th>Category</th>
                <th>Last Updated</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="itemsTableBody">
              <!-- Dynamic rows -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dfModal" tabindex="-1" aria-labelledby="dfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title" id="dfModalLabel">Claim Forms List</h5>
        <div class="row ms-auto align-items-center gx-2">
          <div class="col-md-auto">
            <div style="display: flex; gap: 8px; align-items: center;">
              <label for="minDateDF" style="font-size: 1rem; margin-bottom: 0; color: #FFF;"></label>
              <input type="date" id="minDateDF" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
          </div>
          <div class="col-md-auto">
            <div style="display: flex; gap: 20px; align-items: center;">
              <label for="maxDateDF" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
              <input type="date" id="maxDateDF" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php $modid = $_SESSION['modid']; ?>
        <div class="card-body">
          <ul class="nav nav-tabs card-header-tabs" id="dfTabsModal" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="df-tab-modal" data-bs-toggle="tab" data-bs-target="#df-modal-tab" type="button" role="tab">Claim Forms</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="df-details-tab-modal" data-bs-toggle="tab" data-bs-target="#df-details-modal-tab" type="button" role="tab">Claim Forms Details</button>
            </li>
          </ul>

          <div class="tab-content pt-3" id="dfTabsModalContent">
            <div class="tab-pane fade show active" id="df-modal-tab" role="tabpanel">
              <div class="table-responsive">
                <table id="dfTable" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>DF No.</th>
                      <th>Staff</th>
                      <th>Department</th>
                      <th>Unit</th>
                      <th>DF Date</th>
                      <th>Requested By</th>
                      <th>RR No.</th>
                      <?php if ($modid != 1): ?>
                      <?php endif; ?>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="dfTableBody">
                    <!-- DF rows via AJAX -->
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="df-details-modal-tab" role="tabpanel">
              <div class="table-responsive">
                <table id="dfDetailsTable" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>DF No.</th>
                      <th>Item No.</th>
                      <th>Particular</th>
                      <th>Quantity</th>
                      <th>Unit</th>
                      <th>Amount</th>
                      <?php if ($modid != 6): ?>
                        <th>Equipment No.</th>
                      <?php endif; ?>
                    </tr>
                  </thead>
                  <tbody id="dfDetailsTableBody">
                    <!-- DF Details rows via AJAX -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- /.modal-body -->
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="modal-title" id="categoryModalLabel">Item Categories</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="categoryTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Group Name</th>
              </tr>
            </thead>
            <tbody id="categoryTableBody">
              <!-- Populated via JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

