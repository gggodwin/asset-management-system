function fetchPurchaseRequisitions() {
    fetch('prchse-fetch/fetch_all_prs.php', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateDataTable(data.data);
        } else {
            console.error('Failed to fetch PRs:', data.message);
        }
    })
    .catch(error => console.error('Error fetching PRs:', error));
}

function populateDataTable(prData) {
    // Destroy existing DataTable
    if ($.fn.DataTable.isDataTable('#prTable')) {
        $('#prTable').DataTable().destroy();
    }

    const prTableBody = document.getElementById('prTableBody');
    prTableBody.innerHTML = '';

    prData.forEach(pr => {
        const rowClass = (
            pr.approval_status !== 'Canceled' &&
            (pr.unit_price === null || pr.total_price === null || pr.supplier === null ||
                pr.unit_price === "" || pr.total_price === "" || pr.supplier === "")
        ) ? 'table-warning' : '';

        // Action button logic
        let actionButton = `
            <button class="btn btn-sm btn-primary" onclick="viewDetails('${pr.prs_code}')">View</button>
        `;

        if (pr.approval_status !== 'Canceled') {
            if (pr.has_status_2 && pr.has_status_2 == 1) {
                actionButton += `
                    <button class="btn btn-sm btn-secondary" disabled title="Cannot cancel because items were already received.">Cancel</button>
                `;
            } else {
                actionButton += `
                    <button class="btn btn-sm btn-secondary" onclick="openCancelModal('${pr.prs_code}')">Cancel</button>
                `;
            }
        } else {
            actionButton += `
                <button class="btn btn-sm btn-info" onclick="redoApproval('${pr.prs_code}')">Redo</button>
            `;
        }

        prTableBody.innerHTML += `
        <tr class="${rowClass}">
            <td>${pr.prs_code}</td>
            <td>${pr.requested_by}</td>
            <td>${pr.department}</td>
            <td>${pr.date_requested}</td>
            <td>${pr.approved_by || '-'}</td>
            <td>${pr.dept_head || '-'}</td>
            <td><span class="badge ${getStatusClass(pr.approval_status)}">${pr.approval_status}</span></td>
            <td>${actionButton}</td>
            <!-- Hidden columns -->
            <td style="display:none;">${pr.unit_price || ''}</td>
            <td style="display:none;">${pr.total_price || ''}</td>
            <td style="display:none;">${pr.supplier || ''}</td>
            <td style="display:none;">${pr.date_needed || ''}</td>
        </tr>`;
    });

    $('#prTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        order: [[3]],
        columnDefs: [
            { targets: [7], orderable: false }, // Action column
            { targets: [8, 9, 10, 11], visible: false } // Hidden filter columns
        ],
        dom: `
            <'row mb-3'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 d-flex justify-content-end'f>>
            <'row'<'col-sm-12'tr>>
            <'row mt-2'<'col-sm-5 d-flex align-items-center'i><'col-sm-7 d-flex justify-content-end'p>>
        `,
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'btn btn-outline-success btn-sm',
                text: 'Excel',
                title: 'PRS',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Export only up to approval_status
                },
                customize: function(xlsx) {
                    const sheet = xlsx.xl.worksheets['sheet1.xml'];
                    const rows = sheet.getElementsByTagName('row');

                    prData.forEach((pr, index) => {
                        if (
                            pr.unit_price === null || pr.total_price === null || pr.supplier === null ||
                            pr.unit_price === "" || pr.total_price === "" || pr.supplier === ""
                        ) {
                            const rowIdx = index + 2;
                            const row = rows[rowIdx];
                            if (row) {
                                const cells = row.getElementsByTagName('c');
                                for (let i = 0; i < cells.length; i++) {
                                    const cell = cells[i];
                                    cell.setAttribute('s', '20');
                                }
                            }
                        }
                    });

                    const stylesXml = xlsx.xl['styles.xml'];
                    const fills = stylesXml.getElementsByTagName('fills')[0];
                    const fillCount = fills.getElementsByTagName('fill').length;

                    const yellowFill = `
                        <fill>
                            <patternFill patternType="solid">
                                <fgColor rgb="FFFFFF00"/>
                                <bgColor indexed="64"/>
                            </patternFill>
                        </fill>
                    `;

                    fills.innerHTML += yellowFill;
                    fills.setAttribute('count', fillCount + 1);
                }
            },
            {
                extend: 'print',
                className: 'btn btn-outline-secondary btn-sm',
                text: 'Print',
                title: 'PRS',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Export only up to approval_status
                },
                customize: function(win) {
                    $(win.document.body).find('table tr').each(function(index, row) {
                        const rowData = prData[index - 1]; // Skip header
                        if (rowData && (
                            rowData.unit_price === null || rowData.total_price === null || rowData.supplier === null ||
                            rowData.unit_price === "" || rowData.total_price === "" || rowData.supplier === ""
                        )) {
                            $(row).css('background-color', '#fff3cd');
                        }
                    });
                }
            }
        ],
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search PRS No..."
        }
    });
}



