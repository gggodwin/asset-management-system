
<!--list of Approved by Dept head-->
<div class="modal modal-blur fade" id="modal-approved-prs" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">  <h5 class="modal-title text-primary">Approved PRs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-secondary">
          Below is the list of PRs approved by you.
        </div>
        <div class="table-responsive mt-3">
          <table class="table table-bordered table-striped">
            <thead class="table-primary">
              <tr>
                <th>PR Code</th>
                <th>Requested By</th>
                <th>Department</th>
                <th>Date Requested</th>
                <th>Dept Head</th>
              </tr>
            </thead>
            <tbody id="approvedPRsTableBody">
              </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> </div>
    </div>
  </div>
</div>

<!-- View Details-->
<!-- View Details Modal -->
<div class="modal modal-blur fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-primary">Purchase Requisition Details</div>
        <iframe id="prsDetailsFrame" style="width: 100%; height: 600px; border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printContent()">Print</button>
        <button type="button" class="btn btn-success" id="updatePRBtn" style="display: none;">Update</button>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="viewPRSDetailsModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-primary">Purchase Requisition Details</div>
        <iframe id="prsDetailsFrame2" style="width: 100%; height: 600px; border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printContent()">Print</button>
        <button type="button" class="btn btn-success" id="updatePRBtn" style="display: none;">Update</button>
      </div>
    </div>
  </div>
</div>


<!-- Update PR Status Modal -->
<div class="modal modal-blur fade" id="updatePRModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-primary">Update Purchase Requisition</div>
        <div class="text-secondary">
          Do you want to approve or reject PRS <strong id="modalPrsCode"></strong>?
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
        <button type="button" class="btn btn-success" id="confirmApprove">Approve</button>
      </div>
    </div>
  </div>
</div>


<!-- Success Modal -->
<div class="modal modal-blur fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-success">Success!</div>
        <div class="text-secondary" id="successMessage">
          Purchase Requisition has been successfully updated.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<!-- Admin Remarks Modal -->
<div class="modal fade" id="adminRemarksModal" tabindex="-1" aria-labelledby="adminRemarksModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="adminRemarksForm">
        <div class="modal-header">
          <h5 class="modal-title" id="adminRemarksModalLabel">Add Admin Remarks</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="remarks_prs_code" name="prs_code">
          <div class="mb-3">
            <label for="admin_remarks_text" class="form-label">Remarks</label>
            <textarea class="form-control" id="admin_remarks_text" name="admin_remarks" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit Remarks</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="prsModal" tabindex="-1" aria-labelledby="prsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg mx-auto w-100" role="document" style="max-width: 85%;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="prsModalLabel">List of Incoming And Completed Orders</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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


<!-- Modal -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg mx-auto w-100" style="max-width: 85%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assetModalLabel">Detailed Expenses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php include('../custodian/custodian-table/asset_table.php'); ?>
      </div>
    </div>
  </div>
</div>


