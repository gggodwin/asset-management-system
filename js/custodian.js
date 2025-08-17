function handleItemFormSubmit(formId) {
    document.getElementById(formId).addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);
        const isUpdate = formId === "updateItemForm"; // Determine if it's an update form
        const fetchUrl = isUpdate
            ? "custodian-fetch/update_item-form.php"
            : "custodian-fetch/insert_items.php";

        fetch(fetchUrl, {
            method: "POST",
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`${isUpdate ? "Item updated" : "Item added"} successfully:`, data.message);

                // Hide the respective modal
                const modalId = isUpdate ? "modal-update-item" : "modal-add-items";
                const modalElement = document.getElementById(modalId);
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal?.hide();

                // Show success modal
                showSuccessModal(
                    isUpdate ? "Item Updated!" : "Item Added!",
                    data.message || `The item has been ${isUpdate ? "updated" : "added"} successfully.`
                );

                // Reset the form after success
                this.reset();

                // Handle actions based on add or update
                const onSuccess = () => {
                    LoadItems();        // Refresh the table
                    updateSummaryCounts(); // Refresh any summary counters
                };

                if (!isUpdate) {
                    // Wait until success modal is hidden to update UI (optional delay)
                    const successModal = document.getElementById("modal-success");
                    successModal.addEventListener("hidden.bs.modal", function handler() {
                        onSuccess();
                        successModal.removeEventListener("hidden.bs.modal", handler);
                    });
                } else {
                    // Immediately update UI
                    onSuccess();
                }

            } else {
                console.error(`Failed to ${isUpdate ? "update" : "add"} item:`, data.message);
            }
        })
        .catch(error => console.error(`Error ${isUpdate ? "updating" : "adding"} item:`, error));
    });
}

function setupBarcodeGenerationModal() {
    $(document).ready(function () {
        $('#modal-add-items').on('shown.bs.modal', function () {
            $('#categorySelect').val(''); // Reset category
            $('#barcode').val('Generating...');
        });

        $('#categorySelect').on('change', function () {
            const categoryId = $(this).val();

            if (categoryId) {
                $.ajax({
                    url: 'custodian-fetch/generate_barcode.php',
                    method: 'POST',
                    data: { category: categoryId },
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#barcode').val(response.barcode);
                        } else {
                            $('#barcode').val('Error generating barcode');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        $('#barcode').val('Error');
                    }
                });
            }
        });
    });
}

// Call the setup function



// Function to show the success modal
function showSuccessModal(title = "Success!", message = "Operation completed successfully.") {
    document.getElementById("modal-success-title").textContent = title;
    document.getElementById("modal-success-message").textContent = message;
    new bootstrap.Modal(document.getElementById("modal-success")).show();
}


// Function to update summary counts
function updateSummaryCounts() {
    fetch("custodian-fetch/fetch_items.php", {
        method: 'GET', // Optional, 'GET' is the default for fetch
        headers: {
            'X-Requested-With': 'XMLHttpRequest',  // Add this header
        }
    })
        .then(response => response.json())
        .then(summaryData => {
            if (summaryData.success) {
                // Update all summary counts dynamically
                document.getElementById("totalItemsCount").textContent =
                    summaryData.summary.total_items || 0;
                document.getElementById("lowStockItemsCount").textContent =
                    summaryData.summary.low_stock_items || 0;
                document.getElementById("recentlyUpdatedCount").textContent =
                    summaryData.summary.recently_updated_items || 0;
            } else {
                //console.error("Failed to fetch updated summary:", summaryData.message);
            }
        })
        .catch(error => console.error("Error fetching summary data:", error));
}

function setupBarcodeGeneration() {
    document.addEventListener("DOMContentLoaded", function () {
        document.body.addEventListener("click", function (event) {
            if (event.target.closest(".generate-barcode")) {
                const barcode = event.target.closest(".generate-barcode").getAttribute("data-barcode");
                generateBarcode(barcode);
            }
        });
    });
}

function generateBarcode(barcode) {
    if (!barcode) {
        console.error("Barcode is missing.");
        return;
    }

    // Get the canvas element
    const canvas = document.getElementById('barcodeCanvas');

    // Generate the barcode using JsBarcode on the canvas
    JsBarcode(canvas, barcode, {
        format: "CODE128",
        lineColor: "#242f3f",
        width: 4,
        height: 40,
        displayValue: true
    });

    // Show the modal
    const barcodeModal = new bootstrap.Modal(document.getElementById('barcodeModal'));
    barcodeModal.show();

    // Set up download functionality
    document.getElementById('downloadBarcodeBtn').onclick = function () {
        const link = document.createElement('a');
        link.href = canvas.toDataURL("image/png");
        link.download = `barcode_${barcode}.png`;
        link.click();
    };
}

function setupEquipmentTagging() {
    document.addEventListener("DOMContentLoaded", function () {
        fetchEquipmentTagging();  // Fetch data when the page is loaded
    });
}

function fetchEquipmentTagging() {
    fetch('custodian-fetch/fetch_eq_tagging.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateDataTable(data.data);  // Populate table with data
        } else {
            console.error('Failed to fetch equipment tagging data:', data.message);
        }
    })
    .catch(error => console.error('Error fetching equipment tagging data:', error));
}

function populateDataTable(eqData) {
    // Destroy the DataTable if already initialized
    if ($.fn.DataTable.isDataTable('#eqTaggingTable')) {
        $('#eqTaggingTable').DataTable().destroy();
    }

    const eqTaggingTableBody = document.getElementById('eqTaggingTableBody');
    eqTaggingTableBody.innerHTML = '';

    eqData.forEach(eq => {
        const rowClass = eq.eq_loc ? '' : 'table-warning';
        const statusClass = eq.status ? getEqStatusClass(eq.status) : 'bg-secondary text-white';

        // Check if transfer button should be disabled
        const isProcessing = eq.status === 'Processing';
        const transferBtn = isProcessing
            ? `<button class="btn btn-sm btn-warning" disabled>Transfer</button>`
            : `<button class="btn btn-sm btn-warning" onclick="openTransferModal('${eq.eq_no}', '${eq.eq_loc || ''}')">Transfer</button>`;

        eqTaggingTableBody.innerHTML += `
            <tr class="${rowClass}">
                <td>${eq.eq_no}</td>
                <td>${eq.emp_id || '-'}</td>
                <td>${isNaN(eq.eq_loc) ? eq.eq_loc : eq.unit_name}</td>
                <td>${eq.eq_date}</td>
                <td>${eq.expected_end_date || '-'}</td>
                <td class=""><span class="badge ${statusClass}">${eq.status || '-'}</span></td>
                <td>
                    <button class="btn btn-sm btn-primary me-1" onclick="viewDetails('${eq.eq_no}')">View</button>
                    ${transferBtn}
                </td>
            </tr>`;
    });

    // Initialize the DataTable with export options
    $('#eqTaggingTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        order: [[3]], // Order by date tagged (eq_date) - adjusted column index
        columnDefs: [
            { targets: [5], orderable: false } // Disable ordering on the actions column - adjusted column index
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
                title: 'Equipment Tagging',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                customize: function (doc) {
                    const tableBody = doc.content[1].table.body;
                    eqData.forEach((eq, index) => {
                        if (!eq.eq_loc) {
                            const rowIdx = index + 1;
                            tableBody[rowIdx].forEach(cell => {
                                cell.fillColor = '#fff3cd'; // Highlight rows with missing location
                            });
                        }
                    });
                }
            },
            {
                extend: 'print',
                className: 'btn btn-outline-secondary btn-sm',
                text: 'Print',
                title: 'Equipment Tagging',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                customize: function (win) {
                    $(win.document.body).find('table tr').each(function (index, row) {
                        const rowData = eqData[index - 1]; // Adjust for header row
                        if (rowData && !rowData.eq_loc) {
                            $(row).css('background-color', '#fff3cd');
                        }
                    });
                }
            }
        ],
        lengthMenu: [10, 25, 50, 100],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search Form No..."
        }
    });
}

document.getElementById('minDateEQ').addEventListener('change', filterEQData);
document.getElementById('maxDateEQ').addEventListener('change', filterEQData);

function filterEQData() {
  const minDate = document.getElementById('minDateEQ').value;
  const maxDate = document.getElementById('maxDateEQ').value;

  // Helper: Check if a date is within range
  function isWithinRange(dateStr) {
    if (!dateStr) return false;
    const date = new Date(dateStr);
    const min = minDate ? new Date(minDate) : null;
    const max = maxDate ? new Date(maxDate) : null;

    return (!min || date >= min) && (!max || date <= max);
  }

  // Filter Equipment Tagging Table
  document.querySelectorAll('#eqTaggingTable tbody tr').forEach(row => {
    const dateCell = row.cells[3]?.innerText.trim(); // Date Tagged
    row.style.display = isWithinRange(dateCell) ? '' : 'none';
  });
}

