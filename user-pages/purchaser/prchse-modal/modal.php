<!-- Modal Structure -->
<style>
.readonly-item {
    background-color: #e9ecef !important;
    pointer-events: none;
}

</style>
<div class="modal modal-blur fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                <button type="button" class="btn btn-primary" onclick="printContent()">
                    Print
                </button>
                <button type="button" class="btn btn-success" id="updatePRBtn" style="display: none;">
                    Updates
                </button>
                <button type="button" class="btn btn-info" id="updateRemarksBtn" style="display: none;" onclick="openRemarksModal()">Update Remarks</button>
            </div>
        </div>
    </div>
</div>

<!-- Remarks Modal -->
<div class="modal modal-blur fade" id="purchaseRemarksModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Purchase Remarks</h5>
            </div>
            <div class="modal-body">
                <textarea id="purchaseRemarksInput" class="form-control" rows="5" placeholder="Enter your remark..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitPurchaseRemarks()">Save Remark</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Purchase Requisition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updatePRSForm">
                 <input type="hidden" name="prs_code" value="" /> <!-- Added hidden input for prs_code -->
                <div class="modal-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!--<th>Item Code</th>-->
                                <th>Item Description</th>
                                <th style="width: 100px; padding: 5px;">Quantity</th>
                                <th style="width: 100px; padding: 5px;">Unit Type</th>
                                <th style="width: 200px; padding: 5px;">Supplier</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="prsItems"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">Supplier Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tab Structure inside the Modal -->
                <ul class="nav nav-tabs" id="supplierTab" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tabs-supplier" class="nav-link active" data-bs-toggle="tab">Supplier List</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tabs-settings" class="nav-link" data-bs-toggle="tab">Item Per Supplier</a>    
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane active show" id="tabs-supplier">
                        <table id="suppliersListTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-settings">
                        <table id="itemSuppliersListTable" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Supplier Name</th>
                                    <th>Category</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Insert Structure -->
<div class="modal fade" id="modal-add-supplier" tabindex="-1" aria-labelledby="modalAddSupplierLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddSupplierLabel">Add New Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSupplierForm" class="row g-3">
          <div class="col-md-6">
            <label for="supplierName" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplierName" name="supplier_name" required>
          </div>
          <div class="col-md-6">
            <label for="contactName" class="form-label">Contact Name</label>
            <input type="text" class="form-control" id="contactName" name="contact_name" required>
          </div>
          <div class="col-md-6">
            <label for="contactEmail" class="form-label">Contact Email</label>
            <input type="email" class="form-control" id="contactEmail" name="contact_email" required>
          </div>
          <div class="col-md-6">
            <label for="contactPhone" class="form-label">Contact Phone</label>
            <input type="text" class="form-control" id="contactPhone" name="contact_phone" required>
          </div>
          <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Save Supplier</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Insert Supplier Item Structure -->
<div class="modal fade" id="modal-add-item-supplier" tabindex="-1" aria-labelledby="modalAddItemSupplierLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddItemSupplierLabel">Add Item Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addItemSupplierForm" class="row g-3">
          <div class="col-md-6">
            <label for="itemId" class="form-label">Item</label>
            <select class="form-control" id="itemId" name="item_id" required>
              <option value="" disabled selected>Select Item</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="supplierId" class="form-label">Supplier</label>
            <select class="form-control" id="supplierId" name="supplier_id" required>
              <option value="" disabled selected>Select Supplier</option>
            </select>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Save Item Supplier</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<!-- Update Supplier Modal -->
<div class="modal fade" id="updateSupplierModal" tabindex="-1" aria-labelledby="updateSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateSupplierModalLabel">Update Supplier Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSupplierForm">
                    <input type="hidden" id="updateSupplierId" name="supplier_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="updateSupplierName" class="form-label">Supplier Name</label>
                                <input type="text" class="form-control" id="updateSupplierName" name="supplier_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateContactName" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="updateContactName" name="contact_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateContactEmail" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="updateContactEmail" name="contact_email" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateStatus" class="form-label">Status</label>
                                <select class="form-select" id="updateStatus" name="status" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="updateContactPhone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="updateContactPhone" name="contact_phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="updateAddress" name="address" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="updateSupplierForm" class="btn btn-primary">Update Supplier</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal modal-blur fade" id="modal-success-add" tabindex="-1" role="dialog" aria-hidden="true">
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
                <h3>Supplier Inserted</h3>
                <div class="text-secondary">Operation completed successfully.</div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal modal-blur fade" id="modal-info-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-info"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3>Supplier Updated</h3>
                <div class="text-secondary">Operation completed successfully.</div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal modal-blur fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-info"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <h3>Purchase Requisition Price updated successfully!</h3>
                <div class="text-secondary">Operation completed successfully.</div>
            </div>
        </div>
    </div>
</div>







