<style>
.date-range-container { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.label-from, .label-to { display: none; }
.label-range { display: block; font-size: 1.3rem; color: #FFF; white-space: nowrap; }
#minDatePR, #maxDatePR { margin-bottom: 0px; }
@media (max-width: 576px) { .modal-header { flex-direction: column; align-items: flex-start; }
 .modal-header h5 { margin-bottom: 10px; } 
 .modal-header .row { width: 100%; } 
 .modal-header .form-control { width: 100%; } 
 .label-from, .label-to { display: inline-block; font-size: 1rem; color: #FFF; } 
 .label-range { display: none; } }
</style>

<div class="col-12">
    <div class="card">
        <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <h5 class="modal-title" id="prModalLabel">Purchase Requisitions</h5>
            <div class="row ms-auto align-items-center gx-2 date-range-container">
                <div class="col-md-auto">
                    <label for="minDatePR" class="label-from">From:</label>
                    <input type="date" id="minDatePR" class="form-control" style="font-size: 0.9rem;">
                </div>
                <div class="col-md-auto d-flex align-items-center">
                    <label class="label-range"> ⇄ </label>
                </div>
                <div class="col-md-auto">
                    <label for="maxDatePR" class="label-to">To:</label>
                    <input type="date" id="maxDatePR" class="form-control" style="font-size: 0.9rem;">
                </div>
            </div>
        </div>
        


        <div class="card-body">
        <div class="mb-3">
            <div class="text-center mb-2">
                <strong>Process flow:</strong>
            </div>
            <ul class="process-flow steps-counter my-4" style="font-size: 0.8rem; line-height: 1.2; text-align: left;">
                <li class="process-step" style="--tblr-steps-color: #6c757d;">
                    <strong>🧑‍🏫 Dept. Head</strong><br>
                    <small>Reviews and confirms the request.</small>
                </li>
                <li class="process-step" style="--tblr-steps-color: #007bff;">
                    <strong>👤 Admin</strong><br>
                    <small>Approves the confirmed request.</small>
                </li>
                <li class="process-step" style="--tblr-steps-color: #17a2b8;">
                    <strong>🛒 Purchaser</strong><br>
                    <small>Purchases the approved items.</small>
                </li>
                <li class="process-step" style="--tblr-steps-color: #ffc107;">
                    <strong>📦 Stockroom</strong><br>
                    <small>Receives items and deploys consumables.</small>
                </li>
                <li class="process-step" style="--tblr-steps-color: #28a745;">
                    <strong>🧰 Property Custodian</strong><br>
                    <small>Tags and deploys equipment.</small>
                </li>
            </ul>
        </div>

            <div class="table-responsive">
                <table id="prTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>PRS No.</th>
                            <th>Unit</th>
                            <th>Date Requested</th>
                            <th>STATUS PROGRESS</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="prTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