function openTransferModal(eq_no, old_unit) {
    // Set values
    $('#transferEqNo').val(eq_no);
    $('#oldUnit').val(old_unit);

    // Set today’s date
    const today = new Date().toISOString().split('T')[0];
    $('#transferDate').val(today);

    // Reset and show modal
    $('#deptUnit').val(''); // Clear selection
    $('#receivedBy').val('');
    const transferModal = new bootstrap.Modal(document.getElementById('transferModal'));
    transferModal.show();
}

$('#transferForm').on('submit', function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
        url: 'custodian-fetch/insert_transfer.php', //  PHP
        method: 'POST',
        data: formData,
        success: function (response) {
            if (response.success) {
                $('#transferModal').modal('hide');
                alert('Transfer successful!');
                fetchEquipmentTagging();
            } else {
                alert('Transfer failed: ' + response.message);
            }
        },
        error: function () {
            alert('An error occurred during transfer.');
        }
    });
});


const getEqStatusClass = status => {
    switch (status) {
        case "Processing":
            return "bg-info text-white";
        case "Deployed":
            return "bg-success text-white";
        case "Rejected":
            return "bg-danger text-white";
        case "Canceled":
            return "bg-warning text-dark";
        default:
            return "bg-secondary text-white";
    }
};

function viewDetails(eq_no) {
    $.ajax({
        url: 'custodian-fetch/fetch_eq_details.php',
        type: 'POST',
        data: { eq_no: eq_no },
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        success: function (response) {
            if (response.success) {
                const eq = response.eq_tagging;
                const eqDetails = response.eq_tagging_details;

                // Get the iframe document
                const iframe = document.getElementById('eqDetailsIframe');
                const iframeDocument = iframe.contentWindow.document;

                // Write HTML structure inside the iframe
                iframeDocument.open();
                iframeDocument.write(`
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Equipment Tagging Details</title>
                        <style>
                            body { margin: 0; padding: 0; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
                            .container { width: 90%; max-width: 1200px; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; box-sizing: border-box; }
                            h2 { text-align: center; color: #333; margin-bottom: 20px; }
                            .header, .footer { display: flex; justify-content: space-between; margin-bottom: 10px; }
                            .header span, .footer span { font-weight: bold; }
                            .table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
                            .table th, .table td { border: 1px solid black; padding: 10px; text-align: center; }
                            .table th { background-color: lightgray; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h2>Equipment - Item Tagging</h2>
                            <div class="header">
                                <div>Employee Name: <span>${eq.emp_id}</span></div>
                                <div>Location: <span>${eq.eq_loc}</span></div>
                            </div>
                            <div class="header">
                                <div>Department: <span>College Department</span></div>
                                <div>Date: <span>${eq.eq_date}</span></div>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Items (Brand - Particular)</th>
                                        <th>Property Code</th>
                                        <th>Quantity</th>
                                        <th>Expected Life Span</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="eqDetailsTableBody">
                                    ${eqDetails.map((detail, index) => `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${detail.item_description}</td>
                                            <td>${detail.pr_code}</td>
                                            <td>${detail.quantity}</td>
                                            <td>
                                                ${detail.expected_life_span} year(s)<br>
                                                <small class="text-muted">Ends: ${detail.expected_end_date}</small>
                                            </td>

                                            <td>${detail.eq_remarks}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </body>
                    </html>
                `);
                iframeDocument.close();

                // Show modal with iframe
                $('#eqDetailsModal').modal('show');
                // Show update button.
                $('#updateBtn').show();
            } else {
                alert('No data found.');
            }
        },
        error: function () {
            alert('Error fetching data.');
        }
    });
}

// Print iframe content
function printIframe() {
    const iframe = document.getElementById('eqDetailsIframe');
    iframe.contentWindow.print();
}


function updateEqDetails() {
    const eq_no = $('#modal-eq-no').text();
    alert('Update functionality for ' + eq_no + ' is being implemented.');
    // Add your update logic here
}


function loadReceivingReports() {
    $.ajax({
        url: 'custodian-fetch/fetch_rr_view.php?groupByRRNo=true',
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (rrData) {
            if ($.fn.DataTable.isDataTable('#rrTable')) {
                $('#rrTable').DataTable().destroy();
            }

            const rrTableBody = document.getElementById('rrTableBody');
            rrTableBody.innerHTML = '';

            rrData.forEach(rr => {
                rrTableBody.innerHTML += `
                    <tr>
                        <td>${rr.rr_no}</td>
                        <td>${rr.received_from}</td>
                        <td>${rr.invoice_no}</td>
                        <td>${rr.invoice_date}</td>
                        <td>${rr.received_by}</td>
                        <td>${rr.department}</td>
                        <td>${rr.unit_name}</td>
                        <td>${rr.date_received}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewRRDetails('${rr.rr_no}')">View</button>
                        </td>
                    </tr>
                `;
            });

            $('#rrTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                order: [[7, 'desc']],
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
                        title: 'Receiving Reports',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-secondary btn-sm',
                        text: 'Print',
                        title: 'Receiving Reports',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search RR Records..."
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching Receiving Reports:', error);
        }
    });
}


function viewRRDetails(rrNo) {
    // Set the source of the iframe to the details page
    const iframe = document.getElementById('RRDetailsFrame');
    iframe.src = `custodian-modals/rr_invoice.php?rr_no=${encodeURIComponent(rrNo)}`;

    // Show the modal (Bootstrap 5)
    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
    modal.show();
}


function loadRRDetails() {
    fetch('custodian-fetch/fetch_rr_view.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(rrData => {
        // Destroy the DataTable if already initialized to prevent multiple instances
        if ($.fn.DataTable.isDataTable('#rrDetailsTable')) {
            $('#rrDetailsTable').DataTable().destroy();
        }

        const rrDetailsTableBody = document.getElementById('rrDetailsTableBody');
        rrDetailsTableBody.innerHTML = '';

        // Populate the table with the fetched RR data
        rrData.forEach(rr => {
            const statusClass = getStatusClass(rr.status);
            let capexBadgeClass = 'badge bg-secondary'; // Default
                if (rr.capex_type === 'CAPEX') {
                    capexBadgeClass = 'badge bg-cyan text-white'; // Green
                } else if (rr.capex_type === 'OPEX') {
                    capexBadgeClass = 'badge bg-primary text-white'; // Yellow
                }
            rrDetailsTableBody.innerHTML += `
                <tr>
                    <td class="text-center">${rr.rr_no}</td>
                    <td class="text-center">${rr.prs_no}</td>
                    <td class="text-center">${rr.prs_date}</td>
                    <td>${rr.item_brand} - ${rr.item_particular}</td>
                    <td class="text-center">${rr.quantity}</td>
                    <td class="text-center">${rr.unit}</td>
                    <td class="text-right">${rr.unit_price}</td>
                    <td class="text-right">${rr.total_price}</td>
                     <td class="text-center"><span class="${capexBadgeClass}">${rr.capex_type}</span></td>
                    <td class="text-center"><span class="badge ${statusClass}">${rr.status}</span></td>
                    <!--<td class="text-center">
                        <button class="btn btn-sm btn-primary" onclick="viewDetails('${rr.rr_no}')">View</button>
                    </td>-->
                </tr>`;
        });

        // Initialize the DataTable with export options and centering
        $('#rrDetailsTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [[0]],  // Order by RR No.
            columnDefs: [
                { className: 'dt-center', targets: [0, 1, 2, 4, 5, 8] }, // Center specific columns
                { className: 'dt-right', targets: [6, 7] }, // Right-align price columns
                { targets: [9], orderable: false }   // Disable ordering on the actions column
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
                    title: 'Receiving Report Details',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    customize: function (doc) {
                        const tableBody = doc.content[1].table.body;
                        rrData.forEach((rr, index) => {
                            const rowIdx = index + 1;
                            if (rr.status !== 'Received') {
                                tableBody[rowIdx].forEach(cell => {
                                    cell.fillColor = '#fff3cd'; // Highlight rows with non-received status
                                });
                            }
                        });
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Print',
                    title: 'Receiving Report Details',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    },
                    customize: function (win) {
                        $(win.document.body).find('table tr').each(function (index, row) {
                            const rowData = rrData[index - 1]; // Adjust for header row
                            if (rowData && rowData.status !== 'Received') {
                                $(row).css('background-color', '#fff3cd');
                            }
                        });
                    }
                }
            ],
            lengthMenu: [10, 25, 50, 100], // Number of rows per page
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search RR Records..."
            }
        });
    })
    .catch(error => console.error('Error fetching RR details:', error));
}

document.getElementById('minDateRR').addEventListener('change', filterRRData);
document.getElementById('maxDateRR').addEventListener('change', filterRRData);

function filterRRData() {
  const minDate = document.getElementById('minDateRR').value;
  const maxDate = document.getElementById('maxDateRR').value;

  // Helper: Check if a date is within range
  function isWithinRange(dateStr) {
    if (!dateStr) return false;
    const date = new Date(dateStr);
    const min = minDate ? new Date(minDate) : null;
    const max = maxDate ? new Date(maxDate) : null;

    return (!min || date >= min) && (!max || date <= max);
  }

  // Filter RR Table
  document.querySelectorAll('#rrTable tbody tr').forEach(row => {
    const dateCell = row.cells[7]?.innerText.trim(); // Date Received
    row.style.display = isWithinRange(dateCell) ? '' : 'none';
  });

  // Filter RR Details Table
  document.querySelectorAll('#rrDetailsTable tbody tr').forEach(row => {
    const dateCell = row.cells[2]?.innerText.trim(); // PRS Date
    row.style.display = isWithinRange(dateCell) ? '' : 'none';
  });
}

const getStatusClass = status => {
    switch (status) {
        case "Received":
            return "bg-warning text-white"; // Green with white text
        case "Deployed":
            return "bg-success text-white"; // Gray with white text
        case "Rejected":
            return "bg-danger text-white"; // Red with white text
        case "Canceled":
            return "bg-secondary text-dark"; // Yellow with dark text
        default:
            return "bg-secondary text-white"; // Default for undefined statuses
    }
};

function LoadPRSdetails() {
    fetch('custodian-fetch/fetch_prs_details.php', {
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
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Print',
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
    const prsDetailsFrame = document.getElementById('prsDetailsFrame');

    // Force iframe reload by clearing and resetting the src
    prsDetailsFrame.src = ''; 
    prsDetailsFrame.src = `../purchaser/prchse-modal/prs_invoice.php?prs_code=${prsCode}`;

    // Show the view modal
    const viewDetailsModal = new bootstrap.Modal(document.getElementById('viewPRSDetailsModal'));
    viewDetailsModal.show();
}

function LoadItems() {
    fetch('custodian-fetch/fetch_items.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Destroy the DataTable if already initialized
        //console.log('Received data:', data);
        if ($.fn.DataTable.isDataTable('#itemsTable')) {
            $('#itemsTable').DataTable().destroy();
        }

        const itemsTableBody = document.getElementById('itemsTableBody');
        itemsTableBody.innerHTML = '';

        // Populate table
        data.items.forEach(item => {
            let actionButtons = '';

            if (modid == 1) {
                actionButtons = `
                    <button class="btn btn-sm btn-secondary" onclick="viewItemHistory('${item.barcode}')">View History</button>
                `;
            } else if (modid == 6) {
                actionButtons = `
                    <button class="btn btn-sm btn-info me-1" onclick="handleEditItem('${item.barcode}')">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="handleDelete('${item.barcode}')">Delete</button>
                `;
            }

            itemsTableBody.innerHTML += `
                <tr>
                    <td>${item.barcode}</td>
                    <td>${item.particular}</td>
                    <td>${item.brand}</td>
                    <td>${item.quantity}</td>
                    <td>${item.units}</td>
                    <td>${item.itcat_name} (${item.itemcatgrp_name})</td>
                    <td>${item.last_updated}</td>
                    <td>${actionButtons}</td>
                </tr>
            `;
        });
        // Initialize DataTable
        $('#itemsTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [[6, 'desc']], // Sort by last_updated
            columnDefs: [
                { targets: [7], orderable: false }
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
                    title: 'Inventory Items',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Print',
                    title: 'Inventory Items',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Items/Categories..."
            }
        });
    })
    .catch(error => {
        console.error('Error fetching inventory items:', error);
    });
}

$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if (settings.nTable.id !== 'itemsTable') return true;
  
    const min = document.getElementById('minDateItems').value;
    const max = document.getElementById('maxDateItems').value;
    const lastUpdated = data[6]; // 7th column (index 6) - Last Updated
  
    if (!min && !max) return true;
  
    const date = new Date(lastUpdated);
    const minDate = min ? new Date(min) : null;
    const maxDate = max ? new Date(max) : null;
  
    if ((minDate && date < minDate) || (maxDate && date > maxDate)) {
      return false;
    }
  
    return true;
  });
  
  document.getElementById('minDateItems').addEventListener('change', () => {
    $('#itemsTable').DataTable().draw();
  });
  document.getElementById('maxDateItems').addEventListener('change', () => {
    $('#itemsTable').DataTable().draw();
  });
  

function handleEditItem(barcode) {
    if (!barcode) {
        console.error("No barcode found.");
        return;
    }

    fetch(`custodian-fetch/fetch_items.php?barcode=${barcode}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.items) && data.items.length > 0) {
                let item = data.items[0];

                document.getElementById("update_barcode").value = item.barcode || "";
                document.getElementById("update_particular").value = item.particular || "";
                document.getElementById("update_brand").value = item.brand || "";
                document.getElementById("update_category").value = item.category || "";
                document.getElementById("update_quantity").value = item.quantity || "";
                document.getElementById("update_units").value = item.units || "";

                let modal = new bootstrap.Modal(document.getElementById("modal-update-item"));
                modal.show();
            } else {
                console.error("Item not found or response structure incorrect:", data);
            }
        })
        .catch(error => console.error("Error fetching item details:", error));
}


// Function to delete an item using barcode
function handleDelete(barcode) {
    // Show confirmation modal
    const deleteModal = new bootstrap.Modal(document.getElementById("modal-delete-confirm"));
    deleteModal.show();

    // Attach delete action when confirmation is clicked
    document.getElementById("confirmDeleteButton").onclick = function () {
        fetch("custodian-fetch/delete_item.php", {
            method: "POST",
            body: JSON.stringify({ barcode: barcode }), // Send barcode to delete
            headers: {
                "Content-Type": "application/json",
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Item deleted successfully:", data.message);
                showSuccessModal("Item Deleted!", data.message || "The item has been deleted successfully.");

                // After deletion, reload items and update summary counts
                LoadItems(); // Refresh items table
                updateSummaryCounts(); // Refresh summary counts
            } else {
                console.error("Failed to delete item:", data.message);
            }
        })
        .catch(error => console.error("Error deleting item:", error));

        // Close the delete confirmation modal after the action
        deleteModal.hide();
    };
}




function openCancelModal(prsdetails_id) {
    if (confirm('Are you sure you want to cancel this item?')) {
        $.ajax({
            url: 'custodian-fetch/cancel_item_pr.php',
            method: 'POST',
            data: { prsdetails_id: prsdetails_id },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert('Item has been cancelled.');
                    LoadPRSdetails(); // Update the table
                } else {
                    alert('An error occurred while canceling the item.');
                }
            },
            error: function() {
                alert('An error occurred while canceling the item.');
            }
        });
    }
}