$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const min = document.getElementById('minDate').value;
    const max = document.getElementById('maxDate').value;
    const dateRequested = data[3]; // Adjust index as needed
    const requestDate = new Date(dateRequested);
    
    if (min && !max) {
        const minDate = new Date(min);
        // Compare only date parts (ignore time)
        return requestDate.toDateString() === minDate.toDateString();
    }

    if (min && max) {
        const minDate = new Date(min);
        const maxDate = new Date(max);
        return requestDate >= minDate && requestDate <= maxDate;
    }

    return true; // No filtering if neither is selected
});



document.getElementById('minDate').addEventListener('change', () => {
    $('#prTable').DataTable().draw();
});

document.getElementById('maxDate').addEventListener('change', () => {
    $('#prTable').DataTable().draw();
});


function redoApproval(prsCode) {
    // Ask for confirmation before proceeding
    if (confirm('Are you sure you want to re-approve this Purchase Requisition?')) {
        $.ajax({
            url: 'prchse-fetch/updateApprovalStatus.php',
            method: 'POST',
            data: {
                prs_code: prsCode,
                approval_status: 'Approved'
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    alert('PRS re-approved successfully');
                    fetchPurchaseRequisitions(); // Refresh table
                } else {
                    alert('Error re-approving PR');
                }
            },
            error: function() {
                alert('Error re-approving PR');
            }
        });
    }
}


function openCancelModal(prsCode) {
    if (confirm('Are you sure you want to cancel this Purchase Requisition?')) {
        $.ajax({
            url: 'prchse-fetch/cancel_pr.php',
            method: 'POST',
            data: {
                prs_code: prsCode
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                alert('Purchase Requisition has been canceled.');
                fetchPurchaseRequisitions(); // Refresh table
            },
            error: function() {
                alert('An error occurred while canceling the PR.');
            }
        });
    }
}



const getStatusClass = status => {
    switch (status) {
        case "Approved":
            return "bg-success text-white"; // Green with white text
        case "Pending":
            return "bg-secondary text-white"; // Gray with white text
        case "Rejected":
            return "bg-danger text-white"; // Red with white text
        case "Canceled":
            return "bg-warning text-white"; // Yellow with dark text
        default:
            return "bg-secondary text-white"; // Default for undefined statuses
    }
};


function viewDetails(prsCode) {
    // Load the PRS details page into the iframe
    const prsDetailsFrame = document.getElementById('prsDetailsFrame');
    prsDetailsFrame.src = `prchse-modal/prs_invoice.php?prs_code=${prsCode}`;

    // Set the PRS Code in the hidden input field of the update form
    const prsCodeInput = document.querySelector('#updatePRSForm input[name="prs_code"]');
    if (prsCodeInput) {
        prsCodeInput.value = prsCode;
    } else {
        console.warn('PRS No. input field not found in the update form.');
    }

    // Show the modal
    const viewDetailsModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
    viewDetailsModal.show();

    // Show the update buttons and set up the click event for updating PR and remarks
    const updatePRBtn = document.getElementById('updatePRBtn');
    const updateRemarksBtn = document.getElementById('updateRemarksBtn');
    
    updatePRBtn.style.display = 'block';
    updatePRBtn.onclick = () => {
        fetchAndPopulatePRDetails(prsCode);
    };

    // Show the button for updating remarks
    updateRemarksBtn.style.display = 'block';
}


