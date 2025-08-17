<div class="modal modal-blur fade" id="modal-add-rr" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Receiving Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="rrForm">
        <div class="modal-body">
          <!-- Steps Progress -->
          <div class="card-body">
            <ul class="steps steps-green steps-counter my-4">
            <li class="step-item active" id="rr-step-1">RR Details</li>
            <li class="step-item" id="rr-step-2">RR Items</li>
            </ul>
          </div>

          <!-- Step 1: RR Details -->
          <div class="step-content" id="rr-step-content-1">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">RR No</label>
                <input type="text" class="form-control" name="rr_no" id="rr_no" placeholder="Auto Generated" disabled>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Received From</label>
                <select class="form-control" name="received_from" id="received_from" required>
                  <option value="" selected disabled>-- Select Supplier --</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Date Received</label>
                <?php
                  $today = date('Y-m-d');
                  echo '<input type="date" class="form-control" name="date_received" id="date_received" value="' . $today . '" required>';
                ?>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Invoice No</label>
                <?php function generateInvoiceNumber() { return "INV-" . date("Y") . "-" . sprintf("%06d", mt_rand(1, 999999)); } $invoiceNumber = generateInvoiceNumber(); ?>
                <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="<?php echo $invoiceNumber; ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Invoice Date</label>
                <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>
          </div>

          <!-- Step 2: RR Items -->
          <div class="step-content d-none" id="rr-step-content-2">
            <div class="col-12">
              <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                      <tr>
                        <th class="">Item No.</th>
                        <th style="width: 220px; padding: 5px;">Particulars</th>
                        <th>PRS No</th>
                        <th>PRS Date</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th class="">Action</th>
                      </tr>
                    </thead>
                    <tbody id="rrItemsTableBody">
                      <!-- Dynamic rows will be added here -->
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <button type="button" class="btn btn-success" onclick="addRRRow()">Add Item</button>
                </div>
              </div>
            </div>

            <!-- Moved Fields: Received By & Department -->
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <label class="form-label">Received By</label>
                <input type="text" class="form-control" name="received_by" id="received_by" value="<?php echo $_SESSION['name']; ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Department</label>
                <select class="form-select" name="department" id="department" required>
                  <option value="" disabled>Select Department</option>
                  <?php
                    $departments = $requestor->getDepartments($db);
                    foreach ($departments as $dept): ?>
                      <option value="<?php echo htmlspecialchars($dept['dept_id']); ?>">
                        <?php echo htmlspecialchars($dept['dept_name']); ?>
                      </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <!-- End Moved Fields -->
          </div>
        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="rr-prevBtn" onclick="prevStepRR()">Previous</button>
        <button type="button" class="btn btn-primary" id="rr-nextBtn" onclick="nextStepRR()">Next</button>
        <button type="submit" class="btn btn-success d-none" id="rr-submitBtn">Submit Receiving Report</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-add-eq-tagging" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Equipment Tagging</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="eqTaggingForm">
        <div class="modal-body">
          <div class="card-body">
            <ul class="steps steps-green steps-counter my-4">
            <li class="step-item active" id="eq-step-1">Equipment Details</li>
            <li class="step-item" id="eq-step-2">Tagged Items</li>
            </ul>
          </div>

          <div class="step-content" id="eq-step-content-1">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="eq_rr_no" class="form-label">Receiving Report No.</label>
                <select class="form-select" id="eq_rr_no" name="eq_rr_no" required>
                  <option value="">Select RR No.</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" class="form-control" name="emp_id" id="emp_id" readonly>
                <!-- Hidden input for actual employee ID 
                 <input type="hidden" name="emp_id">-->
                
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="eq_loc" id="eq_loc" readonly>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Department</label>
                <input type="text" class="form-control" name="eq_dept" id="eq_dept" readonly>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="eq_date" id="eq_date" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
          </div>


          <div class="step-content d-none" id="eq-step-content-2">
            <div class="col-12">
              <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                      <tr>
                        <th>Item No</th>
                        <th>Item Description</th>
                        <th>Property Code</th>
                        <th>Quantity</th>
                        <th>Expected Life Span</th>
                        <th>Remarks</th>
                        <!--<th class="w-1">Action</th>-->
                      </tr>
                    </thead>
                    <tbody id="eqTaggingDetailsTableBody">
                      </tbody>
                  </table>
                </div>
                <!--
                <div class="card-footer">
                  <button type="button" class="btn btn-success" onclick="addEqRow()">Add Item</button>
                </div>-->
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="eq-prevBtn" onclick="navigatePrevStepEq()">Previous</button>
        <button type="button" class="btn btn-primary" id="eq-nextBtn" onclick="navigateNextStepEq()">Next</button>
        <button type="submit" class="btn btn-success d-none" id="eq-submitBtn">
            Submit Equipment Tagging
          </button>
        </div>
      </form>
    </div>
  </div>
</div>