function initializeFilters() {
    LoadItems();

    // Flag to track whether the "Non-Capex" filter is active or not
    let nonCapexFilterActive = false;
    let untaggedFilterActive = false;
    let taggedFilterActive = false;
    

    // Function to reset filters and redraw both tables
    function resetFilters() {
        const itemsTable = $('#itemsTable').DataTable();
        const rrDetailsTable = $('#rrTable').DataTable();
        itemsTable.search('').columns().search('').draw();
        rrDetailsTable.search('').columns().search('').draw();
    }

    // Function to scroll to the specified table
    function scrollToTable(tableId) {
        const el = document.getElementById(tableId);
        if (el) {
            el.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    }

    // Generic function to apply filters based on card type
    function applyFilter(filterType) {
        const itemsTable = $('#itemsTable').DataTable();
        const rrDetailsTable = $('#rrDetailsTable').DataTable();
        const eqTaggingTable = $('#eqTaggingTable').DataTable();
    
        resetFilters();
    
        if (filterType === 'dynamicFilter') {
            filterType = (modid == 1) ? 'untagged' : 'nonCapex';
        }
    
        switch (filterType) {
            case 'lowStock':
                itemsTable.column(3).search('^0$', true, false).draw();
                scrollToTable('itemsTable');
                break;
    
            case 'allStock':
                itemsTable.column(3).search('').draw();
                scrollToTable('itemsTable');
                break;
    
            case 'tagged':
                if (taggedFilterActive) {
                    eqTaggingTable.search('').columns().search('').draw(); // Clear the filter
                    taggedFilterActive = false;
                } else {
                    eqTaggingTable.column(4).search('Deployed', true, false).draw();
                    taggedFilterActive = true;
                }
                break;
    
            case 'recentlyUpdated':
                const today = new Date().toISOString().split('T')[0];
                itemsTable.column(6).search(`^${today}`, true, false).draw();
                scrollToTable('itemsTable');
                break;
    
            case 'untagged':
                if (untaggedFilterActive) {
                    eqTaggingTable.search('').columns().search('').draw();
                    untaggedFilterActive = false;
                } else {
                    eqTaggingTable.column(4).search('Processing', true, false).draw();
                    untaggedFilterActive = true;
                }
                break;
                
            case 'nonCapex':
                $('#nonCapexModal').modal('show'); // Always show the modal
            
                const capexTypeColumnIndex = 8;
                const rrDetailsTable = $('#rrDetailsTable').DataTable();
            
                // Always apply the OPEX filter (no toggle)
                rrDetailsTable.search('').columns().search('').draw(); // Clear previous filters
                rrDetailsTable.column(capexTypeColumnIndex).search('OPEX', true, false).draw();
                break;
                
        }
    }
    // Event listeners for each card click
    const filterCards = {
        lowStockItemsCard: 'lowStock',
        totalItemsCard: (modid == 1) ? 'tagged' : 'allStock', // dynamically change based on modid
        recentlyUpdatedCard: 'recentlyUpdated',
        NonCapexItemsCard: 'dynamicFilter' // still dynamic
    };

    // Attach event listeners to the cards
    Object.entries(filterCards).forEach(([cardId, filterType]) => {
        const card = document.getElementById(cardId);
        if (card) {
            card.addEventListener("click", () => applyFilter(filterType));
        }
    });
}


function loadDeliveryForms() {
    $.ajax({
        url: 'custodian-fetch/fetch_df_view.php?groupByDFNo=true',
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (dfData) {
            if ($.fn.DataTable.isDataTable('#dfTable')) {
                $('#dfTable').DataTable().destroy();
            }

            const dfTableBody = document.getElementById('dfTableBody');
            dfTableBody.innerHTML = '';

            dfData.forEach(df => {
                dfTableBody.innerHTML += `
                        <tr>
                            <td>${df.df_no}</td>
                            <td>${df.staff_id}</td>
                            <td>${df.dept_name}</td>
                            <td>${df.unit_name}</td>
                            <td>${df.df_date}</td>
                            <td>${df.df_reqstby}</td>
                            <td>${df.rr_no}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="viewDFDetails('${df.df_no}')">View</button>
                            </td>
                        </tr>
                `;
            });

            $('#dfTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                order: [[2, 'desc']],
                columnDefs: [
                    { targets: [6], orderable: false }
                ],
                dom: `
                    <'row mb-3'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 d-flex justify-content-end'f>>
                    <'row'<'col-sm-12'tr>>
                    <'row mt-2'<'col-sm-5 d-flex align-items-center'i><'col-sm-7 d-flex justify-content-end'p>>
                `,
                buttons: [
                    'pdf', 'print'
                ],
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search DF Number..."
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching Delivery Forms:', error);
        }
    });
}


function viewDFDetails(dfNo) {
  const iframe = document.getElementById('DFDetailsFrame'); 
  iframe.src = `custodian-modals/df_invoice.php?df_no=${encodeURIComponent(dfNo)}`;

  // Show the modal (Bootstrap 5)
  const modal = new bootstrap.Modal(document.getElementById('viewDFModal'));
  modal.show();
}

function loadDFDetails() {
    fetch('custodian-fetch/fetch_df_view.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(dfData => {
        if ($.fn.DataTable.isDataTable('#dfDetailsTable')) {
            $('#dfDetailsTable').DataTable().destroy();
        }

        const dfDetailsTableBody = document.getElementById('dfDetailsTableBody');
        dfDetailsTableBody.innerHTML = '';

            dfData.forEach(df => {
                let eqNoTd = '';
                if (sessionModid != 6) {
                    eqNoTd = `<td class="text-center">${df.eq_no ?? '-'}</td>`;
                }

                dfDetailsTableBody.innerHTML += `
                    <tr>
                        <td class="text-center">${df.df_no}</td>
                        <td class="text-center">${df.it_no}</td>
                        <td>${df.particular}</td>
                        <td class="text-center">${df.df_qty}</td>
                        <td class="text-center">${df.df_unit}</td>
                        <td class="text-right">${parseFloat(df.df_amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        ${eqNoTd}
                        
                    </tr>`;
            });

        $('#dfDetailsTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [[0, 'desc']],
            columnDefs: [
                { className: 'dt-center', targets: [0, 1, 2, 3, 5] },
                { className: 'dt-right', targets: [5] }
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
                    title: 'Delivery Form Details',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    title: 'Delivery Form Details',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Delivery Form..."
            }
        });
    })
    .catch(error => console.error('Error fetching DF details:', error));
}

