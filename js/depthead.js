function loadDeptHeadPRS() {
    fetch('../requestor/rqst-fetch/fetch_prs.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const prsList = data.prs.prs;

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
                // Approval logic flags
                const hasApprovedBy = pr.approved_by !== null && pr.approved_by !== '';
                const hasDeptHead = pr.dept_head !== null && pr.dept_head !== '';
                const isRejected = pr.approval_status === 'Rejected';

                // Valid pricing check
                const hasValidPrices = pr.valid_unit_prices_count > 0 && pr.valid_total_prices_count > 0;

                // Action buttons
                let actionButtons = `
                    <button class="btn btn-sm btn-primary me-1" onclick="viewPRS('${pr.prs_code}')">View</button>
                `;

                // Approve button logic
                if (modid == 4 && !hasApprovedBy && !isRejected) {
                    actionButtons += `<button class="btn btn-sm btn-success me-1" onclick="handleApprovalWithRemark('${pr.prs_code}')">Approve</button>`;
                } else if (modid == 3 && !hasDeptHead && !isRejected) {
                    actionButtons += `<button class="btn btn-sm btn-success me-1" onclick="openUpdateModal('${pr.prs_code}')">Approve</button>`;
                }

                // Re-Approve button logic
                if (
                    (modid == 3 && hasDeptHead && pr.approval_status !== 'Approved' && !isRejected) ||
                    (modid == 4 && hasDeptHead && hasApprovedBy && !isRejected && !hasValidPrices) // 👈 Re-approve only if NOT valid
                ) {
                    actionButtons += `<button class="btn btn-sm btn-warning" onclick="resetDeptHead('${pr.prs_code}')">Re-Approve</button>`;
                }


            prTableBody.innerHTML += `
                <tr>
                    <td class="text-center">${pr.prs_code}</td>
                    <td class="text-center">${pr.unit_name}</td>
                    <td class="text-center">${pr.date_requested}</td>
                    <td class="text-center">${progressTracker}</td>
                    <td class="text-center">${actionButtons}</td>
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


document.getElementById('minDatePR').addEventListener('change', () => {
    $('#prTable').DataTable().draw();
});

document.getElementById('maxDatePR').addEventListener('change', () => {
    $('#prTable').DataTable().draw();
});


function handleApprovalWithRemark(prsCode) {
    const wantsRemark = confirm("Do you want to add an admin remark before approving?");
    if (wantsRemark) {
        openAdminRemarksModal(prsCode, true); // pass true to indicate follow-up approval
    } else {
        openUpdateModal(prsCode);
    }
}

function openAdminRemarksModal(prsCode, approveAfter = false) {
    document.getElementById('remarks_prs_code').value = prsCode;
    document.getElementById('admin_remarks_text').value = '';
    document.getElementById('adminRemarksForm').dataset.approveAfter = approveAfter ? "1" : "0";
    const modal = new bootstrap.Modal(document.getElementById('adminRemarksModal'));
    modal.show();
}

// Handle modal form submission
document.getElementById('adminRemarksForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const prsCode = document.getElementById('remarks_prs_code').value;
    const remarks = document.getElementById('admin_remarks_text').value.trim();
    const approveAfter = this.dataset.approveAfter === "1";

    if (remarks === '') {
        alert('Please enter your remark.');
        return;
    }

    fetch('dpth-fetch/update_admin_remarks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ prs_code: prsCode, admin_remarks: remarks })
    })
    .then(res => res.json())
    .then(response => {
        alert(response.message || 'Remarks updated.');
        bootstrap.Modal.getInstance(document.getElementById('adminRemarksModal')).hide();
        if (approveAfter) {
            openUpdateModal(prsCode); // Continue to approval
        } else {
            loadDeptHeadPRS(); // Just refresh the PR list
        }
    })
    .catch(err => {
        console.error('Error updating remarks:', err);
        alert('Something went wrong while updating remarks.');
    });
});


    function viewPRS(prsCode)
    {
    	// Load the details form in the modal
    	const iframe = document.getElementById('prsDetailsFrame');
    	iframe.src = `../requestor/rqst-modals/prs_invoice.php?prs_code=${prsCode}`;

    	// Show the modal
    	const modalElement = document.getElementById('viewDetailsModal');
    	const modal = new bootstrap.Modal(modalElement);
    	modal.show();
    }

    const openUpdateModal = (prsCode) => {
    document.getElementById("modalPrsCode").innerText = prsCode;
    document.getElementById("confirmApprove").onclick = () => updatePRStatus(prsCode, "approve");
    document.getElementById("confirmReject").onclick = () => updatePRStatus(prsCode, "reject");

    // Show Update PR Modal
    const updatePRModal = new bootstrap.Modal(document.getElementById("updatePRModal"));
    updatePRModal.show();
};

const updatePRStatus = async (prsCode, action) => {
    try {
        const res = await fetch("dpth-fetch/update_pr_status.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded","X-Requested-With": "XMLHttpRequest" },
            body: new URLSearchParams({ prs_code: prsCode, action }),
        });

        const data = await res.json();
        if (data.success) {
            // Set success message
            document.getElementById("successMessage").innerText =
                action === "approve" ? "Purchase Requisition Approved Successfully!" : "Purchase Requisition Rejected Successfully!";

            // Close the Update PR Modal
            const updatePRModal = document.getElementById("updatePRModal");
            const updateModalInstance = bootstrap.Modal.getInstance(updatePRModal);
            if (updateModalInstance) updateModalInstance.hide();

            // Close the View Details Modal if open
            const viewDetailsModal = document.getElementById("viewDetailsModal");
            const viewModalInstance = bootstrap.Modal.getInstance(viewDetailsModal);
            if (viewModalInstance) viewModalInstance.hide();

            // Show the Success Modal
            const successModal = new bootstrap.Modal(document.getElementById("successModal"));
            successModal.show();

            // Refresh PR table after the success modal is completely hidden
            document.getElementById("successModal").addEventListener("hidden.bs.modal", () => {
                loadDeptHeadPRS();
            });

        } else {
            alert("Failed: " + data.message);
        }
    } catch (error) {
        console.error("Error updating PR status:", error);
        alert("Error updating PR status.");
    }
};

function resetDeptHead(prsCode) {
    if (confirm(`Are you sure you want to redo the approval process for PR Code: ${prsCode}`)) {
        fetch('dpth-fetch/reset_dept_head.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest' // Important for identifying AJAX requests
            },
            body: `prs_code=${encodeURIComponent(prsCode)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Ensure the table is reloaded after a successful reset
                loadDeptHeadPRS();
            } else {
                alert(`Error resetting Department Head: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error resetting Department Head:', error);
            alert('An unexpected error occurred while trying to reset the Department Head.');
        });
    }
}

function setupCardFilters() {
    const titleElement = document.getElementById("prModalLabel");

    // Card elements
    const cards = {
        pending: document.getElementById("pendingPRsCard"),
        approved: document.getElementById("approvedPRsCard"),
        //rejected: document.getElementById("rejectedPRsCard"),
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


function filterTableByStatus(status)
{
    const table = $('#prTable').DataTable();
    table.column(3).search(status, true, false).draw();
}


// Call the function when DOM content is loaded
document.addEventListener("DOMContentLoaded", function ()
{
    loadDeptHeadPRS(); // Load the PRs data

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
fetch("../requestor/rqst-fetch/fetch_prs_stats.php")
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
    const prsDetailsFrame = document.getElementById('prsDetailsFrame2');

    // Force iframe reload by clearing and resetting the src
    prsDetailsFrame.src = ''; 
    prsDetailsFrame.src = `../purchaser/prchse-modal/prs_invoice.php?prs_code=${prsCode}`;

    // Show the view modal
    const viewDetailsModal = new bootstrap.Modal(document.getElementById('viewPRSDetailsModal'));
    viewDetailsModal.show();
}

function viewAssetDetails() {
    const assetFrame = document.getElementById('assetDetailsFrame');
  
    // Force reload
    assetFrame.src = '';
    assetFrame.src = '../custodian/custodian-table/asset_table.php';
  
    // Show modal
    const assetModal = new bootstrap.Modal(document.getElementById('assetModal'));
    assetModal.show();
  }
  

function printContent() {
    const iframe = document.getElementById('prsDetailsFrame');
    if (iframe) {
        iframe.contentWindow.print();
    } else {
        console.error("Iframe not found");
    }
}

$(document).ready(function() {
    LoadPRSdetails();
    loadDeptHeadPRS();
});