<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Purchase Requisition Details</h3>
            <!-- Optionally you can add a button to add new records -->
            <!-- <button class="btn btn-ghost-success btn-icon" type="button" data-bs-toggle="modal" data-bs-target="#modal">+ Add Item</button> -->
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="prsDetailsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>PRS Code</th>
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
