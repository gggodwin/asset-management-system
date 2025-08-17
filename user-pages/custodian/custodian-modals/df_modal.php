<div class="modal fade" id="modal-add-df" tabindex="-1" role="dialog" aria-labelledby="modalAddDFLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 55%; width: auto !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Claim Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dfForm">
                <div class="modal-body">
                    <div class="card-body">
                        <ul class="steps steps-green steps-counter my-4">
                            <li class="step-item active" id="df-step-1-indicator">Claim Forms Details</li>
                            <li class="step-item" id="df-step-2-indicator">DF Items</li>
                        </ul>
                    </div>

                    <div id="df-step-1">
                        <input type="hidden" class="form-control" id="df_no" name="df_no" readonly>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="df_rr_no" class="form-label">Receiving Report No.</label>
                                <select class="form-select" id="df_rr_no" name="df_rr_no" required>
                                    <option value="">Select RR No.</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="staff_id" class="form-label">Staff Name</label>
                                <input type="text" class="form-control" id="staff_id" name="staff_id" value="<?php echo $_SESSION['name']; ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dept_id" class="form-label">Department</label>
                                <select class="form-select" id="dept_id" name="dept_id">
                                    <option value="">Auto-Generated</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="unit_id" class="form-label">Unit</label>
                                <select class="form-select" id="unit_id" name="unit_id">
                                    <option value="">Auto-Generated</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="df_date" class="form-label">DF Date</label>
                                <input type="date" class="form-control" id="df_date" name="df_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="df_reqstby" class="form-label">Requested By</label>
                                <input type="text" class="form-control" id="df_reqstby" name="df_reqstby" placeholder="Auto-Generated" readonly>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" id="nextStepBtn">Next ➡</button>
                        </div>
                    </div>

                    <div id="df-step-2" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dfItemsTable">
                                <thead>
                                    <tr>
                                        <th>IT No.</th>
                                        <th style="width: 300px; padding: 5px;">Particular</th>
                                        <th style="width: 100px; padding: 5px;">Quantity</th>
                                        <th style="width: 100px; padding: 5px;">Unit</th>
                                        <th>Amount</th>
                                        <th>Eq No.</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="dfItemsBody">
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="addDfItemRow" style="display: none;">➕ Add Item</button>
                        </div>

                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" id="prevStepBtn">⬅ Previous</button>
                            <button type="submit" class="btn btn-success">✅ Submit Claim Form</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="viewDFModal" tabindex="-1" role="dialog" aria-hidden="true"
     style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="z-index: 1070;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-title text-primary">Claim Form Details</div>
                <iframe id="DFDetailsFrame" style="width: 100%; height: 600px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printContentDF()">
                    Print
                </button>
                <button type="button" class="btn btn-success" id="updatePRBtn" style="display: none;">
                    Updates
                </button>
            </div>
        </div>
    </div>
</div>

