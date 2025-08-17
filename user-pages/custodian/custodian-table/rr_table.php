<div class="col-12">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="rrTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rr-tab" data-bs-toggle="tab" data-bs-target="#rr" type="button" role="tab" aria-controls="rr" aria-selected="true">Receiving Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="rr-details-tab" data-bs-toggle="tab" data-bs-target="#rr-details" type="button" role="tab" aria-controls="rr-details" aria-selected="false">RR Details</button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="rrTabsContent">
                <!-- Receiving Reports Tab -->
                <div class="tab-pane fade " id="rr" role="tabpanel" aria-labelledby="rr-tab">
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
                                <!-- Dynamic Receiving Report rows will be inserted here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RR Details Tab -->
                <div class="tab-pane fade show active" id="rr-details" role="tabpanel" aria-labelledby="rr-details-tab">
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
                                <!-- Dynamic RR Details rows will be inserted here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
