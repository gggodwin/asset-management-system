
<div class="modal modal-blur fade" id="eqModal" tabindex="-1" aria-labelledby="eqModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 75%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white" style="display: flex; justify-content: space-between; align-items: center;">
        <h5 class="modal-title" id="eqModalLabel">Equipment-Item Tagging</h5>
          <div class="row ms-auto align-items-center gx-2" style="padding: 10px;">
            <div class="col-md-auto">
              <div style="display: flex; gap: 8px; align-items: center;">
                <label for="minDateEQ" style="font-size: 1rem; margin-bottom: 0; color: #FFF;"></label>
                <input type="date" id="minDateEQ" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
              </div>
            </div>
            <div class="col-md-auto">
              <div style="display: flex; gap: 20px; align-items: center;">
                <label for="maxDateEQ" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
                <input type="date" id="maxDateEQ" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
              </div>
            </div>
          </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="eqTaggingTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Tag No.</th>
                <th>Employee Name</th>
                <th>Location</th>
                <th>Date Tagged</th>
                <th>Expected Life Span</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="eqTaggingTableBody">
              <!-- AJAX-loaded rows go here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="transferModalTable" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 75%; width: auto !important;">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="modal-title" id="transferModalLabel">Equipment Transfer Records</h5>
        <div class="row ms-auto align-items-center gx-2" style="padding: 10px;">
  <div class="col-md-auto">
    <div style="display: flex; gap: 8px; align-items: center;">
      <label for="minDateTransfer" style="font-size: 1rem; margin-bottom: 0; color: #000;"></label>
      <input type="date" id="minDateTransfer" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
    </div>
  </div>
  <div class="col-md-auto">
    <div style="display: flex; gap: 20px; align-items: center;">
      <label for="maxDateTransfer" style="font-size: 1.3rem; margin-bottom: 0; color: #FFF; white-space: nowrap;">&nbsp; ⇄ </label>
      <input type="date" id="maxDateTransfer" class="form-control" style="font-size: 0.9rem; padding: 0.25rem;">
    </div>
  </div>
</div>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id="transferTable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Transfer No.</th>
                <th>Equipment No.</th>
                <th>Old Unit</th>
                <th>New Unit</th>
                <th>Date Transferred</th>
                <th>Received By</th>
              </tr>
            </thead>
            <tbody id="transferTableBody">
              <!-- AJAX loaded data -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>