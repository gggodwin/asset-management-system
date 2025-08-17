

<div class="modal modal-blur fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-hidden="true"
     style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="z-index: 1070;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title text-primary">Receiving Report Details</div>
                <iframe id="RRDetailsFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printContent()">
                    Print
                </button>
                <button type="button" class="btn btn-success" id="updatePRBtn" style="display: none;">
                    Updates
                </button>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal modal-blur fade" id="rrModal" tabindex="-1" aria-labelledby="rrModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 80%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title" id="rrModalLabel">Receiving Report Details</h5>
        <div class="row ms-auto align-items-center gx-2">
          <div class="col-md-auto">
            <div style="display: flex; gap: 8px; align-items: center;">
            <label for="minDateRR" style="font-size: 1rem; margin-bottom: 0; color: #FFF;"></label>
            <input type="date" id="minDateRR" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
          </div>
          <div class="col-md-auto">
          <div style="display: flex; gap: 20px; align-items: center;">
            <label for="maxDateRR" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
            <input type="date" id="maxDateRR" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-tabs" id="rrModalTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="modal-rr-tab" data-bs-toggle="tab" data-bs-target="#modal-rr" type="button" role="tab" aria-controls="modal-rr" aria-selected="true">Receiving Reports</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="modal-rr-details-tab" data-bs-toggle="tab" data-bs-target="#modal-rr-details" type="button" role="tab" aria-controls="modal-rr-details" aria-selected="false">RR Details</button>
          </li>
        </ul>

        <div class="tab-content mt-3" id="rrModalTabsContent">
          <!-- Receiving Reports Tab -->
          <div class="tab-pane fade show active" id="modal-rr" role="tabpanel" aria-labelledby="modal-rr-tab">
            <div class="table-responsive">
              <table id="rrTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>RR No.</th>
                    <th>Received From</th>
                    <th>Invoice No.</th>
                    <th>Invoice Date</th>
                    <th>Received By</th>
                    <th>Department</th>
                    <th>Unit</th>
                    <th>Date Received</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="rrTableBody">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- RR Details Tab -->
          <div class="tab-pane fade" id="modal-rr-details" role="tabpanel" aria-labelledby="modal-rr-details-tab">
            <div class="table-responsive">
              <table id="rrDetailsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>RR No.</th>
                    <th>PRS ID</th>
                    <th>PRS Date</th>
                    <th>Particulars</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Category</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="rrDetailsTableBody">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-rr">
          🫴 Create Receiving Report
        </button>
      </div>
    </div>
  </div>
</div>
















