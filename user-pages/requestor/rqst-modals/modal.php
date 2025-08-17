<style>
  .input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }

  .manual-entry-wrapper small {
    display: block;
    margin-top: 4px;
    color: #6c757d;
  }
</style>
<div class="modal modal-blur fade" id="modal-add-prs" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Purchase Requisition</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="prsForm">
        <div class="modal-body">
          <!-- Steps Progress -->
          <div class="card-body">
            <ul class="steps steps-green steps-counter my-4">
              <li class="step-item active" id="step-1">Purchase Details</li>
              <li class="step-item" id="step-2">PR Items</li>
              <li class="step-item" id="step-3">Review & Submit</li>
            </ul>
          </div>

          <!-- Step 1: Purchase Details -->
          <div class="step-content" id="step-content-1">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">PR Code</label>
                <input type="hidden" name="prs_code" id="prs_code">
                <input type="text" class="form-control"  value="Auto-Generated" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Requested By</label>
                <input type="text" class="form-control" name="requested_by" id="requested_by" value="<?php echo $_SESSION['name']; ?>" readonly>
              </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department</label>
                    <select class="form-select" name="department" id="department" required>
                        
                        <?php
                        $departments = $requestor->getDepartments($db); // Fetch departments
                        foreach ($departments as $dept):
                            // Check if the department name matches the one in session
                            $selected = ($_SESSION['dept_name'] == $dept['dept_name']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo htmlspecialchars($dept['dept_name']); ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($dept['dept_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
              <div class="col-md-6 mb-3">
              <label class="form-label">Unit</label>
              <select class="form-select" name="unit_id" id="unit_id" required>
                <?php
                $units = $requestor->getUnits($db); // Make sure this function exists and fetches unit_id + unit_name
                foreach ($units as $unit): ?>
                  <option value="<?php echo htmlspecialchars($unit['unit_id']); ?>">
                    <?php echo htmlspecialchars($unit['unit_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Date Requested</label>
                <input type="date" class="form-control" name="date_requested" id="date_requested" value="<?php echo date('Y-m-d'); ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Date Needed</label>
                <input type="date" class="form-control" name="date_needed" id="date_needed" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
              </div>
                <input type="hidden" class="form-control" name="approval_status" value="Pending" readonly>
            </div>
          </div>

          <!-- Step 2: Purchase Requisition Items -->
          <div class="step-content d-none" id="step-content-2">
            <div class="col-12">
              <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                      <tr>
                        <th class="w-1">Item No.</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th class="w-5">Action</th>
                      </tr>
                    </thead>
                    <tbody id="purchaseRequisitionsDetailsTableBody">
                      <!-- class="w-1" Dynamic Rows Will Be Added Here -->
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-success" onclick="addRow()">Add Item</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 3: Review & Submit -->
          <div class="step-content d-none" id="step-content-3">
            <div class="mb-3">
              <label class="form-label">Purpose</label>
              <textarea class="form-control" name="remarks" id="remarks" rows="3" placeholder="Enter purpose" required></textarea>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="prevBtn" onclick="prevStep()" disabled>Previous</button>
          <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">Next</button>
          <button type="submit" class="btn btn-success d-none" id="submitBtn">
            Submit Purchase Requisition
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--modal for success-->
<div class="modal modal-blur fade" id="modal-pr-success" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-success"></div>
      <div class="modal-body text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
          <path d="M9 12l2 2l4 -4" />
        </svg>
        <h3>Purchase Requisition Submitted</h3>
        <div class="text-secondary">Your PR has been successfully submitted for approval.</div>
      </div>
    </div>
  </div>
</div>

<!-- Missing Fields Alert Modal -->
<div class="modal modal-blur fade" id="modal-missing-fields" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-warning">Missing Fields!</div>
        <div class="text-secondary" id="missingFieldsMessage">
          Please fill in all required fields before proceeding.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning w-100" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal modal-blur fade" id="modal-delete-confirm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-danger">Are you sure?</div>
        <div class="text-secondary">
          Do you really want to delete this purchase requisition? This action cannot be undone.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, delete PR</button>
      </div>
    </div>
  </div>
</div>


<!-- Success Delete Modal -->
<div class="modal modal-blur fade" id="modal-delete-success" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-success">Success!</div>
        <div class="text-secondary" id="deletePRMessage">
          PR has been successfully deleted.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Update Modal -->
<div class="modal modal-blur fade" id="modal-update-success" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-success">Success!</div>
        <div class="text-secondary" id="updatePRMessage">
          PR has been successfully updated.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<!--View Details-->

<div class="modal modal-blur fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title text-primary">Purchase Requisition Details</div>
        <iframe id="prsDetailsFrame" style="width: 100%; height: 600px; border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="printContent()">Print</button>
      </div>
    </div>
  </div>
</div>



<!-- Update PR Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Purchase Requisition</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="update_prs_form">
          <!-- Hidden Field for PR Code -->
          <input type="hidden" id="update_prs_code" name="prs_code">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="update_requested_by" class="form-label">Requested By</label>
              <input type="text" class="form-control" id="update_requested_by" name="requested_by" readonly>
            </div>
            <div class="col-md-6">
              <label for="update_department" class="form-label">Department</label>
              <select class="form-select" name="department" id="update_department" required>
                <option value="" disabled selected>Select Department</option>
                <?php
                $departments = $requestor->getDepartments($db);
                foreach ($departments as $dept): ?>
                  <option value="<?php echo htmlspecialchars($dept['dept_name']); ?>">
                    <?php echo htmlspecialchars($dept['dept_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="update_date_requested" class="form-label">Date Requested</label>
              <input type="date" class="form-control" id="update_date_requested" name="date_requested" readonly>
            </div>
            <div class="col-md-6">
              <label for="update_date_needed" class="form-label">Date Needed</label>
              <input type="date" class="form-control" id="update_date_needed" name="date_needed" required>
            </div>
          </div>
          
          <div class="mb-3">
            <div class="col-md-6">
                <label for="update_unit_id" class="form-label">Unit</label>
                <select class="form-select" name="unit_id" id="update_unit_id" required>
                  <option value="" disabled selected>Select Unit</option>
                  <?php
                  $units = $requestor->getUnits($db); // Ensure this function fetches unit_id and unit_name
                  foreach ($units as $unit): ?>
                    <option value="<?php echo htmlspecialchars($unit['unit_id']); ?>">
                      <?php echo htmlspecialchars($unit['unit_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
          </div>

          <div class="mb-3">
            <label for="update_remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="update_remarks" name="remarks" rows="2"></textarea>
          </div>

          <!-- PR Details Table -->
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Item Code</th>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody id="updateDetailsTableBody">
                <!-- Dynamic Rows Will Be Inserted Here -->
              </tbody>
            </table>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update PR</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add New Item Modal -->
<div class="modal fade" id="newItemModal" tabindex="-1" aria-labelledby="newItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newItemModalLabel">Add New Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="newItemForm">
          <div class="mb-3">
            <label for="newItemParticular" class="form-label">Particular</label>
            <input type="text" class="form-control" id="newItemParticular" name="particular" placeholder="Enter item name or description" required>
          </div>
          <div class="mb-3">
            <label for="newItemBrand" class="form-label">Brand</label>
            <input type="text" class="form-control" id="newItemBrand" name="brand" value="Any Brand">
          </div>
          <div class="mb-3">
              <label for="newItemCategory" class="form-label">Category</label>
              <select class="form-select" id="newItemCategory" name="category">
                  <option value="">Select category</option>
                  </select>
          </div>
          <div class="mb-3">
            <!--<label for="barcode" class="form-label">Barcode</label>-->
            <input type="hidden" class="form-control" id="barcode" name="barcode" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveNewItem(document.getElementById('newItemModal'), this)">Save Item</button>
      </div>
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


<script>

</script>

