<div class="modal modal-blur fade" id="modal-add-items" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addItemForm">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Barcode ID</label>
                <input type="text" class="form-control" name="barcode" id="barcode" placeholder="Generating..." readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="particular" placeholder="Enter product name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" name="brand" placeholder="Enter brand" required>
              </div>
            </div>
          
            <div class="col-md-6">
              <!--
              <div class="mb-3">
                <label class="form-label">Quantity</label>
                
              </div>-->
              <input type="hidden" class="form-control" name="quantity" id="quantity" placeholder="Enter quantity" readonly>
              <div class="mb-3">
                <label class="form-label">Units</label>
                <select class="form-select" name="units" id="unitsSelect" required>
                  <option value="pcs" selected>Pieces (pcs)</option>
                  <option value="box">Box</option>
                  <option value="pack">Pack</option>
                  <option value="set">Set</option>
                  <option value="roll">Roll</option>
                  <option value="liter">Liter (L)</option>
                  <option value="gallon">Gallon</option>
                  <option value="kilogram">Kilogram (kg)</option>
                  <option value="gram">Gram (g)</option>
                  <option value="meter">Meter (m)</option>
                  <option value="foot">Foot (ft)</option>
                  <option value="yard">Yard (yd)</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category" id="categorySelect" required>
                  <option value="" disabled selected>Select a category</option>
                  <?php
                    $categories = $requestor->getCategories($db);
                    $grouped = [];

                    foreach ($categories as $cat) {
                        $grouped[$cat['itemcatgrp_name']][] = $cat;
                    }

                    foreach ($grouped as $groupName => $cats):
                  ?>
                    <optgroup label="<?php echo htmlspecialchars($groupName); ?>">
                      <?php foreach ($cats as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['itcat_id']); ?>">
                          <?php echo htmlspecialchars($cat['itcat_name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Add Item
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal modal-blur fade" id="modal-update-item" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateItemForm">
        <div class="modal-body">
          <input type="hidden" name="barcode" id="update_barcode">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="particular" id="update_particular" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" name="brand" id="update_brand" required>
              </div>
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select class="form-select" name="category" id="update_category" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                  $categories = $requestor->getCategories($db);
                  $grouped = [];

                  foreach ($categories as $cat) {
                      $grouped[$cat['itemcatgrp_name']][] = $cat;
                  }

                  foreach ($grouped as $groupName => $cats):
                ?>
                  <optgroup label="<?php echo htmlspecialchars($groupName); ?>">
                    <?php foreach ($cats as $cat): ?>
                      <option value="<?php echo htmlspecialchars($cat['itcat_id']); ?>">
                        <?php echo htmlspecialchars($cat['itcat_name']); ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
              </select>
            </div>

            </div>
            <div class="col-md-6">
              <!--
              <div class="mb-3">
                <label class="form-label">Quantity</label>
                
                </div>-->
                <input type="hidden" class="form-control" name="quantity" id="update_quantity" readonly>
              
              <div class="mb-3">
                <label class="form-label">Units</label>
                <select class="form-select" name="units" id="update_units" required>
                  <option value="pcs">Pieces</option>
                  <option value="kg">Kilograms</option>
                  <option value="ltr">Liters</option>
                  <option value="meters">Meters</option>
                  <option value="tubes">Tubes</option>
                  <option value="box">Box</option>
                  <option value="pack">Pack</option>
                  <option value="set">Set</option>
                  <option value="roll">Roll</option>
                  <option value="gallon">Gallon</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</a>
          <button type="submit" class="btn btn-primary ms-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Update Item
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Success Modal -->
<div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3 id="modal-success-title">Success!</h3>
                <div id="modal-success-message" class="text-secondary">
                    Operation completed successfully.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-eq-tagging-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3 id="modal-add-eq-tagging-success-title">Success!</h3>
                <div id="modal-add-eq-tagging-success-message" class="text-secondary">
                    Equipment tagging added successfully.
                </div>
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
                <div class="text-secondary">Do you really want to delete this item? This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, delete item</button>
            </div>
        </div>
    </div>
</div>

<!-- Equipment Tagging Details Modal -->
<div class="modal modal-blur fade" id="eqDetailsModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="z-index: 1070;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title text-primary">Equipment Tagging Details</div>

                <div class="iframe-container" style="display: flex; justify-content: center; align-items: center; height: 600px;">
                    <iframe id="eqDetailsIframe" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">
                    Close
                </button>

                <button type="button" class="btn btn-primary" onclick="printIframe()">
                    Print
                </button>
                <!--
                <button type="button" class="btn btn-success" id="updateBtn" style="display: none;" onclick="updateEqDetails()">
                    Update
                </button>-->
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="viewPRSDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title text-primary">Purchase Requisition Details</div>
                <iframe id="prsDetailsFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printPRSContent()">
                    Print
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-blur fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true" style="z-index: 1060;" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog " style="z-index: 1070;">
    <form id="transferForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transferModalLabel">Transfer Equipment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="transferEqNo" name="eq_no">
            <input type="hidden" id="oldUnit" name="old_unit">

            <div class="mb-3">
                <label for="deptUnit" class="form-label">New Unit</label>
                <select class="form-select" id="deptUnit" name="dept_unit" required>
                    <option value="" disabled selected>Select Unit</option>
                    <?php
                    $units = $requestor->getUnits($db); // Fetch unit_id + unit_name
                    foreach ($units as $unit): ?>
                        <option value="<?php echo htmlspecialchars($unit['unit_id']); ?>">
                            <?php echo htmlspecialchars($unit['unit_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="transferDate" class="form-label">Transfer Date</label>
                <input type="date" class="form-control" id="transferDate" name="transfer_date" required>
            </div>
            <div class="mb-3">
                <label for="receivedBy" class="form-label">Received By</label>
                <select class="form-select" id="receivedBy" name="received_by" required>
                    <option value="" disabled selected>Select Employee</option>
                    <?php
                    $employees = $propertyCustodian->getEmployees($db); // Assuming $custodian is an object with this method
                    foreach ($employees as $employee): ?>
                        <option value="<?php echo htmlspecialchars($employee['emp_name']); ?>">
                            <?php echo htmlspecialchars($employee['emp_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit Transfer</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Item History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="historyModalLabel">Item History</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Item Number: <span id="historyItemNo"></span></h6>

        <div class="mb-4">
          <h6 class="fw-bold">Purchase Requisition (PRS)</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>PRS Code</th>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Supplier</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Unit</th>
              </tr>
            </thead>
            <tbody id="prsHistoryBody"></tbody>
          </table>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold">Receiving Report (RR)</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>RR No</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Date Received</th>
              </tr>
            </thead>
            <tbody id="rrHistoryBody"></tbody>
          </table>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold">Equipment Tagging</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>EQ No.</th>
                <th>Property Code</th>
                <th>Staff</th>
                <th>Tag Date</th>
                <th>Department</th>
                <th>RR No.</th>
                <th>Location</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody id="eqHistoryBody"></tbody>
          </table>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold">Transfer</h6>
          <table class="table table-bordered table-sm">
            <thead class="table-light">
              <tr>
                <th>Transfer No</th>
                <th>Eq No.</th>
                <th>From</th>
                <th>To</th>
                <th>Date Transferred</th>
              </tr>
            </thead>
            <tbody id="transferHistoryBody"></tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printItemHistory()">Print</button>
      </div>
    </div>
  </div>
</div>