document.getElementById('minDateDF').addEventListener('change', filterDFData);
document.getElementById('maxDateDF').addEventListener('change', filterDFData);

function filterDFData() {
  const minDate = document.getElementById('minDateDF').value;
  const maxDate = document.getElementById('maxDateDF').value;

  // Helper: Check if a date is within range
  function isWithinRange(dateStr) {
    if (!dateStr) return false;
    const date = new Date(dateStr);
    const min = minDate ? new Date(minDate) : null;
    const max = maxDate ? new Date(maxDate) : null;

    return (!min || date >= min) && (!max || date <= max);
  }

  // Filter DF Table
  document.querySelectorAll('#dfTable tbody tr').forEach(row => {
    const dateCell = row.cells[4]?.innerText.trim(); // DF Date
    row.style.display = isWithinRange(dateCell) ? '' : 'none';
  });
}

function viewItemHistory(it_no) {
    document.getElementById('historyItemNo').innerText = it_no;

    fetch(`custodian-fetch/fetch_item_history.php?it_no=${it_no}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert("Failed to fetch history.");
            return;
        }

        const { prs, rr, eq, transfer } = data;

        const fillTable = (tbodyId, rows, cols) => {
            const tbody = document.getElementById(tbodyId);
            tbody.innerHTML = '';
            if (!rows.length) {
                tbody.innerHTML = `<tr><td colspan="${cols}" class="text-center text-muted">No records found.</td></tr>`;
                return;
            }
            rows.forEach(row => {
                const tr = document.createElement('tr');
                Object.values(row).forEach(val => {
                    const td = document.createElement('td');
                    td.innerText = val ?? '-';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        };

        fillTable('prsHistoryBody', prs, 7);
        fillTable('rrHistoryBody', rr, 7);
        fillTable('eqHistoryBody', eq, 6);
        fillTable('transferHistoryBody', transfer, 6);

        $('#historyModal').modal('show');
    })
    .catch(err => {
        console.error("Error loading history:", err);
        alert("Error loading history.");
    });
}



function loadTransferRecords() {
    fetch('custodian-fetch/fetch_transfer.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        //console.log('Transfer data received:', data);

        if ($.fn.DataTable.isDataTable('#transferTable')) {
            $('#transferTable').DataTable().destroy();
        }

        const tableBody = document.getElementById('transferTableBody');
        tableBody.innerHTML = '';

        data.transfers.forEach(transfer => {
            tableBody.innerHTML += `
                <tr>
                    <td>${transfer.trans_id}</td>
                    <td>${transfer.eq_no}</td>
                    <td>${transfer.old_unit || '-'}</td>
                    <td>${transfer.new_unit || '-'}</td>
                    <td>${transfer.transfer_date}</td>
                    <td>${transfer.received_by || '-'}</td>
                    <!--
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewTransferDetails('${transfer.trans_id}')">View</button>
                    </td>-->
                </tr>
            `;
        });

        $('#transferTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            order: [[4, 'desc']], // sort by transfer_date
            columnDefs: [
                { targets: [5], orderable: false }
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
                    title: 'Equipment Transfers',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Print',
                    title: 'Equipment Transfers',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Transfer Number..."
            }
        });
    })
    .catch(error => {
        console.error('Error fetching transfer records:', error);
    });
}

document.getElementById('minDateTransfer').addEventListener('change', filterTransferData);
document.getElementById('maxDateTransfer').addEventListener('change', filterTransferData);

function filterTransferData() {
  const minDate = document.getElementById('minDateTransfer').value;
  const maxDate = document.getElementById('maxDateTransfer').value;

  function isWithinRange(dateStr) {
    if (!dateStr) return false;
    const date = new Date(dateStr);
    const min = minDate ? new Date(minDate) : null;
    const max = maxDate ? new Date(maxDate) : null;
    return (!min || date >= min) && (!max || date <= max);
  }

  // Filter Transfer Table
  document.querySelectorAll('#transferTable tbody tr').forEach(row => {
    const dateCell = row.cells[4]?.innerText.trim(); // Date Transferred
    row.style.display = isWithinRange(dateCell) ? '' : 'none';
  });
}


// Function to add a DF item row
function addDfItemRow(data = {}) {
    const tbody = document.getElementById('dfItemsBody');
    const row = document.createElement('tr');

    const quantity = (modid === 1) ? 1 : (data.quantity || '');

    row.innerHTML = `
        <td><input type="text" name="it_no[]" class="form-control" value="${data.item_code || ''}" readonly></td>
        <td><input type="text" name="particular[]" class="form-control" value="${data.particular ? `${data.particular} (${data.brand || ''})` : ''}" readonly></td>
        <td><input type="number" name="df_qty[]" class="form-control" value="${quantity}" readonly></td>
        <td><input type="text" name="df_unit[]" class="form-control" value="${data.unit || ''}" readonly></td>
        <td><input type="number" step="0.01" name="df_amount[]" class="form-control" value="${data.total_price || ''}" readonly></td>
        <td><input type="text" name="eq_no[]" class="form-control" value="${data.eq_no || 'N/A'}" readonly></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm remove-row">🗑️</button>
        </td>
    `;

    tbody.appendChild(row);
}


// Function for step navigation
function setupStepNavigation() {
    const nextStepBtn = document.getElementById('nextStepBtn');
    const prevStepBtn = document.getElementById('prevStepBtn');
    const dfStep1 = document.getElementById('df-step-1');
    const dfStep2 = document.getElementById('df-step-2');
    const dfStep1Indicator = document.getElementById('df-step-1-indicator');
    const dfStep2Indicator = document.getElementById('df-step-2-indicator');

    nextStepBtn.addEventListener('click', () => {
        dfStep1.style.display = 'none';
        dfStep2.style.display = 'block';
        dfStep1Indicator.classList.remove('active');
        dfStep2Indicator.classList.add('active');
    });

    prevStepBtn.addEventListener('click', () => {
        dfStep2.style.display = 'none';
        dfStep1.style.display = 'block';
        dfStep2Indicator.classList.remove('active');
        dfStep1Indicator.classList.add('active');
    });
}

// Function to setup add row functionality
function setupAddRowButton() {
    const addDfItemRowBtn = document.getElementById('addDfItemRow');
    addDfItemRowBtn.addEventListener('click', () => {
        addDfItemRow(); // blank row
    });
}

// Function to setup remove row functionality
function setupRemoveRow() {
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
}

// Function to fetch RR options
function fetchRROptions() {
    fetch('custodian-fetch/fetch_rr_options.php', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const rrSelect = document.getElementById('df_rr_no');
            rrSelect.innerHTML = '<option value="">Select RR No.</option>';

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(rr => {
                    const option = document.createElement('option');
                    option.value = rr.rr_no;
                    option.textContent = rr.rr_no;
                    rrSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No Receiving Reports available';
                option.disabled = true;
                rrSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error fetching RR options:', error);
            alert('Failed to load Receiving Report options.');
        });
}

function fetchRROptionsEq() {
    fetch('custodian-fetch/fetch_eqrr_options.php', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const rrSelect = document.getElementById('eq_rr_no');
            rrSelect.innerHTML = '<option value="">Select RR No.</option>';

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(rr => {
                    const option = document.createElement('option');
                    option.value = rr.rr_no;
                    option.textContent = rr.rr_no;
                    rrSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No Receiving Reports available';
                option.disabled = true;
                rrSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error fetching RR options:', error);
            alert('Failed to load Receiving Report options.');
        });
}

// Function to fetch RR details on RR selection
function setupRrDetailsFetch() {
    const dfRrNoSelect = document.getElementById('df_rr_no');
    const deptSelect = document.getElementById('dept_id');
    const unitSelect = document.getElementById('unit_id');
    const dfReqstbyInput = document.getElementById('df_reqstby');
    const dfItemsBody = document.getElementById('dfItemsBody');

    dfRrNoSelect.addEventListener('change', function () {
        const rrNo = this.value;
        if (!rrNo) {
            deptSelect.innerHTML = '<option value="">Select Department</option>';
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            dfReqstbyInput.value = '';
            dfItemsBody.innerHTML = '';
            addDfItemRow();
            return;
        }

        fetch(`custodian-fetch/fetch_df_details.php?rr_no=${encodeURIComponent(rrNo)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                deptSelect.innerHTML = `<option value="${data.department_id}">${data.dept_name}</option>`;
                unitSelect.innerHTML = `<option value="${data.unit_id}">${data.unit_name}</option>`;
                dfReqstbyInput.value = data.requested_by || '';
                dfItemsBody.innerHTML = '';

                if (data.items && Array.isArray(data.items)) {
                    data.items.forEach(item => {
                        addDfItemRow(item);
                    });
                } else {
                    addDfItemRow();
                }
            })
            .catch(error => {
                console.error('Error fetching DF details:', error);
                alert('Failed to fetch deployment form details.');
            });
    });
}

