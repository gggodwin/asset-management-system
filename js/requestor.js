function loadRequestorPRS() {
    fetch('rqst-fetch/fetch_prs.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const prsList = data.prs.prs;

        // Destroy existing DataTable
        if ($.fn.DataTable.isDataTable('#prTable')) {
            $('#prTable').DataTable().destroy();
        }

        const prTableBody = document.getElementById('prTableBody');
        prTableBody.innerHTML = '';

        prsList.forEach(pr => {
            // Build visual progress tracker
			let currentStatus = '';
			let statusColor = '#6c757d'; // Default to Dept. Head (Gray)
			let textColor = 'white';     // Default text color
			
			if (!pr.dept_head) {
				currentStatus = 'For Dept. Head Confirmation';
				statusColor = '#6c757d'; // Dept. Head
			} else if (pr.approval_status === 'Rejected' && !pr.approved_by) {
				currentStatus = 'Rejected by Dept. Head';
				statusColor = '#6c757d'; // Rejected
			} else if (pr.dept_head && !pr.approved_by) {
				currentStatus = 'Confirmed by: Department Head';
				statusColor = '#007bff'; // Admin
			} else if (pr.approval_status === 'Rejected' && pr.approved_by) {
				currentStatus = 'Rejected by Admin';
				statusColor = '#17a2b8'; // Rejected
			} else if (pr.approval_status === 'Approved' && pr.has_purchased != 1) {
				currentStatus = 'Approved by Admin – Purchase in Progress';
				statusColor = '#17a2b8'; // Purchaser
			} else if (pr.has_purchased == 1 && pr.receive_status !== 'Fully Received') {
				currentStatus = 'Purchased – Delivery in Progress';
				statusColor = '#ffc107'; // Stockroom (yellow)
				textColor = 'black';     // Override for readability
			} else if (pr.receive_status === 'Fully Received' && pr.deploy_status !== 'Fully Deployed') {
				currentStatus = 'Received – Deployment in Progress';
				statusColor = '#ffc107'; // Stockroom (yellow)
				textColor = 'black'; 
			} else if (pr.deploy_status === 'Partially Deployed') {
				currentStatus = 'Partially Deployed';
				statusColor = '#28a745'; // Property Custodian
			} else if (pr.deploy_status === 'Fully Deployed') {
				currentStatus = 'Fully Deployed';
				statusColor = '#28a745'; // Final step
			}
			
			const progressTracker = `
				<div class="text-center">
					<span style="background-color: ${statusColor}; color: ${textColor}; padding: 4px 12px; border-radius: 30px; font-size: 0.85rem;">
						${currentStatus}
					</span>
				</div>
			`;
			
			
			
		
		
		
		

            let updateButton = `<button class="btn btn-sm btn-info" onclick="openUpdateModal('${pr.prs_code}')">Update</button>`;
            if (pr.dept_head || pr.approved_by) {
                updateButton = `<button class="btn btn-sm btn-info" disabled>Update</button>`;
            }

            prTableBody.innerHTML += `
                <tr>
                    <td class="text-center">${pr.prs_code}</td>
                    <td class="text-center">${pr.unit_name}</td>
                    <td class="text-center">${pr.date_requested}</td>
                    <td class="text-center">${progressTracker}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" onclick="viewPRS('${pr.prs_code}')">View</button>
                        ${updateButton}
                    </td>
                </tr>`;
        });

        $('#prTable').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            order: [[0]],
            columnDefs: [
                { className: 'dt-center', targets: '_all' },
                { targets: -1, orderable: false }
            ],
            dom: `
                <'row mb-2'
                    <'col-12 col-md-6 d-flex justify-content-center justify-content-md-start mb-2 mb-md-0'B>
                    <'col-12 col-md-6 d-flex justify-content-center justify-content-md-end'f>
                >
                <'row'<'col-12'tr>>
                <'row mt-2'
                    <'col-sm-12 col-md-5 d-flex align-items-center'i>
                    <'col-sm-12 col-md-7 d-flex justify-content-center justify-content-md-end'p>
                >
            `,
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Excel',
                    title: 'Purchase Requisitions',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'PDF',
                    title: 'Purchase Requisitions',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search PRS Number..."
            }
        });
    })
    .catch(error => console.error('Error loading PRs:', error));
}


