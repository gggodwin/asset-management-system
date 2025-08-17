<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title ">Purchase Requisitions</h3>
<!--<a href="#" class="btn btn-ghost-primary btn-icon" id="printButtonPRS" style="margin: -10px; padding: 5px;">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
</a>-->
<?php if ($_SESSION['modid'] != 4): ?>
<button class="btn btn-ghost-success btn-icon" id="approvedPRsButton" type="button"
        data-bs-toggle="modal" data-bs-target="#modal-approved-prs">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checklist">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M9 6l11 0" />
    <path d="M9 12l11 0" />
    <path d="M9 18l11 0" />
    <path d="M5 6l0 .01" />
    <path d="M5 12l0 .01" />
    <path d="M5 18l0 .01" />
  </svg>
</button>
<?php endif; ?>
<div class="row mb-2"> <div class="col-md-auto"> <div class="date-range-container">
                        <label for="minDate">From:</label>
                        <input type="date" id="minDate" class="form-control form-control-sm"> </div>
                </div>
                <div class="col-md-auto"> <div class="date-range-container">
                        <label for="maxDate">To:</label>
                        <input type="date" id="maxDate" class="form-control form-control-sm"> </div>
                </div>
            </div>

        </div>
        <div class="card-body border-bottom py-3">
            <div class="d-flex">
                <div class="text-secondary">
                    Show
                    <div class="mx-2 d-inline-block">
                        <input type="number" id="entriesPerPage" class="form-control form-control-sm" value="10" size="3" aria-label="Entries count">
                    </div>
                    entries
                </div>
                <div class="ms-auto text-secondary">
                    Search:
                    <div class="ms-2 d-inline-block">
                        <input type="text" id="searchInput" class="form-control form-control-sm" aria-label="Search PR">
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="prTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="w-1">No.</th>
                        <th>PR Code</th>
                        <th>Requested By</th>
                        <th>Department</th>
                        <th>Date Requested</th>
                        <th>Approval Status</th>
                        <th>Approved By</th>
                        <th class="w-5"></th>
                    </tr>
                </thead>
                <tbody id="prTableBody">
                    <!-- Dynamic PR rows will be inserted here via AJAX -->
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-secondary">No. of PRs: <span id="totalPRsCount">0</span></p>
            <ul class="pagination m-0 ms-auto" id="paginationControls">
                <li class="page-item disabled" id="prevPage">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 6l-6 6l6 6"/>
                        </svg>
                    </a>
                </li>
                <!-- Dynamic page links will be inserted here -->
            </ul>
        </div>
    </div>
</div>