// Function to handle form submission
function setupFormSubmission() {
    const dfForm = document.getElementById('dfForm');
    const modalAddDF = document.getElementById('modal-add-df');

    dfForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('custodian-fetch/insert_df.php', { // Ensure this is the correct endpoint for insertion
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status === 'success') {
                const modalInstance = bootstrap.Modal.getInstance(modalAddDF);
                if (modalInstance) {
                    modalInstance.hide();
                }

                this.reset();
                document.getElementById('dfItemsBody').innerHTML = '';
                addDfItemRow();
                fetchEquipmentTagging();

                if (typeof loadDeliveryForms === 'function') loadDeliveryForms();
                if (typeof loadDFDetails === 'function') loadDFDetails();

                alert('Deployment Form successfully submitted!');
            } else {
                alert(data.message || 'Failed to submit Deployment Form.');
            }
        })
        .catch(error => {
            console.error('Submission error:', error);
            alert('An error occurred while submitting the Deployment Form.');
        });
    });
}

function printContentDF() {
            const iframe = document.getElementById('DFDetailsFrame');
            if (iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } else {
                console.error('Unable to access iframe content for printing.');
            }
        }

// Function to handle modal show event
function setupModalShowEvent() {
    const modalAddDF = document.getElementById('modal-add-df');
    if (modalAddDF) {
        modalAddDF.addEventListener('show.bs.modal', () => {
            fetchRROptions();
            document.getElementById('dfForm').reset();
            document.getElementById('df-step-1').style.display = 'block';
            document.getElementById('df-step-2').style.display = 'none';
            document.getElementById('df-step-1-indicator').classList.add('active');
            document.getElementById('df-step-2-indicator').classList.remove('active');
            document.getElementById('dfItemsBody').innerHTML = '';
            addDfItemRow();
        });
    }
}

// Initialize steps for the Receiving Report (RR) modal
function initializeRRSteps() {
    rrCurrentStep = 1; // Initialize step for RR
    showRRStep(rrCurrentStep); // Show the first step (RR Details)

    // RR Step navigation functions
    $('#rr-nextBtn').on('click', navigateNextStepRR);
    $('#rr-prevBtn').on('click', navigatePrevStepRR);
}

// Initialize steps for the Equipment Tagging modal
function initializeEqTaggingSteps() {
    eqTaggingCurrentStep = 1; // Initialize step for Equipment Tagging
    showEqTaggingStep(eqTaggingCurrentStep); // Show the first step (Equipment Details)

    // Equipment Tagging Step navigation functions
    $('#eq-nextBtn').on('click', navigateNextStepEq);
    $('#eq-prevBtn').on('click', navigatePrevStepEq);
}

// RR Step Navigation Functions
let rrCurrentStep = 1;
function showRRStep(step) {
    $('.step-content[id^="rr-step-content-"]').addClass('d-none');
    $('#rr-step-content-' + step).removeClass('d-none');
    $('.step-item[id^="rr-step-"]').removeClass('active');
    $('#rr-step-' + step).addClass('active');
    $('#rr-prevBtn').prop('disabled', step === 1);
    $('#rr-nextBtn').toggleClass('d-none', step === 2);
    $('#rr-submitBtn').toggleClass('d-none', step !== 2);
}

function navigateNextStepRR() {
    if (rrCurrentStep < 2) {
        rrCurrentStep++;
        showRRStep(rrCurrentStep);
    }
}

function navigatePrevStepRR() {
    if (rrCurrentStep > 1) {
        rrCurrentStep--;
        showRRStep(rrCurrentStep);
    }
}

// Equipment Tagging Step Navigation Functions
let eqTaggingCurrentStep = 1;
function showEqTaggingStep(step) {
    $('.step-content[id^="eq-step-content-"]').addClass('d-none');
    $('#eq-step-content-' + step).removeClass('d-none');
    $('.step-item[id^="eq-step-"]').removeClass('active');
    $('#eq-step-' + step).addClass('active');
    $('#eq-prevBtn').prop('disabled', step === 1);
    $('#eq-nextBtn').toggleClass('d-none', step === 2);
    $('#eq-submitBtn').toggleClass('d-none', step !== 2);
}

function navigateNextStepEq() {
    if (eqTaggingCurrentStep < 2) {
        eqTaggingCurrentStep++;
        showEqTaggingStep(eqTaggingCurrentStep);
    }
}

function navigatePrevStepEq() {
    if (eqTaggingCurrentStep > 1) {
        eqTaggingCurrentStep--;
        showEqTaggingStep(eqTaggingCurrentStep);
    }
}


function getLocationCode(location) {
  const map = {
    "College Faculty": "CF",
    "I.T Laboratory": "ITL",
    "Electromechanical Laboratory": "EML",
    "Mechanical Laboratory": "MCL",
    "Automotive Laboratory": "ATL"
  };
  return map[location] || "LOC";
}

function getYearFromDate(dateStr) {
  return new Date(dateStr).getFullYear();
}


function fetchEquipmentItems(row) {
  $.ajax({
    url: 'custodian-fetch/fetch_eq_items.php',
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
    },
    success: function(response) {
      if (response.success) {
        populateItemDropdown(row, response.items);
      } else {
        console.error('Failed to fetch items');
      }
    },
    error: function(xhr, status, error) {
      console.error('Error fetching data', status, error);
    }
  });
}

function populateItemDropdown(row, items) {
  let options = '<option value="" disabled selected>Select an item</option>';
  items.forEach(function(item) {
    options += `<option value="${item.barcode}" data-available-quantity="${item.quantity}">${item.particular} - ${item.brand}</option>`;
  });

  const itemDescriptionSelect = row.find('.item-description');
  itemDescriptionSelect.html(options);

  setupQuantityChangeListener(row);
  setupItemChangeListener(row);
}