$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
	const min = document.getElementById('minDatePR').value;
	let max = document.getElementById('maxDatePR').value;
	const dateRequested = data[2]; // 5th column, zero-based index

	// If no min and no max, show all
	if (!min && !max) return true;

	const requestDate = new Date(dateRequested);

	// If min is set and max is not, default max to min
	if (min && !max) {
		max = min;
	}

	const minDate = min ? new Date(min) : null;
	const maxDate = max ? new Date(max) : null;

	if ((minDate && requestDate < minDate) || (maxDate && requestDate > maxDate)) {
		return false;
	}

	return true;
});

// Trigger redraw on date change
document.getElementById('minDatePR').addEventListener('change', () => {
	$('#prTable').DataTable().draw();
});

document.getElementById('maxDatePR').addEventListener('change', () => {
	$('#prTable').DataTable().draw();
});


    function viewPRS(prsCode)
    {
    	// Load the details form in the modal
    	const iframe = document.getElementById('prsDetailsFrame');
    	iframe.src = `rqst-modals/prs_invoice.php?prs_code=${prsCode}`;

    	// Show the modal
    	const modalElement = document.getElementById('viewDetailsModal');
    	const modal = new bootstrap.Modal(modalElement);
    	modal.show();
    }

	const openUpdateModal = (prsCode) => {
		fetch(`rqst-fetch/fetch_prs_details.php?prs_code=${prsCode}`, {
			method: 'GET',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
			},
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					// Populate main PR form fields
					document.getElementById('update_prs_code').value = data.prs.prs_code;
					document.getElementById('update_requested_by').value = data.prs.requested_by;
					document.getElementById('update_department').value = data.prs.department;
					document.getElementById('update_unit_id').value = data.prs.unit_id;
					document.getElementById('update_date_requested').value = data.prs.date_requested;
					document.getElementById('update_date_needed').value = data.prs.date_needed;
					document.getElementById('update_remarks').value = data.prs.remarks;
	
					// Clear existing rows in details table
					const detailsTableBody = document.getElementById('updateDetailsTableBody');
					detailsTableBody.innerHTML = '';
	
					// Fetch the latest item list
					fetch('rqst-fetch/fetch_categorized_items.php', {
						method: 'GET',
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
						},
					})
						.then((response) => response.json())
						.then((itemsData) => {
							// Group items by category
							const categorizedItems = {};
							itemsData.forEach((item) => {
								let catLabel = `${item.itcat_name} (${item.itemcatgrp_name})`;
								if (!categorizedItems[catLabel]) {
									categorizedItems[catLabel] = [];
								}
								categorizedItems[catLabel].push({
									barcode: item.barcode,
									label: `${item.particular} - ${item.brand}`,
								});
							});
	
							// Store categorized items globally for handleCategoryChangeUpdate
							window.categorizedItemsUpdate = categorizedItems;
	
							// Populate the rows with the PR details
							data.details.forEach((item) => {
								const row = document.createElement('tr');
								const selectedCategory = Object.keys(categorizedItems).find(
									(cat) => categorizedItems[cat].find((i) => i.label === item.item_description)
								);
	
								let itemField = `<select class="form-control item-category" name="item_category[]" required onchange="handleCategoryChangeUpdate(this)">`;
								itemField += `<option value="" disabled>Select Category First</option>`;
	
								// Populate categories
								Object.keys(categorizedItems).forEach((category) => {
									itemField += `<option value="${category}" ${category === selectedCategory ? 'selected' : ''}>${category}</option>`;
								});
	
								itemField += `</select>`;
	
								row.innerHTML = `
									<td>${detailsTableBody.children.length + 1}</td>
									<input type="hidden" class="form-control item-code" name="item_code[]" value="${item.item_code}" readonly>
									<td class="item-category-container">${itemField}</td>
									<td>
										<div class="item-select-wrapper">
											<select class="form-control item-description" name="item_description[]" required onchange="updateItemCode(this)">
												<option value="" disabled>Select Item</option>
											</select>
										</div>
									</td>
									<td><input type="number" class="form-control" name="quantity[]" value="${item.quantity}" required style="width: 80px;"></td>
									<td>
										<select class="form-control" name="unit_type[]" required>
											<optgroup label="Solid Units">
												<option value="pcs" ${item.unit_type === 'pcs' ? 'selected' : ''}>Pieces (pcs)</option>
												<option value="box" ${item.unit_type === 'box' ? 'selected' : ''}>Box (box)</option>
												<option value="pack" ${item.unit_type === 'pack' ? 'selected' : ''}>Pack (pack)</option>
												<option value="set" ${item.unit_type === 'set' ? 'selected' : ''}>Set (set)</option>
												<option value="roll" ${item.unit_type === 'roll' ? 'selected' : ''}>Roll (roll)</option>
												<option value="kg" ${item.unit_type === 'kg' ? 'selected' : ''}>Kilogram (kg)</option>
												<option value="g" ${item.unit_type === 'g' ? 'selected' : ''}>Gram (g)</option>
											</optgroup>
											<optgroup label="Liquid Units">
												<option value="ml" ${item.unit_type === 'ml' ? 'selected' : ''}>Milliliter (ml)</option>
												<option value="L" ${item.unit_type === 'L' ? 'selected' : ''}>Liter (L)</option>
												<option value="bottle" ${item.unit_type === 'bottle' ? 'selected' : ''}>Bottle (bottle)</option>
												<option value="gallon" ${item.unit_type === 'gallon' ? 'selected' : ''}>Gallon (gallon)</option>
											</optgroup>
										</select>
									</td>
									<td class="w-1">
										<button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>
									</td>
								`;
	
								detailsTableBody.appendChild(row);
	
								const categorySelect = row.querySelector('.item-category');
								handleCategoryChangeUpdate(categorySelect, item.item_description);
							});
	
							// Show the update modal
							new bootstrap.Modal(document.getElementById('updateModal')).show();
						})
						.catch(console.error);
				} else {
					alert('Failed to fetch PR details.');
				}
			})
			.catch(console.error);
	};
	
	function handleCategoryChangeUpdate(select, selectedItemDescription = null) {
		const row = select.closest('tr');
		const itemSelect = row.querySelector('.item-description');
		const itemCodeInput = row.querySelector('.item-code');
	
		// Reset item dropdown
		itemSelect.innerHTML = `<option value="" disabled selected>Select Item</option>`;
	
		const selectedCategory = select.value;
	
		if (window.categorizedItemsUpdate && window.categorizedItemsUpdate[selectedCategory]) {
			window.categorizedItemsUpdate[selectedCategory].forEach((item) => {
				const option = document.createElement('option');
				option.value = item.label;
				option.textContent = item.label;
				option.dataset.code = item.barcode;
	
				if (selectedItemDescription && item.label === selectedItemDescription) {
					option.selected = true;
					itemCodeInput.value = item.barcode;
				}
	
				itemSelect.appendChild(option);
			});
		}
	}
	



    window.toggleManualUpdate = (selectElement) =>
    {
    	const row = selectElement.closest("tr");
    	const itemCodeInput = row.querySelector(".item-code");

    	if (selectElement.value === "manual")
    	{
    		const inputField = document.createElement("input");
    		inputField.type = "text";
    		inputField.className = "form-control manual-item";
    		inputField.name = "item_description[]";
    		inputField.placeholder = "Enter item manually";
    		inputField.required = true;
    		inputField.value = ""; // Empty by default for manual entry

    		// Set item code to 0 for manual entries
    		itemCodeInput.value = "0";

    		// Replace select with input field
    		const container = selectElement.closest(".item-field-container");
    		container.innerHTML = "";
    		container.appendChild(inputField);

    		// Restore dropdown if empty
    		inputField.addEventListener("blur", () =>
    		{
    			if (inputField.value.trim() === "")
    			{
    				restoreDropdown(container);
    			}
    		});

    		// Focus on the input field
    		inputField.focus();
    	}
    	else
    	{
    		updateItemCode(selectElement);
    	}
    };

    // ✅ **Fix: Restore Dropdown When Needed**
    window.restoreDropdown = (container) =>
    {
    	fetch(`rqst-fetch/fetch_items.php`)
    		.then(response => response.json())
    		.then(itemsData =>
    		{
    			const selectField = document.createElement("select");
    			selectField.className = "form-control item-description";
    			selectField.name = "item_description[]";
    			selectField.required = true;
    			selectField.onchange = function ()
    			{
    				toggleManualUpdate(selectField);
    			};

    			selectField.innerHTML = `<option value="" disabled>Select Item</option>
                                     <option value="manual">Manual Entry</option>`;

    			itemsData.forEach(dbItem =>
    			{
    				const option = document.createElement("option");
    				option.value = dbItem.particular;
    				option.dataset.code = dbItem.barcode;
    				option.textContent = `${dbItem.particular} (${dbItem.brand})`;
    				selectField.appendChild(option);
    			});

    			container.innerHTML = "";
    			container.appendChild(selectField);
    		})
    		.catch(console.error);
    };

    // ✅ **Fix: Update Item Code Automatically**
    window.updateItemCode = (selectElement) =>
    {
    	const selectedOption = selectElement.options[selectElement.selectedIndex];
    	const itemCodeInput = selectElement.closest("tr").querySelector(".item-code");

    	if (selectedOption && selectedOption.dataset.code)
    	{
    		itemCodeInput.value = selectedOption.dataset.code;
    	}
    };


    // Function to handle PR form submission
    const handleUpdatePRFormSubmit = (e) =>
    {
    	e.preventDefault();
    	const formData = new FormData(e.target);

    	fetch("rqst-fetch/update_prs.php",
    		{
    			method: "POST",
    			body: formData,
    			headers:
    			{
    				"X-Requested-With": "XMLHttpRequest" // Add the X-Requested-With header
    			}
    		})
    		.then(response => response.json())
    		.then(data =>
    		{
    			if (data.success)
    			{
    				// Set success message
    				document.getElementById("updatePRMessage").innerText = "PRS has been successfully updated.";

    				// Show the success modal
    				let successModal = new bootstrap.Modal(document.getElementById("modal-update-success"));
    				successModal.show();

    				// Close update modal
    				bootstrap.Modal.getInstance(document.getElementById("updateModal")).hide();

    				// Refresh the PR list AFTER the success modal is hidden
    				document.getElementById("modal-update-success").addEventListener('hidden.bs.modal', () =>
    				{
    					//loadRequestorPRS();
    					window.location.reload();
    				},
    				{
    					once: true
    				}); // Ensure the listener runs only once
    			}
    			else
    			{
    				alert("Failed to update PR.");
    			}
    		})
    		.catch(console.error);
    };

    const handleInsertPRSFormSubmit = (event) =>
    {
    	event.preventDefault(); // Prevent default form submission

    	let formData = new FormData(event.target); // Capture form data

    	fetch("rqst-fetch/insert_prs.php",
    		{
    			method: "POST",
    			body: formData,
    			headers:
    			{
    				"X-Requested-With": "XMLHttpRequest" // Add the X-Requested-With header
    			}
    		})
    		.then(response => response.json()) // Convert response to JSON
    		.then(data =>
    		{
    			if (data.success)
    			{
    				// Show success modal
    				let successModal = new bootstrap.Modal(document.getElementById("modal-pr-success"));
    				successModal.show();

    				// Reset form fields
    				event.target.reset();

    				// Increment PR code (+1)
    				let prCodeField = document.getElementById("prs_code");
    				if (prCodeField)
    				{
    					let currentPrCode = parseInt(prCodeField.value, 10) || 0;
    					prCodeField.value = currentPrCode + 1;
    				}

    				// Reset back to step 1
    				currentStep = 1;
    				showStep(currentStep); // Ensure this function properly displays step 1

    				// Reset table rows (Keep only the first row)
    				let tableBody = document.querySelector("#purchaseRequisitionsDetailsTableBody");
    				if (tableBody)
    				{
    					let firstRow = tableBody.querySelector("tr:first-child");
    					tableBody.innerHTML = ""; // Clear all rows
    					if (firstRow) tableBody.appendChild(firstRow); // Re-add first row only
    				}

    				// Hide the PR modal if needed
    				let addPrsModal = document.getElementById("modal-add-prs");
    				if (addPrsModal)
    				{
    					let modalInstance = bootstrap.Modal.getInstance(addPrsModal);
    					modalInstance.hide();
    				}

    				// Refresh data
    				loadRequestorPRS();

    				// Reload page when modal is closed
    				successModal._element.addEventListener('hidden.bs.modal', function ()
    				{
    					window.location.reload();
    				});
    			}
    			else
    			{
    				alert("Error: " + data.message);
    			}
    		})
    		.catch(error =>
    		{
    			console.error("Error:", error);
    			alert("An unexpected error occurred.");
    		});
    };

	function setupCardFilters() {
		const titleElement = document.getElementById("prModalLabel");
	
		// Card elements
		const cards = {
			pending: document.getElementById("pendingPRsCard"),
			approved: document.getElementById("approvedPRsCard"),
			total: document.getElementById("totalPRsCard")
		};
	
		// Utility to reset all card borders
		function resetCardBorders() {
			Object.values(cards).forEach(card => {
				card.style.border = "none";
			});
		}
	
		// Click handlers
		cards.pending.addEventListener("click", function () {
			filterTableByStatus("For Dept. Head Confirmation|Confirmed by: Department Head");
			titleElement.textContent = "For Administrator Approval";
			resetCardBorders();
			this.style.border = "2px solid #007bff";
		});
		
	
		cards.approved.addEventListener("click", function () {
			// Combine all statuses into a regex OR pattern using |
			const statuses = [
				"Approved by Admin – Purchase in Progress",
				"Purchased – Delivery in Progress",
				"Received – Deployment in Progress",
				"Partially Deployed",
				"Fully Deployed"
			].join("|");
		
			filterTableByStatus(statuses);
			titleElement.textContent = "Approved Purchase Requisitions";
			resetCardBorders();
			this.style.border = "2px solid #007bff";
		});
		
	
		cards.total.addEventListener("click", function () {
			filterTableByStatus('');
			titleElement.textContent = "All Purchase Requisitions";
			resetCardBorders();
			this.style.border = "2px solid #007bff";
		});
	}
	

	function filterTableByStatus(status) {
		const table = $('#prTable').DataTable();
		table.column(3).search(status, true, false).draw();  // true for regex search
	}


    // Call the function when DOM content is loaded
    document.addEventListener("DOMContentLoaded", function ()
    {
    	loadRequestorPRS(); // Load the PRs data

    	setupCardFilters(); // Set up the card filters

    	// Add the event listener for the PR form submit (Insert)
    	const prsForm = document.getElementById("prsForm");
    	if (prsForm)
    	{
    		prsForm.addEventListener("submit", handleInsertPRSFormSubmit);
    	}

    	// Add the event listener for the PR form submit (Update)
    	const updatePRForm = document.getElementById("update_prs_form");
    	if (updatePRForm)
    	{
    		updatePRForm.addEventListener("submit", handleUpdatePRFormSubmit);
    	}
    });




