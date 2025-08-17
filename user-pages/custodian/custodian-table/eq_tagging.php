<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Equipment-Item Tagging</h3>
            <!-- You can uncomment the button below if you want to add functionality to add new records -->
            <!-- <button class="btn btn-ghost-success btn-icon" type="button" data-bs-toggle="modal" data-bs-target="#modal">+ Add Equipment</button> -->
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="eqTaggingTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Form no.</th>
                            <!--<th>Employee ID</th>-->
                            <th>Employee Name</th>
                            <th>Location</th>
                            <th>Date Tagged</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="eqTaggingTableBody">
                        <!-- Dynamic Equipment Tagging rows will be inserted here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
