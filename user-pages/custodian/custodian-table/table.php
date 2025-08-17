<div class="col-12">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">All Items</h3>
      <div>
        <a href="#" class="btn btn-ghost-primary btn-icon" id="printButtonItems" style="margin: -10px; padding: 5px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
          </svg>
        </a> &nbsp;
        <button type="button" class="btn btn-ghost-info btn-icon " data-bs-toggle="dropdown" aria-expanded="false" style="margin: -10px; padding: 5px;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
          </svg>
        </button>
        <ul class="dropdown-menu">
          <a class="dropdown-item filterAction" href="#itemTable" data-category="All">All Categories</a>
            <?php foreach ($propertyCustodian->getCategories($db) as $category): ?>
                <?php if ($modid == 1 && strtolower($category) === 'consumables') continue; ?>
                <a class="dropdown-item filterAction" href="#itemTable" data-category="<?php echo htmlspecialchars($category); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            <?php endforeach; ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger resetFilter" href="#">Reset Filter</a>
        </ul>
      </div>
    </div>
    <div class="card-body border-bottom py-3">
      <div class="d-flex">
        <div class="text-secondary">
          Show
          <div class="mx-2 d-inline-block">
            <input type="number" id="entriesPerPage" class="form-control form-control-sm" value="10" size="3" aria-label="Items count">
          </div>
          entries
        </div>
        <div class="ms-auto text-secondary">
          Search:
          <div class="ms-2 d-inline-block">
            <input type="text" id="searchInput" class="form-control form-control-sm" aria-label="Search item">
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table id="allItemsTable" class="table card-table table-vcenter text-nowrap display nowrap" style="width:100%">
      <table id="allItemsTable" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="w-1">No.</th>
            <th>Barcode</th>
            <th>Particular</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Units</th>
            <!-- Conditionally render columns based on modid
            <?php if ($_SESSION['modid'] != 1): ?>
              <th>Quantity</th>
              <th>Units</th>
            <?php endif; ?> -->
            <th>Last Updated</th>
            <th class="w-2">Action</th>
          </tr>
        </thead>
        <tbody id="allItemsTableBody">
          <!-- Dynamic item rows will be inserted here -->
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex align-items-center">
      <p class="m-0 text-secondary">No. of items:
          <span id="entriesStart">
              <span id="totalItemsCount">
                  <?php
                      if ($_SESSION['modid'] == 1) {
                          // If modid is 1 (Admin), use the custom method
                          echo $propertyCustodian->getTaggedItemsCount($db);
                      } else {
                          // For other modid values, show the default item count
                          echo $propertyCustodian->getTotalItemsCount($db);
                      }
                  ?>
              </span>
          </span>
      </p>

      <ul class="pagination m-0 ms-auto" id="paginationControls">
        <li class="page-item disabled" id="prevPage">
          <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M15 6l-6 6l6 6" />
            </svg>
          </a>
        </li>
        <!-- Dynamic page links will be inserted here -->
      </ul>
    </div>
  </div>
</div>