//Stats
function fetchPRStats()
{
	fetch("rqst-fetch/fetch_prs_stats.php")
		.then(response => response.json())
		.then(stats =>
		{
			document.getElementById("totalPRsCount1").innerText = stats.total_prs;
			document.getElementById("pendingPRsCount").innerText = stats.pending_prs ?? 0;
			document.getElementById("approvedPRsCount").innerText = stats.approved_prs ?? 0;
			document.getElementById("rejectedPRsCount").innerText = stats.rejected_prs ?? 0;
		})
		.catch(error => console.error("Error fetching PR stats:", error));
}



// Modals
let currentStep = 1;
const totalSteps = 3;

// Show the current step in the multi-step form
function showStep(step)
{
	document.querySelectorAll(".step-content").forEach(el => el.classList.add("d-none"));
	document.querySelector(`#step-content-${step}`).classList.remove("d-none");

	document.querySelectorAll(".step-item").forEach((el, index) =>
	{
		el.classList.remove("active", "completed");
		if (index + 1 < step)
		{
			el.classList.add("completed");
		}
		else if (index + 1 === step)
		{
			el.classList.add("active");
		}
	});

	document.getElementById("prevBtn").disabled = step === 1;
	document.getElementById("nextBtn").classList.toggle("d-none", step === totalSteps);
	document.getElementById("submitBtn").classList.toggle("d-none", step !== totalSteps);
}