function setupQuantityChangeListener(row) {
  row.find('.quantity-input').on('input', function() {
    const selectedItem = $(this).closest('tr').find('.item-description option:selected');
    const availableQuantity = parseInt(selectedItem.data('available-quantity'));
    const enteredQuantity = parseInt($(this).val());

    if (enteredQuantity > availableQuantity) {
      $(this).val(availableQuantity);
    }
  });
}

function setupItemChangeListener(row) {
  row.find('.item-description').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    const barcode = selectedOption.val();
    const availableQuantity = parseInt(selectedOption.data('available-quantity'));
    const location = $('#eq_loc').val();   // Get selected location
    const eqDate = $('#eq_date').val();    // Get selected date
    const year = getYearFromDate(eqDate);
    const locCode = getLocationCode(location);
    const prCode = `${locCode}-${year}-${barcode}`;

    $(this).closest('tr').find('.pr-code').val(prCode);
    $(this).closest('tr').find('.quantity-input')
      .attr('max', availableQuantity)
      .val(1);
  });
}

function addNewEquipmentRow(item = {}, locationCode = '') {
  const itemNo = itemNoCounter++;

  const itemCode = item.item_code || '';
  
  // Just prefix without suffix — no numbers added here
  const formattedPrCode = `00624_${locationCode}${itemCode}`;

  const newRowHtml = `
    <tr>
      <td class="item-no">${itemNo}</td>
      <td>
        <input type="hidden" name="it_no[]" value="${itemCode}">
        <input type="text" class="form-control" name="item_description[]" value="${item.particular || ''} - ${item.brand || ''}" readonly>
      </td>
      <td>
        <input type="text" class="form-control pr-code" name="pr_code[]" value="${formattedPrCode}" readonly required>
      </td>
      <td>
        <input type="number" class="form-control quantity-input" name="quantity[]" value="${item.quantity || ''}" min="1" readonly>
      </td>
      <td>
        <input type="number" class="form-control" name="eq_lifespan[]" value="1" step="0.1" required placeholder="Enter Expected Life Span">
      </td>
      <td>
        <input type="text" class="form-control" name="eq_remarks[]" value="Good condition" required placeholder="Enter remarks">
      </td>
    </tr>`;

  $('#eqTaggingDetailsTableBody').append(newRowHtml);
}




function removeEquipmentRow(button) {
  // Remove the row
  $(button).closest('tr').remove();

  // Update the item numbers after removing the row
  resetItemNumbers();
}

function resetItemNumbers() {
  itemNoCounter = 1; // Reset the counter
  $('#eqTaggingDetailsTableBody tr').each(function() {
    $(this).find('.item-no').text(itemNoCounter++); // Reassign item numbers
  });
}

function submitEquipmentTaggingForm() {
  $('#eqTaggingForm').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
      url: 'custodian-fetch/insert_eq.php',
      method: 'POST',
      data: formData,
      dataType: 'json',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(response) {
        handleFormSubmissionSuccess(response);
      },
      error: function(xhr, status, error) {
        handleFormSubmissionError(error);
      }
    });
  });
}

function handleFormSubmissionSuccess(response) {
  if (response.status === 'success') {
    $('#modal-add-eq-tagging-success-title').text('Success!');
    $('#modal-add-eq-tagging-success-message').text(response.message);
    $('#modal-add-eq-tagging-success').modal('show'); // Show the success modal
    $('#modal-add-eq-tagging').modal('hide');
    $('#eqTaggingForm')[0].reset();
    // Assuming populateDataTable is defined elsewhere to update the main table
    if (typeof populateDataTable === 'function') {
      populateDataTable(response.eqData);
    }
    setupReloadOnSuccessModalClose();
  } else {
    alert('Failed to insert data: ' + response.message);
  }
}

function handleFormSubmissionError(error) {
  console.error('Error inserting data:', error);
  alert('Error inserting data: ' + error);
}

function setupReloadOnSuccessModalClose() {
  $('#modal-add-eq-tagging-success').on('hidden.bs.modal', function() {
    window.location.reload();
  });
}

function fetchLatestBarcode() {
  document.addEventListener("DOMContentLoaded", function() {
    fetch('custodian-fetch/fetch_latest_barcode.php', {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest', // Adding this header to identify the request as an AJAX request
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
         const barcodeInput = document.getElementById('barcode');
         if (barcodeInput) {
          barcodeInput.value = data.latest_barcode;
        }
      } else {
        console.error('Error fetching barcode:', data.message);
        // Optionally handle the error, e.g., display a default value or message.
      }
    })
    .catch(error => {
      console.error('Error fetching barcode:', error);
      // Optionally handle the error, e.g., display a default value or message.
    });
  });
}

function setupEqDetailsFetch() {
  const eqRrNoSelect = document.getElementById('eq_rr_no');
  const empIdSelect = document.getElementById('emp_id');
  const eqLocSelect = document.getElementById('eq_loc');
  const eqDeptInput = document.getElementById('eq_dept'); // <- new department input
  const eqDateInput = document.getElementById('eq_date');
  const eqTaggingDetailsBody = document.getElementById('eqTaggingDetailsTableBody');

  eqRrNoSelect.addEventListener('change', function() {
    const rrNo = this.value;
    if (!rrNo) {
      // Reset fields if no RR No. is selected
      empIdSelect.value = '';
      eqLocSelect.value = 'College Faculty';
      eqDeptInput.value = ''; // <- clear department
      eqDateInput.value = new Date().toISOString().split('T')[0];
      eqTaggingDetailsBody.innerHTML = '';
      itemNoCounter = 1;
      addNewEquipmentRow();
      return;
    }

    fetch(`custodian-fetch/fetch_eq_rr.php?rr_no=${encodeURIComponent(rrNo)}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }

      // Populate header fields
      empIdSelect.value = data.requested_by || '';
      eqLocSelect.value = data.unit_name || '';
      eqDeptInput.value = data.dept_name || ''; // <- set department
      eqDateInput.value = new Date().toISOString().split('T')[0];

      // Populate items
      eqTaggingDetailsBody.innerHTML = '';
      itemNoCounter = 1;
      if (data.items && Array.isArray(data.items)) {
        data.items.forEach(item => {
            // Convert unit_name (e.g., "IT Lab") into code (e.g., "DBCITL")
            const locationCode = convertToLocationCode(data.unit_name);
            addNewEquipmentRow(item, locationCode);
          });
          
      } else {
        addNewEquipmentRow();
      }
    })
    .catch(error => {
      console.error('Error fetching equipment details:', error);
      alert('Failed to fetch equipment details for the selected RR No.');
    });
  });
}

function convertToLocationCode(unitName) {
    const locationMap = {
      'Faculty Office': 'DBCFAC',
      'Info Tech Lab': 'DBCITL',
      'Automotive Lab': 'DBCAUTL',
      'Mechanical Lab': 'DBCMECHL',
      'Electromechanical Lab': 'DBCEML',
      'General Computer Lab': 'DBCGCL'
    };
    return locationMap[unitName.trim()] || 'DBCGEN'; // fallback if not found
  }
  
  

function printItemHistory() {
  const modalContent = document.querySelector('#historyModal .modal-body').innerHTML;
  const printWindow = window.open('', '', 'width=900,height=700');
  printWindow.document.write(`
    <html>
      <head>
        <title>Item History</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
          body { padding: 20px; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ccc; padding: 5px; }
          h6 { margin-top: 20px; }
        </style>
      </head>
      <body>
        <h3>Item History</h3>
        <div>${modalContent}</div>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();
}

let activeStep = 1;
const numSteps = 2;

function setupReceivingReportForm() {
    document.addEventListener('DOMContentLoaded', () => {
        showStep(activeStep);
        if (document.getElementById("rrItemsTableBody").rows.length === 0) {
            addRRRow();
        }
        hideSelectedOptions();
    });

    $(document).ready(function () {
        $.ajax({
            url: '../purchaser/prchse-fetch/fetch_distinct_supp.php',
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (response) {
                const suppliers = JSON.parse(response);
                const select = $('#received_from');
                select.empty();
                select.append('<option value="" disabled selected>-- Select Supplier --</option>');
                suppliers.forEach(supplier => {
                    const option = $('<option></option>')
                        .val(supplier.supplier_id)
                        .text(supplier.supplier_name);
                    select.append(option);
                });
            },
            error: function () {
                alert('Error fetching supplier list.');
            }
        });
    });

    $('#rrForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: 'custodian-fetch/insert_rr.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert('Receiving Report successfully saved!');
                        location.reload();
                    } else {
                        alert('Failed to save RR: ' + res.message);
                    }
                } catch (err) {
                    console.error('Invalid JSON:', response);
                    alert('Unexpected response from server.');
                }
            },
            error: function () {
                alert('Error submitting RR form.');
            }
        });
    });

    document.getElementById('received_from').addEventListener('change', function () {
        const selectedSupplier = this.value;
        console.log("Selected Supplier:", selectedSupplier);
        const tableBody = document.getElementById('rrItemsTableBody');
        tableBody.innerHTML = '';
        addRRRow();
    });
}
// Show the current step in the multi-step form
function showStep(step) {
    document.querySelectorAll(".step-content").forEach(el => el.classList.add("d-none"));
    document.querySelector(`#step-content-${step}`).classList.remove("d-none");

    document.querySelectorAll(".step-item").forEach((el, index) => {
        el.classList.remove("active", "completed");
        if (index + 1 < step) {
            el.classList.add("completed");
        } else if (index + 1 === step) {
            el.classList.add("active");
        }
    });

    document.getElementById("prevBtn").disabled = step === 1;
    document.getElementById("nextBtn").classList.toggle("d-none", step === numSteps);
    document.getElementById("submitBtn").classList.toggle("d-none", step !== numSteps);
}