// Function to print the content of the iframe
function printContent() {
    const iframe = document.getElementById('prsDetailsFrame');
    if (iframe.contentWindow) {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    } else {
        console.error('Unable to access iframe content for printing.');
    }
}

function setupCardFilters() {
    document.getElementById("pendingPriceUpdatesCard").addEventListener("click", function() {
        filterTableByCustomLogic("missingPrices");
    });

    document.getElementById("totalApprovedPRsCard").addEventListener("click", function() {
        filterTableByCustomLogic("withPrices");
    });


    document.getElementById("upcomingPRDeadlinesCard").addEventListener("click", function() {
        filterTableByCustomLogic("");
    });

    document.getElementById("supplierCountCard").addEventListener("click", function () {
        filterTableByCustomLogic(""); // no filter, just clear any previous filters
    });
}

function filterTableByCustomLogic(filterType) {
    const table = $('#prTable').DataTable();

    // Reset the custom search logic
    $.fn.dataTable.ext.search = [];

    if (filterType === "missingPrices") {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const unitPrice = data[8] ?? "";
            const totalPrice = data[9] ?? "";
            const supplier = data[10] ?? "";
            const approvalStatus = (data[6] ?? "").toLowerCase();

            return (
                (unitPrice.trim() === "" || unitPrice === "0") &&
                (totalPrice.trim() === "" || totalPrice === "0") &&
                supplier.trim() === "" &&
                approvalStatus === "approved"
            );
        });
    } else if (filterType === "withPrices") {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const unitPrice = data[8] ?? "";
            const totalPrice = data[9] ?? "";
            const supplier = data[10] ?? "";

            return (
                unitPrice.trim() !== "" && unitPrice !== "0" &&
                totalPrice.trim() !== "" && totalPrice !== "0" &&
                supplier.trim() !== ""
            );
        });
    } else if (filterType === "rejected") {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const status = data[7] ?? "";
            return status.toLowerCase() === "rejected";
        });
    } else if (filterType === "upcomingDeadline") {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const dateNeededStr = data[6];
            if (!dateNeededStr) return false;

            const dateNeeded = new Date(dateNeededStr);
            const today = new Date();
            const threeDaysLater = new Date();
            threeDaysLater.setDate(today.getDate() + 3);

            return dateNeeded >= today && dateNeeded <= threeDaysLater;
        });
    }

    table.draw();
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.card[data-scroll-target]').forEach(card => {
        card.addEventListener('click', () => {
            const targetSelector = card.getAttribute('data-scroll-target');
            if (targetSelector) {
                const targetElement = document.querySelector(targetSelector);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } else {
                console.warn('No scroll target specified for card:', card);
            }
        });
    });
});



async function updatePurchaseSummary() {
    try {
        const response = await fetch('prchse-fetch/fetch_summary.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        document.getElementById('totalApprovedPRsCount').textContent = data.totalApprovedPRs ?? 0;
        document.getElementById('pendingPriceUpdatesCount').textContent = data.pendingPriceUpdates ?? 0;
        document.getElementById('supplierCountCount').textContent = data.supplierCount ?? 0;
        document.getElementById('upcomingPRDeadlinesCount').textContent = data.upcomingPRDeadlines ?? 0;

    } catch (error) {
        console.error('Error fetching purchase summary:', error);
    }
}


function setupAddSupplierForm() {
    document.getElementById('addSupplierForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('prchse-fetch/insert_supplier.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                updatePurchaseSummary();

                const addModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-add-supplier'));
                addModal.hide();

                this.reset(); // 'this' refers to the form element

                const successModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-success-add'));
                successModal.show();

                // Optionally refresh the supplier list or table
                // refreshSupplierList();
            } else {
                console.error(data.message || 'Failed to add supplier.');
            }

        } catch (error) {
            console.error('Error adding supplier:', error);
        }
    });
}