function validateStep(step)
{
	let isValid = true;
	let missingFields = [];

	if (step === 1)
	{
		const department = document.getElementById("department").value.trim();
		const dateNeeded = document.getElementById("date_needed").value.trim();

		if (!department) missingFields.push("Department is required.");
		if (!dateNeeded) missingFields.push("Date Needed is required.");
	}
	else if (step === 2)
	{
		const items = document.querySelectorAll("#purchaseRequisitionsDetailsTableBody tr");

		if (items.length === 0)
		{
			missingFields.push("At least one item must be added.");
		}
		else
		{
			items.forEach((row, index) =>
			{
				const itemDescription = row.querySelector(".item-description")?.value.trim();
				const manualItem = row.querySelector(".manual-item")?.value.trim();
				const quantity = row.querySelector("[name='quantity[]']")?.value.trim();
				const unit = row.querySelector("[name='unit_type[]']")?.value.trim();

				// Check if at least one of the item descriptions (dropdown or manual) is filled
				if ((!itemDescription || itemDescription === "Loading...") && !manualItem)
				{
					missingFields.push(`Row ${index + 1}: Item Description is missing.`);
				}

				// Ensure quantity is a valid positive number
				if (!quantity || isNaN(quantity) || parseFloat(quantity) <= 0)
				{
					missingFields.push(`Row ${index + 1}: Quantity must be a valid positive number.`);
				}

				// Ensure unit type is not empty
				if (!unit)
				{
					missingFields.push(`Row ${index + 1}: Unit Type is required.`);
				}
			});
		}
	}

	if (missingFields.length > 0)
	{
		document.getElementById("missingFieldsMessage").innerHTML =
			`<ul class="mb-0">${missingFields.map(field => `<li>${field}</li>`).join("")}</ul>`;
		new bootstrap.Modal(document.getElementById("modal-missing-fields")).show();
		return false;
	}

	return true;
}