function validateFormStep(step) {
    let isValid = true;
    let missingFields = [];

    if (step === 1) {
        const receivedFrom = document.getElementById("received_from").value.trim();
        const dateReceived = document.getElementById("date_received").value.trim();
        const invoiceDate = document.getElementById("invoice_date").value.trim();

        if (!receivedFrom) missingFields.push("Received From is required.");
        if (!dateReceived) missingFields.push("Date Received is required.");
        if (!invoiceDate) missingFields.push("Invoice Date is required.");

    } else if (step === 2) {
        const receivedBy = document.getElementById("received_by").value.trim();
        const department = document.getElementById("department").value.trim();

        if (!receivedBy) missingFields.push("Received By is required.");
        if (!department) missingFields.push("Department is required.");

        const items = document.querySelectorAll("#rrItemsTableBody tr");

        if (items.length === 0) {
            missingFields.push("At least one item must be added.");
        } else {
            items.forEach((row, index) => {
                const particularsInput = row.querySelector("[name='particulars[]']");
                const quantityInput = row.querySelector("[name='quantity[]']");
                const unitInput = row.querySelector("[name='unit[]']");
                const unitPriceInput = row.querySelector("[name='unit_price[]']");
                const totalPriceInput = row.querySelector("[name='total_price[]']");

                const particulars = particularsInput ? particularsInput.value.trim() : '';
                const quantity = quantityInput ? quantityInput.value.trim() : '';
                const unit = unitInput ? unitInput.value.trim() : '';
                const unitPrice = unitPriceInput ? unitPriceInput.value.trim() : '';
                const totalPrice = totalPriceInput ? totalPriceInput.value.trim() : '';

                if (!particulars) missingFields.push(`Row ${index + 1}: Particulars are required.`);
                if (!quantity || isNaN(quantity) || parseFloat(quantity) <= 0) missingFields.push(`Row ${index + 1}: Valid Quantity is required.`);
                if (!unit) missingFields.push(`Row ${index + 1}: Unit is required.`);
                if (!unitPrice || isNaN(unitPrice) || parseFloat(unitPrice) <= 0) missingFields.push(`Row ${index + 1}: Valid Unit Price is required.`);
                if (totalPrice === '' || isNaN(totalPrice) || parseFloat(totalPrice) < 0) {
                    missingFields.push(`Row ${index + 1}: Valid Total Price is required.`);
                }
            });
        }
    }

    if (missingFields.length > 0) {
        alert(missingFields.join("\n"));
        return false;
    }

    return true;
}

function nextStepRR() {
    if (validateFormStep(activeStep)) {
        activeStep++;
        showStep(activeStep);
    }
}

function prevStepRR() {
    activeStep--;
    showStep(activeStep);
}

// Function to add a new RR row
function addRRRow() {
    const tableBody = document.getElementById("rrItemsTableBody");
    const rowCount = tableBody.rows.length + 1;

    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${rowCount}</td>
        <td>
            <select name="particulars[]" class="form-control" onchange="autoFillFields(this)">
                <option value="">Select Item</option>
            </select>
        </td>
        <input type="hidden" name="prs_id[]" />
        <td><input type="text" name="prs_no[]" class="form-control" readonly></td>
        <td><input type="date" name="prs_date[]" class="form-control" readonly></td>
        <td>
            <input type="number" name="quantity[]" class="form-control" oninput="calculateTotalPrice(this)">
        </td>
        <td>
            <span class="badge bg-warning text-dark pending-badge" style="font-size: 0.85em;" data-bs-toggle="tooltip" title="Pending items will appear here.">Pending: -</span>
        </td>
        <td><input type="text" name="unit[]" class="form-control" readonly></td>
        <td><input type="number" name="unit_price[]" class="form-control" oninput="calculateTotalPrice(this)" readonly></td>
        <td><input type="number" name="total_price[]" class="form-control" readonly></td>
        <td><button type="button" class="btn btn-danger" onclick="removeRRRow(this)">Remove</button></td>
    `;
    tableBody.appendChild(row);

    // Fetch item options for the newly added row
    fetchItemOptions(row);

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}


// Function to populate item dropdown based on supplier
function fetchItemOptions(row) {
    const selectedSupplierId = document.getElementById('received_from').value;

    if (!selectedSupplierId) {
        console.warn("No supplier selected.");
        return;
    }

    $.ajax({
        url: 'custodian-fetch/fetch_prs_items.php',
        method: 'POST',
        data: { supplier_id: selectedSupplierId },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (response) {
            const data = JSON.parse(response);
            const selectElement = row.querySelector("select[name='particulars[]']");
            selectElement.innerHTML = '';

            // Add placeholder option for selecting an item
            const placeholderOption = document.createElement("option");
            placeholderOption.value = '';
            placeholderOption.textContent = '-- Select Item --';
            placeholderOption.disabled = true;
            placeholderOption.selected = true;
            selectElement.appendChild(placeholderOption);

            // Check if data is returned from the server
            if (data && data.length > 0) {
                const grouped = {};

                // Group the items by PRS code
                data.forEach(item => {
                    if (!grouped[item.prs_code]) {
                        grouped[item.prs_code] = [];
                    }
                    grouped[item.prs_code].push(item);
                });

                // Create optgroup elements for each PRS code
                for (const prsCode in grouped) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = `PRS #${prsCode}`;

                    // Add options for each item under the PRS code
                    grouped[prsCode].forEach(item => {
                        const option = document.createElement('option');
                        const description = item.item_description || item.item_code;
                        option.value = `${item.item_code}|${item.prs_code}`;
                        const receivedQty = item.rr_quantity || 0;
                        option.textContent = `${description} (Original Oty: ${item.quantity})`;
                        optgroup.appendChild(option);
                    });

                    selectElement.appendChild(optgroup);
                }
            } else {
                // Handle the case where no items are returned
                const option = document.createElement("option");
                option.textContent = 'No item was received from this supplier';
                option.disabled = true;
                selectElement.appendChild(option);
            }
            hideSelectedOptions();
        },
        error: function () {
            alert('Error fetching PRS details');
        }
    });
}


// Automatically fill in fields when an item is selected
// Automatically fill in fields when an item is selected
function autoFillFields(selectElement) {
    const row = selectElement.closest('tr');
    const [itemCode, prsCode] = selectElement.value.split('|');

    if (itemCode && prsCode) {
        $.ajax({
            url: 'custodian-fetch/fetch_item_details.php',
            method: 'POST',
            data: { item_code: itemCode, prs_code: prsCode },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (response) {
                let item;
                try {
                    item = JSON.parse(response);
                } catch (e) {
                    alert('Invalid response from server');
                    return;
                }

                if (item.error) {
                    alert(item.error);
                    return;
                }

                const maxQty = parseInt(item.quantity); // from PRS
                const rowQtyInput = row.querySelector("input[name='quantity[]']");
                const pendingBadge = row.querySelector(".pending-badge");

                // Auto-fill fields
                row.querySelector("input[name='prs_no[]']").value = item.prs_code;
                row.querySelector("input[name='prs_date[]']").value = item.date_requested;
                row.querySelector("input[name='unit[]']").value = item.unit_type;
                row.querySelector("input[name='prs_id[]']").value = item.prs_id;
                row.querySelector("input[name='unit_price[]']").value = item.unit_price;

                // Set quantity max limit
                rowQtyInput.value = '';
                rowQtyInput.setAttribute('max', maxQty);
                rowQtyInput.setAttribute('data-max', maxQty);

                // Update the pending badge with quantity
                if (pendingBadge) {
                    pendingBadge.textContent = `Pending: ${maxQty}`;
                    pendingBadge.classList.remove("bg-success", "bg-danger");
                    pendingBadge.classList.add("bg-warning");
                }

                calculateTotalPrice(rowQtyInput);
                hideSelectedOptions();
            },
            error: function () {
                alert('Error fetching item details');
            }
        });
    } else {
        hideSelectedOptions();
    }
}

// Function to update the pending note and tooltip dynamically
function updatePendingNote(input) {
    const max = parseInt(input.getAttribute("data-max")) || 0;
    const current = parseInt(input.value) || 0;
    const remaining = max - current;

    const row = input.closest("tr");
    const pendingBadge = row.querySelector(".pending-badge");

    if (pendingBadge) {
        if (remaining > 0) {
            pendingBadge.textContent = `Pending: ${remaining}`;
            pendingBadge.classList.remove("bg-success", "bg-danger");
            pendingBadge.classList.add("bg-warning");
            pendingBadge.setAttribute("data-bs-original-title", `You still need ${remaining} more items.`);
        } else {
            pendingBadge.textContent = `Complete`;
            pendingBadge.classList.remove("bg-warning", "bg-danger");
            pendingBadge.classList.add("bg-success");
            pendingBadge.setAttribute("data-bs-original-title", "All items have been received.");
        }

        // Reinitialize the tooltip
        const tooltip = bootstrap.Tooltip.getInstance(pendingBadge);
        if (tooltip) {
            tooltip.update();
        }
    }
}


