<style>
	#prTable_wrapper {
		overflow-x: hidden !important;
	}

	#prTable {
		width: 100% !important;
		table-layout: fixed;
	}

	.dataTables_wrapper {
		overflow-x: auto;
	}

	.date-range-container {
		display: flex;
		gap: 10px;
		align-items: center;
	}

	.date-range-container label {
		width: 4em;
		text-align: right;
	}
</style>
<div class="col-12">
	<div class="card">
		<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0" id="purchaseCardTitle">Purchase Requisitions</h5>
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
				<table id="prTable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>PR No.</th>
							<th>Requested By</th>
							<th>Department</th>
							<th>Date Requested</th>
							<th>Dept Head</th>
							<th>Approved By</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="prTableBody">
						<!-- Dynamic PR rows will be inserted here via AJAX -->
					</tbody>
				</table>
			</div>
		</div>
	</div>