function setupUpdateSupplierForm() {
    document.getElementById('updateSupplierForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('prchse-fetch/update_supplier.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                const updateModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('updateSupplierModal'));
                updateModal.hide();

                // Assuming 'suppliersListTable' is a DataTable instance (requires jQuery DataTables)
                $('#suppliersListTable').DataTable().ajax.reload();

                const successModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-info-update'));
                successModal.show();

            } else {
                console.error(data.message || 'Failed to update supplier.');
            }

        } catch (error) {
            console.error('Error updating supplier:', error);
        }
    });
}

function setupSupplierCardClick() {
    document.getElementById('supplierCountCard').addEventListener('click', function() {
        // Show the modal
        $('#supplierModal').modal('show');

        // Initialize DataTable only once
        if (!$.fn.DataTable.isDataTable('#suppliersListTable')) {
            console.log('Initializing DataTable...');
            $('#suppliersListTable').DataTable({
                "ajax": "prchse-fetch/fetch_allSuppliers.php",
                "columns": [
                    { "data": "supplier_name" },
                    { "data": "contact_name" },
                    { "data": "contact_email" },
                    { "data": "contact_phone" },
                    { "data": "address" },
                    {
                        "data": "status",
                        "render": function (data, type, row) {
                            let badgeClass = data === 'Active' ? 'bg-success text-light' : 'bg-danger text-light';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary update-supplier-btn"
                                    data-id="${row.supplier_id}"
                                    data-supplier_name="${row.supplier_name}"
                                    data-contact_name="${row.contact_name}"
                                    data-contact_email="${row.contact_email}"
                                    data-contact_phone="${row.contact_phone}"
                                    data-address="${row.address}"
                                    data-status="${row.status}">
                                    ✎ Update
                                </button>
                            `;
                        }
                    }
                ],
                "processing": true,
                "responsive": true,
                "destroy": true,
                "language": {
                    "emptyTable": "No suppliers available"
                },
                "dom": `
                    <'row mb-3'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 d-flex justify-content-end'f>>
                    <'row'<'col-sm-12'tr>>
                    <'row mt-2'<'col-sm-5 d-flex align-items-center'i><'col-sm-7 d-flex justify-content-end'p>>
                `,
                "buttons": [

                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-outline-secondary btn-sm',
                        text: 'Excel',
                        title: 'Suppliers',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-secondary btn-sm',
                        text: 'Print',
                        title: 'Suppliers List',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        customize: function (win) {
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('width', '100%')
                                .css('border-collapse', 'collapse');
                        }
                    }
                ]
            });
        } else {
            console.log('Reloading DataTable...');
            $('#suppliersListTable').DataTable().ajax.reload();
        }
    });
}

function setupUpdateSupplierButtonClick() {
    // Handle Update Button Click (Event Delegation)
    $('#suppliersListTable').on('click', '.update-supplier-btn', function() {
        const supplierId = $(this).data('id');
        $('#updateSupplierModal').modal('show');

        // Populate form fields with existing data
        $('#updateSupplierId').val(supplierId);
        $('#updateSupplierName').val($(this).data('supplier_name'));
        $('#updateContactName').val($(this).data('contact_name'));
        $('#updateContactEmail').val($(this).data('contact_email'));
        $('#updateContactPhone').val($(this).data('contact_phone'));
        $('#updateAddress').val($(this).data('address'));
        $('#updateStatus').val($(this).data('status')); // New status
    });
}

function setupUpdatePRButton() {
    $(document).ready(function () {
        $('#updatePRBtn').on('click', function () {
            const prsCode = $('#prsNo').text().trim(); // Get the PRS Code
            if (prsCode) {
                fetchAndPopulatePRDetails(prsCode);
            }
        });
    });
}

function fetchAndPopulatePRDetails(prsCode) {
    $.ajax({
        url: 'prchse-fetch/fetch_prs_details.php',
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        data: { prs_code: prsCode },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                populateUpdateModal(response.data);
                $('#updateModal').modal('show');
            } else {
                alert('Failed to fetch PR details.');
            }
        },
        error: function () {
            alert('Error fetching PR details.');
        }
    });
}

function populateUpdateModal(data) {
    $('#prsItems').empty(); // Clear existing rows

    data.forEach((item, index) => {
        const status = parseInt(item.status);
        const isCanceled = status === 0;
        const isCompleted = status === 2;
        const isInactive = isCanceled || isCompleted;
        const readonlyAttr = isInactive ? 'readonly' : '';
        const disabledClass = isInactive ? 'readonly-item' : '';
        const cancelBtnText = isCanceled ? 'Canceled' : (isCompleted ? 'Status:' : 'Cancel');
        const cancelBtnClass = isInactive ? '' : 'btn-danger';
        const hideCancelBtn = isCompleted ? 'd-none' : '';
        const redoBtn = isCanceled ? `<button type="button" class="btn btn-sm btn-success redo-item" data-index="${index}">Redo</button>` : '';
        const quantityValue = item.quantity ?? '';
        const unitPriceValue = item.unit_price ?? '';
        const totalPrice = (parseFloat(unitPriceValue) * parseFloat(quantityValue)).toFixed(2);

        $('#prsItems').append(`
            <tr data-item-status="${status}" class="${isInactive ? 'table-secondary' : ''}">
                <input type="hidden" name="item_code[]" value="${item.item_code ?? ''}">
                <input type="hidden" name="item_status[]" class="item-status" value="${status}">

                <td>
                    <input type="text" name="item_description[]" value="${item.item_description ?? ''}" class="form-control ${disabledClass}" readonly>
                </td>
                <td>
                    <input type="number" name="quantity[]" value="${quantityValue}" class="form-control quantity-input ${disabledClass}" ${readonlyAttr}>
                </td>
                <td>
                    <input type="text" name="unit_type[]" value="${item.unit_type ?? ''}" class="form-control ${disabledClass}" readonly>
                </td>
                <td>
                    <select name="supplier[]" class="form-control supplier-select ${disabledClass}" data-item="${item.item_code ?? ''}" required ${readonlyAttr}>
                        <option value="${item.supplier ?? ''}" selected>${item.supplier ?? 'Select Supplier'}</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="unit_price[]" value="${unitPriceValue}" class="form-control unit-price ${disabledClass}" step="0.01" data-quantity="${quantityValue}" required ${readonlyAttr}>
                </td>
                <td>
                    <input type="number" name="total_price[]" value="${totalPrice}" class="form-control total-price" readonly>
                </td>
                <td>
                    <div class="d-flex gap-1 align-items-center">
                        <button type="button" class="btn btn-sm ${cancelBtnClass} cancel-item ${hideCancelBtn}" data-index="${index}" ${isInactive ? 'disabled' : ''}>${cancelBtnText}</button>
                        ${redoBtn}
                        ${isCompleted ? '<span class="badge bg-secondary text-white">Status: Completed</span>' : ''}
                    </div>
                </td>
            </tr>
        `);

        // Load suppliers for the specific item
        loadSuppliers(item.item_code);

        // Event listeners for cancel and redo buttons are now inside populateUpdateModal
        $(`.cancel-item[data-index="${index}"]`).off('click').on('click', function () { // Changed selector
            const row = $(this).closest('tr');
            const itemStatus = parseInt(row.data('item-status'));

            if (itemStatus === 0) {
                alert('This item is already canceled.');
                return;
            } else if (itemStatus === 2) {
                alert('This item is completed and cannot be modified.');
                return;
            }

            if (confirm('Are you sure you want to cancel this item?')) {
                row.data('item-status', 0).addClass('table-secondary');
                row.find('.item-status').val(0);
                row.find('input, select').addClass('readonly-item').attr('readonly', true);
                $(this).text('Canceled').removeClass('btn-danger').prop('disabled', true);
                row.find('.redo-item').removeClass('d-none');
            }
        });

        $(`.redo-item[data-index="${index}"]`).off('click').on('click', function () {  // Changed selector
            const row = $(this).closest('tr');
            row.data('item-status', 1).removeClass('table-secondary');
            row.find('.item-status').val(1);
            row.find('input, select').removeClass('readonly-item').removeAttr('readonly');
            row.find('.cancel-item').text('Cancel').addClass('btn-danger').prop('disabled', false);
            $(this).addClass('d-none');
        });
    });

    // Total price calculation - Moved outside the forEach loop and using event delegation on the table
    $('#prsItems').off('input', '.unit-price, .quantity-input').on('input', '.unit-price, .quantity-input', function () {
        const row = $(this).closest('tr');
        const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
        const totalPrice = (quantity * unitPrice).toFixed(2);
        row.find('.total-price').val(totalPrice);
    });
}

function loadSuppliers(itemCode) {
    $.ajax({
        url: 'prchse-fetch/fetch_suppliers.php',
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        data: { barcode: itemCode }, // Pass the barcode (item code) as a parameter
        dataType: 'json',
        success: function (suppliers) {
            // Find the supplier select box for the specific item
            const supplierSelect = $(`.supplier-select[data-item="${itemCode}"]`);
            supplierSelect.empty();

            if (suppliers.length) {
                suppliers.forEach(supplier => {
                    supplierSelect.append(`
                        <option value="${supplier.supplier_name}">
                            ${supplier.supplier_name}
                        </option>
                    `);
                });
            } else {
                supplierSelect.append('<option value="">No suppliers available</option>');
            }
        }
    });
}

function setupUpdatePRSForm() {
    $('#updatePRSForm').on('submit', function (e) {
        e.preventDefault();

        // Clear any previous hidden status inputs
        $('#updatePRSForm').find('input[name="status[]"]').remove();

        // Create hidden input fields for statuses and append them to the form
        $('#prsItems tr').each(function () {
            const status = $(this).data('item-status'); // Get status from the row data attribute

            if (status !== undefined) {
                // Add hidden input for each item's status
                $(this).closest('form').append('<input type="hidden" name="status[]" value="' + status + '">');
            }
        });

        $.ajax({
            url: 'prchse-fetch/update_prs_details.php',
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('Response:', response);

                if (response.success) {
                    // Show the success modal
                    $('#successModal').modal('show');

                    // Close other modals
                    $('#updateModal').modal('hide');
                    $('#viewDetailsModal').modal('hide');

                    // Refresh the PRS table
                    fetchPurchaseRequisitions();

                    // Automatically close the success modal after 3 seconds
                    setTimeout(function () {
                        $('#successModal').modal('hide');
                    }, 3000);
                } else {
                    alert('Failed to update Purchase Requisition: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
            }
        });
    });
}

function setupAddItemSupplierModal() {
    $('#modal-add-item-supplier').on('shown.bs.modal', function() {
        // Fetch Non-Stock Items
        $.ajax({
            url: 'prchse-fetch/fetch_NS_items.php',
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            success: function(response) {
                console.log('Items Response:', response);

                let items = Array.isArray(response) ? response : [];

                // Group items by category_name
                const groupedItems = {};
                items.forEach(function(item) {
                    const categoryName = item.category_name || "Uncategorized";

                    if (!groupedItems[categoryName]) {
                        groupedItems[categoryName] = [];
                    }
                    groupedItems[categoryName].push(item);
                });

                let itemOptions = '<option value="" disabled selected>Select Item</option>';

                // Create <optgroup> for each category
                for (let category in groupedItems) {
                    itemOptions += `<optgroup label="${category}">`;
                    groupedItems[category].forEach(function(item) {
                        itemOptions += `<option value="${item.id}">${item.particular} (${item.brand}) - ${item.barcode}</option>`;
                    });
                    itemOptions += '</optgroup>';
                }

                $('#itemId').html(itemOptions);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching items:', error);
            }
        });

        // Fetch Active Suppliers
        $.ajax({
            url: 'prchse-fetch/fetch_allSuppliers.php',
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            success: function(response) {
                let suppliers = response.data || [];
                let supplierOptions = '<option value="" disabled selected>Select Supplier</option>';

                let activeSuppliers = suppliers.filter(function(supplier) {
                    return supplier.status === 'Active';
                });

                if (activeSuppliers.length > 0) {
                    activeSuppliers.forEach(function(supplier) {
                        supplierOptions += `<option value="${supplier.supplier_id}">${supplier.supplier_name}</option>`;
                    });
                } else {
                    supplierOptions += '<option disabled>No active suppliers available</option>';
                }

                $('#supplierId').html(supplierOptions);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching suppliers:', error);
            }
        });
    });
}

function setupAddItemSupplierFormSubmit() {
    $('#addItemSupplierForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: 'prchse-fetch/insert_item_supplier.php',
            method: 'POST',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            success: function(response) {
                if (response === 'success') {
                    alert('Item Supplier added successfully!');
                    $('#modal-add-item-supplier').modal('hide');
                    // Optionally refresh the list or table of item suppliers
                } else {
                    alert('Error adding item supplier.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error submitting form:', error);
            }
        });
    });
}

function setupItemSuppliersTable() {
    document.getElementById('supplierCountCard').addEventListener('click', function () {
        $('#supplierModal').modal('show');

        if (!$.fn.DataTable.isDataTable('#itemSuppliersListTable')) {
            $('#itemSuppliersListTable').DataTable({
                ajax: {
                    url: 'prchse-fetch/fetch_item_suppliers.php',
                    dataSrc: function (json) {
                        return Array.isArray(json) ? json : [];
                    }
                },
                columns: [
                    {
                        data: function (row) {
                            return row.item_name + " (" + row.brand + ")";
                        },
                        title: 'Item (Brand)'
                    },
                    { data: 'supplier_name', title: 'Supplier' },
                    { data: 'category', title: 'Category' }
                ],
                processing: true,
                responsive: true,
                destroy: true,
                language: {
                    emptyTable: 'No item suppliers found'
                },
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
                        title: 'Item Suppliers',
                        exportOptions: {
                            columns: ':visible'
                        },
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-secondary btn-sm',
                        text: 'Print',
                        title: 'Item Suppliers',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (win) {
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('width', '100%')
                                .css('border-collapse', 'collapse');
                        }
                    }
                ]
            });
        } else {
            $('#itemSuppliersListTable').DataTable().ajax.reload();
        }
    });
}



function openRemarksModal() {
    const prsCode = document.querySelector('#updatePRSForm input[name="prs_code"]').value;
    if (!prsCode) {
        alert('No PRS Number found!');
        return;
    }

    // Create a prompt for adding remarks or open a modal for adding remarks
    const remarks = prompt(`Enter purchase remarks for PRS: ${prsCode}`);
    
    if (remarks !== null) {
        fetch('prchse-fetch/update_purchase_remarks.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ prs_code: prsCode, purchase_remarks: remarks })
        })
        .then(res => res.json())
        .then(response => {
            alert(response.message || 'Remarks updated.');
            loadRequestorPRS(); // Reload PRS details
        })
        .catch(err => console.error('Error updating remarks:', err));
    }
}

document.getElementById('supplierCountCard').addEventListener('click', function() {
    $('#supplierModal').modal('show');

    let activeTab = $('#supplierModal .nav-tabs .active').attr('id');

    if (activeTab === 'tab-item-suppliers') {
        loadItemSuppliersTable();
    } else if (activeTab === 'tab-supplier-list') {
        loadSuppliersListTable();
    }
});



$(document).ready(function() {
    setupCardFilters();
    fetchPurchaseRequisitions();
    updatePurchaseSummary(); // Call it here
    setupAddSupplierForm(); // Call it here
    setupUpdateSupplierForm(); // Call it here
    setupSupplierCardClick(); // Call the function for the supplier card click
    setupUpdateSupplierButtonClick();
    setupUpdatePRButton();
    setupUpdatePRSForm();
    setupAddItemSupplierModal(); // Call the function for the Add Item Supplier modal
    setupAddItemSupplierFormSubmit();
    setupItemSuppliersTable();
});