// Function to hide already selected options in all particulars dropdowns
function hideSelectedOptions() {
    const allSelectElements = document.querySelectorAll("select[name='particulars[]']");
    const selectedValues = Array.from(allSelectElements)
        .map(select => select.value)
        .filter(value => value !== '');

    allSelectElements.forEach(currentSelect => {
        Array.from(currentSelect.options).forEach(option => {
            if (option.value !== '' && selectedValues.includes(option.value)) {
                if (option.value !== currentSelect.value) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            } else {
                option.style.display = '';
            }
        });
    });
}

// Function to remove an RR row
function removeRRRow(button) {
    button.closest("tr").remove();
    const rows = document.querySelectorAll("#rrItemsTableBody tr");
    rows.forEach((row, index) => {
        row.querySelector("td").textContent = index + 1;
    });
    hideSelectedOptions();
}

function calculateTotalPrice(input) {
    const row = input.closest('tr');
    const qty = parseInt(row.querySelector("input[name='quantity[]']").value) || 0;
    const unitPrice = parseFloat(row.querySelector("input[name='unit_price[]']").value) || 0;
    const totalField = row.querySelector("input[name='total_price[]']");

    // Validation: restrict quantity to max
    const max = parseInt(input.getAttribute("data-max")) || 0;
    if (qty > max) {
        alert(`Maximum allowed quantity is ${max}`);
        input.value = max;
        updatePendingNote(input);
        totalField.value = (max * unitPrice).toFixed(2);
        return;
    }

    updatePendingNote(input);
    totalField.value = (qty * unitPrice).toFixed(2);
}

function printContent() {
    const iframe = document.getElementById('RRDetailsFrame');
    if (iframe.contentWindow) {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    } else {
        console.error('Unable to access iframe content for printing.');
    }
}

function printPRSContent() {
    const iframe = document.getElementById('prsDetailsFrame');
    if (iframe.contentWindow) {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    } else {
        console.error('Unable to access iframe content for printing.');
    }
}

function loadAssetRecords() {
    fetch('custodian-fetch/fetch_asset_records.php', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if ($.fn.DataTable.isDataTable('#assetRecordsTable')) {
            $('#assetRecordsTable').DataTable().destroy();
        }

        const tbody = document.getElementById('assetRecordsTableBody');
        tbody.innerHTML = '';

        data.forEach(row => {
            let capexClass = row.capex_type === 'CAPEX'
                ? 'badge bg-cyan text-white'
                : 'badge bg-primary text-white';

            tbody.innerHTML += `
                <tr>
                    <td>${row.item_name}</td>
                    <td class="text-center">${row.department}</td>
                    <td class="text-center">${row.unit}</td>
                    <td class="text-right">${parseFloat(row.unit_price).toFixed(2)}</td>
                    <td class="text-center">${row.quantity}</td>
                    <td class="text-center">${row.rr_no}</td>
                    <td class="text-center">${row.date_received}</td>
                    <td class="text-center">${row.prs_code}</td>
                    <td class="text-center">${row.prs_date}</td>
                    <td class="text-right">₱ ${parseFloat(row.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td class="text-center"><span class="${capexClass}">${row.capex_type}</span></td>
                </tr>
            `;
        });

        $('#assetRecordsTable').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            order: [[9, 'desc']],
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
                    title: 'Equipment Transfers',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-secondary btn-sm',
                    text: 'Print',
                    title: 'Equipment Transfers',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (win) {
                        let api = $('#assetRecordsTable').DataTable();
                        let total = 0;

                        // Calculate grand total based on visible rows
                        api.rows({ search: 'applied' }).every(function () {
                            const row = this.node();
                            const amountText = $(row).find('td').eq(9).text().replace(/[₱,]/g, ''); // Remove peso signs and commas before parsing
                            const amount = parseFloat(amountText) || 0;
                            total += amount;
                        });

                        // Append the total row to the printed table
                        const table = $(win.document.body).find('table');
                        table.append(`
                            <tfoot>
                                <tr>
                                    <th colspan="9" style="text-align:right;">Grand Total:</th>
                                    <th style="text-align:right;"><strong>₱ ${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        `);
                    }
                }
            ],
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Item Records..."
            }
        });
    })
    .catch(error => console.error('Error loading asset records:', error));
}



$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const min = document.getElementById('minDate').value;
    const max = document.getElementById('maxDate').value;
    const receivedDate = data[8]; // 10th column (index 9) - Received Date

    if (!min && !max) return true;

    const date = new Date(receivedDate);
    const minDate = min ? new Date(min) : null;
    const maxDate = max ? new Date(max) : null;

    if ((minDate && date < minDate) || (maxDate && date > maxDate)) {
        return false;
    }

    return true;
});

document.getElementById('minDate').addEventListener('change', () => {
    $('#assetRecordsTable').DataTable().draw();
});

document.getElementById('maxDate').addEventListener('change', () => {
    $('#assetRecordsTable').DataTable().draw();
});

function loadCategoryRecords() {
    $.ajax({
        url: 'custodian-fetch/fetch_categories.php',
        method: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function (categoryData) {
            if ($.fn.DataTable.isDataTable('#categoryTable')) {
                $('#categoryTable').DataTable().destroy();
            }

            const tableBody = document.getElementById('categoryTableBody');
            tableBody.innerHTML = '';

            categoryData.forEach(cat => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${cat.itcat_id}</td>
                        <td>${cat.itcat_name}</td>
                        <td>${cat.itemcatgrp_name}</td>
                    </tr>
                `;
            });

            $('#categoryTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                order: [[1, 'asc']],
                columnDefs: [
                    { targets: [2], orderable: false }
                ],
                dom: `
                    <'row mb-3'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3 d-flex justify-content-end'f>>
                    <'row'<'col-sm-12'tr>>
                    <'row mt-2'<'col-sm-5 d-flex align-items-center'i><'col-sm-7 d-flex justify-content-end'p>>
                `,
                buttons: [
                    'pdf', 'print'
                ],
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search Category name..."
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching categories:', error);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const modalConfigs = [
        { cardId: 'totalItemsCard', modalId: 'totalItemsModal', loadFn: null },
        { cardId: 'rrListCard', modalId: 'rrModal', loadFn: () => { loadReceivingReports(); loadRRDetails(); } },
        { cardId: 'prsListCard', modalId: 'prsModal', loadFn: LoadPRSdetails },
        { cardId: 'dfListCard', modalId: 'dfModal', loadFn: () => { loadDeliveryForms(); loadDFDetails(); } },
        { cardId: 'expensesListCard', modalId: 'expensesModal', loadFn: loadAssetRecords },
        { cardId: 'eqTaggingCard', modalId: 'eqModal', loadFn: fetchEquipmentTagging },
        { cardId: 'transferListCard', modalId: 'transferModalTable', loadFn: loadTransferRecords },
        { cardId: 'totalCategoriesCard', modalId: 'categoryModal', loadFn: loadCategoryRecords }
    ];

    const delay = 2000; // 2 seconds delay

    modalConfigs.forEach(({ cardId, modalId, loadFn }) => {
        const card = document.getElementById(cardId);
        const modalElement = document.getElementById(modalId);

        if (card && modalElement) {
            card.addEventListener('click', () => {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                if (typeof loadFn === 'function') {
                    setTimeout(loadFn, delay);
                }
            });
        }
    });
});




let itemNoCounter = 1;
$(document).ready(function () {
    loadReceivingReports();
    loadRRDetails();
    LoadPRSdetails();
    LoadItems();
    loadDeliveryForms();
    initializeFilters();
    loadDFDetails();
    setupBarcodeGeneration();
    loadTransferRecords();
    handleItemFormSubmit("addItemForm");
    handleItemFormSubmit("updateItemForm");
    /*updateSummaryCounts();*/
    setupEquipmentTagging();
    setupStepNavigation();
    setupAddRowButton();
    setupRemoveRow();
    setupRrDetailsFetch();
    setupFormSubmission();
    setupModalShowEvent();
    fetchRROptionsEq();

    initializeRRSteps();
    initializeEqTaggingSteps();
    addNewEquipmentRow(); 
    submitEquipmentTaggingForm();
    fetchLatestBarcode();
    setupEqDetailsFetch();
    fetchEquipmentTagging();
    setupBarcodeGenerationModal();
    loadAssetRecords();
    setupReceivingReportForm();
    loadCategoryRecords();
    // RR step navigation buttons
    $('#rr-nextBtn').on('click', navigateNextStepRR);
    $('#rr-prevBtn').on('click', navigatePrevStepRR);

    // Equipment Tagging step navigation buttons
    $('#eq-nextBtn').on('click', navigateNextStepEq);
    $('#eq-prevBtn').on('click', navigatePrevStepEq);
});