// Navigation functions
function nextStep()
{
	if (validateStep(currentStep))
	{ // Ensure current step is valid before proceeding
		if (currentStep < totalSteps)
		{
			currentStep++;
			showStep(currentStep);
		}
	}
}

function prevStep()
{
	if (currentStep > 1)
	{
		currentStep--;
		showStep(currentStep);
	}
}

// Initialize form on page load
document.addEventListener("DOMContentLoaded", function ()
{
	showStep(currentStep);
	loadItems(); // Fetch items for dropdown
	addRow(); // Ensure at least one row is present

	let dateRequested = document.getElementById("date_requested");
	let dateNeeded = document.getElementById("date_needed");

	function updateMinDate()
	{
		dateNeeded.min = dateRequested.value;
	}

	updateMinDate();
	dateRequested.addEventListener("change", updateMinDate);
});

// Fetch items from fetch_items.php and populate the select dropdown
async function loadItems()
{
	try
	{
		let response = await fetch('rqst-fetch/fetch_items.php', {
			method: 'GET', // or 'POST' if applicable
			headers: {
				'Content-Type': 'application/json',
				'X-Requested-With': 'XMLHttpRequest'
			}
		});

		let items = await response.json();

		let options = `
      <option value="" disabled selected>Select an item</option>
      <option value="manual_entry">Manually Enter Item</option>
    `;

		items.forEach(item =>
		{
			let fullDescription = `${item.particular} - ${item.brand}`;
			options += `<option value="${fullDescription}" data-code="${item.barcode}">${fullDescription}</option>`;
		});

		document.querySelectorAll(".item-description").forEach(select =>
		{
			if (select.innerHTML.includes("Loading..."))
			{
				select.innerHTML = options;
			}
		});
	}
	catch (error)
	{
		console.error("Error fetching items:", error);
	}
}


