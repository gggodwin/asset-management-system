<style>

</style>
<div class="col-12 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title text-white">Detailed Expenses</h3>
 

            <div class="row ms-auto align-items-center gx-2">
            <div class="col-md-auto">
            <div style="display: flex; gap: 8px; align-items: center;">
                <label for="minDate" style="font-size: 1rem; margin-bottom: 0; color: #FFF;"></label>
                <input type="date" id="minDate" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
            </div>
            <div class="col-md-auto">
            <div style="display: flex; gap: 20px; align-items: center;">
                <label for="maxDate" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
                <input type="date" id="maxDate" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
            </div>
            </div>
        </div>
        </div>

        <div class="card-body">
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
                    <tbody id="assetRecordsTableBody">
                        <!-- Data will be loaded here via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