function addRow() {
    let tbody = document.getElementById("purchaseRequisitionsDetailsTableBody");
    let newRow = document.createElement("tr");
    let rowCount = tbody.children.length + 1;
    newRow.innerHTML = `
        <td>${rowCount}</td>
        <input type="hidden" class="form-control item-code" name="item_code[]" value="00000">
        <td>
            <select class="form-control item-category" required onchange="handleCategoryChange(this)">
                <option value="" selected>Select Category First</option>
            </select>
        </td>
        <td>
            <div class="item-select-wrapper">
                <div class="input-group">
                    <select class="form-control item-description" name="item_description[]" required>
                        <option value="" disabled selected>Select Item</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" onclick="openAddNewItemModal(this)" title="Add New Item" disabled>✚</button>
                </div>
            </div>
        </td>
        <td><input type="number" class="form-control" name="quantity[]" required style="width: 80px;"></td>
        <td>
            <select class="form-control" name="unit_type[]" required>
                <optgroup label="Solid Units">
                    <option value="pcs">Pieces (pcs)</option>
                    <option value="box">Box (box)</option>
                    <option value="pack">Pack (pack)</option>
                    <option value="set">Set (set)</option>
                    <option value="roll">Roll (roll)</option>
                    <option value="kg">Kilogram (kg)</option>
                    <option value="g">Gram (g)</option>
                </optgroup>
                <optgroup label="Liquid Units">
                    <option value="ml">Milliliter (ml)</option>
                    <option value="L">Liter (L)</option>
                    <option value="bottle">Bottle (bottle)</option>
                    <option value="gallon">Gallon (gallon)</option>
                </optgroup>
            </select>
        </td>
        <td class="w-1">
            <button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button>
        </td>
    `;
    tbody.appendChild(newRow);
    loadCategorizedItems(); // preload and populate
}

let categorizedItems = {};

function loadCategorizedItems() {
    fetch('rqst-fetch/fetch_categorized_items.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        categorizedItems = {}; // Reset categorized items

        // Build category map
        data.forEach(item => {
            let catLabel = `${item.itcat_name} (${item.itemcatgrp_name})`;
            let catId = item.category;

            if (!categorizedItems[catId]) {
                categorizedItems[catId] = {
                    label: catLabel,
                    items: []
                };
            }

            categorizedItems[catId].items.push({
                barcode: item.barcode,
                label: `${item.particular} - ${item.brand}`
            });
        });

        // Fill the category dropdown for the last row
        const lastRow = document.querySelector("#purchaseRequisitionsDetailsTableBody tr:last-child");
        const catSelect = lastRow?.querySelector(".item-category");

        if (catSelect) {
            Object.entries(categorizedItems).forEach(([catId, catData]) => {
                let option = document.createElement("option");
                option.value = catId;
                option.textContent = catData.label;
                catSelect.appendChild(option);
            });
        }
    })
    .catch(error => console.error("Error loading items:", error));
}


function handleCategoryChange(select) {
    let row = select.closest("tr");
    let itemSelect = row.querySelector(".item-description");
    let addNewButton = row.querySelector("button[onclick='openAddNewItemModal(this)']");
    let itemCodeInput = row.querySelector(".item-code");

    // Reset item dropdown
    itemSelect.innerHTML = `<option value="" disabled selected>Select Item</option>`;
    itemCodeInput.value = ""; // Clear barcode
    addNewButton.disabled = !select.value; // Enable only if a category is selected

    const selectedCategory = select.value;

    if (categorizedItems[selectedCategory]) {
        categorizedItems[selectedCategory].items.forEach(item => {
            let option = document.createElement('option');
            option.value = item.label; // Store label (e.g., "Mouse - Logitech")
            option.textContent = item.label;
            option.dataset.code = item.barcode; // Store barcode separately
            itemSelect.appendChild(option);
        });

        // Update barcode (item_code) when an item is selected
        itemSelect.onchange = function () {
            let selectedOption = this.options[this.selectedIndex];
            itemCodeInput.value = selectedOption.dataset.code || "";
        };
    } else {
        console.log("No items found for category:", selectedCategory);
    }
}
function removeRow(button) {
    let tbody = document.getElementById("purchaseRequisitionsDetailsTableBody");
    if (tbody.children.length > 1) {
        button.parentElement.parentElement.remove();
    } else {
        alert("At least one row must be present.");
    }
}

// Function to open the "Add New Item" modal
function openAddNewItemModal(buttonElement) {
    const row = buttonElement.closest("tr");
    const categorySelect = row.querySelector(".item-category");
    const selectedCategory = categorySelect.value;
    const modal = document.getElementById('newItemModal');
    const categorySelectModal = modal.querySelector('#newItemCategory');
    const saveButton = modal.querySelector('.btn-primary');

    // Populate category options in the modal if not already done
    if (categorySelectModal.options.length <= 1) {
        Object.entries(categorizedItems).forEach(([catId, catData]) => {
            let option = document.createElement("option");
            option.value = catId; // Use itcat_id as value
            option.textContent = catData.label; // Show full category label
            categorySelectModal.appendChild(option);
        });
    }

    // Set the category in the modal to the one selected in the row
    categorySelectModal.value = selectedCategory;

    // Set the row index as a data attribute for the save button
    saveButton.dataset.rowindex = row.rowIndex;

    // Show the Bootstrap modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

function saveNewItem(modal, button) {
    const particular = modal.querySelector("#newItemParticular").value.trim();
    const brand = modal.querySelector("#newItemBrand").value.trim();
    const category = modal.querySelector("#newItemCategory").value;

    if (!particular || !brand || !category) {
        alert("Please fill in all the item details.");
        return;
    }

    fetch('rqst-fetch/insert_manual_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
			'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ particular, brand, category }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.duplicate) {
			alert(`Item "${particular} - ${brand}" already exists in category: ${data.category_label}`);
			return;
        }

        if (!data.success) {
            alert(`Error adding item: ${data.error || 'Unknown error'}`);
            return;
        }

        const tbody = document.getElementById("purchaseRequisitionsDetailsTableBody");
        const rowIndex = parseInt(button.dataset.rowindex) - 1;
        const row = tbody.rows[rowIndex];

        if (!row) {
            alert("Error: target row not found.");
            return;
        }

        const itemSelect = row.querySelector(".item-description");
        const itemCodeInput = row.querySelector(".item-code");
        const newItemLabel = `${particular} - ${brand}`;

        // Update JS cache
        if (!categorizedItems[category]) {
            categorizedItems[category] = {
                label: "Unknown Category",
                items: []
            };
        }
        categorizedItems[category].items.push({ barcode: data.barcode, label: newItemLabel });

        // Append new option to the select box
        const newOption = document.createElement("option");
        newOption.value = newItemLabel;
        newOption.textContent = newItemLabel;
        newOption.dataset.code = data.barcode;

        itemSelect.appendChild(newOption);
        itemSelect.value = newItemLabel;
        itemCodeInput.value = data.barcode;

        modal.querySelector('button[data-bs-dismiss="modal"]').click();
    })
    .catch(error => {
        console.error("Fetch error:", error);
        alert("Failed to add new item.");
    });
}


function LoadPRSdetails() {
    fetch('../custodian/custodian-fetch/fetch_prs_details.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#prsDetailsTable')) {
            $('#prsDetailsTable').DataTable().destroy();
        }

        const prsDetailsTableBody = document.getElementById('prsDetailsTableBody');
        prsDetailsTableBody.innerHTML = '';

        data.forEach(item => {
            let status = item.status;
            let statusClass = '';
            let statusText = '';
            let textColorClass = '';

            const prsQty = parseFloat(item.quantity || 0);
            const rrQty = parseFloat(item.rr_quantity || 0);

            if (item.rr_status === 'received') {
                if (rrQty === prsQty) {
                    status = '2'; // Completed
                } else {
                    status = '3'; // Incomplete
                }
            }

            if (status === '1') {
                statusClass = 'bg-warning';
                textColorClass = 'text-white';
                statusText = 'Processing';
            } else if (status === '2') {
                statusClass = 'bg-success';
                textColorClass = 'text-white';
                statusText = 'Completed';
            } else if (status === '3') {
                statusClass = 'bg-danger';
                textColorClass = 'text-white';
                const pendingQty = prsQty - rrQty;
                statusText = `Pending: ${pendingQty}`;
            } else {
                statusClass = 'bg-secondary';
                textColorClass = 'text-white';
                statusText = 'Unknown';
            }

            prsDetailsTableBody.innerHTML += `
                <tr>
                    <td>${item.prs_code}</td>
                    <td>${item.item_code}</td>
                    <td>${item.item_description}</td>
                    <td>${item.quantity}</td>
                    <td>${item.supplier}</td>
                    <td>${item.unit_price}</td>
                    <td>${item.total_price}</td>
                    <td><span class="badge ${statusClass} ${textColorClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewPRSDetails('${item.prs_code}')">View</button>
                        <!--<button class="btn btn-sm btn-secondary" onclick="openCancelModal(${item.prsdetails_id})">Cancel</button>-->
                    </td>
                </tr>`;
        });

        $('#prsDetailsTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [8], orderable: false }
            ],
            dom: `
                <'row mb-3'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 d-flex justify-content-end'f>>
                <'row'<'col-sm-12'tr>>
                <'row mt-2'<'col-sm-5 d-flex align-items-center'i><'col-sm-7 d-flex justify-content-end'p>>
            `,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'PDF',
                    title: 'Purchase Requisition Details',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Excel',
                    title: 'Purchase Requisition Details',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Items or Status..."
            }
        });
    })
    .catch(error => {
        console.error('Error fetching PRS details:', error);
    });
}

function viewPRSDetails(prsCode) {
    	// Load the details form in the modal
    	const iframe = document.getElementById('prsDetailsFrame');
    	iframe.src = `rqst-modals/prs_invoice.php?prs_code=${prsCode}`;

    	// Show the modal
    	const modalElement = document.getElementById('viewDetailsModal');
    	const modal = new bootstrap.Modal(modalElement);
    	modal.show();
}


function printContent()
{
	const iframe = document.getElementById('prsDetailsFrame');
	if (iframe)
	{
		iframe.contentWindow.print();
	}
	else
	{
		console.error("Iframe not found");
	}
}



// Auto-refresh every 60 seconds (60000 ms)
setInterval(loadRequestorPRS, 600000);


$(document).ready(function() {
    LoadPRSdetails();
});