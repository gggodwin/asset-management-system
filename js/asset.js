function loadAssetRecords() {
    fetch('../custodian/custodian-fetch/fetch_asset_records.php', {
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
                <'row mb-3'
                    <'col-12 col-md-4 d-flex justify-content-start align-items-center mb-2 mb-md-0'l>
                    <'col-12 col-md-4 d-flex justify-content-center justify-content-md-center mb-2 mb-md-0'B>
                    <'col-12 col-md-4 d-flex justify-content-end'f>
                >
                <'row'<'col-12'tr>>
                <'row mt-2'
                    <'col-12 col-md-5 d-flex align-items-center'i>
                    <'col-12 col-md-7 d-flex justify-content-center justify-content-md-end'p>
                >
            `,
            buttons: [
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-outline-success btn-sm me-2',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    title: 'Equipment Transfers',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    className: 'btn btn-outline-danger btn-sm',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    title: 'Detailed Expenses',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (win) {
                        let api = $('#assetRecordsTable').DataTable();
                        let total = 0;
        
                        api.rows({ search: 'applied' }).every(function () {
                            const row = this.node();
                            const amountText = $(row).find('td').eq(9).text().replace(/[₱,]/g, '');
                            const amount = parseFloat(amountText) || 0;
                            total += amount;
                        });
        
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
                searchPlaceholder: "Search Asset Records..."
            }
        });
    })
    .catch(error => console.error('Error loading asset records:', error));
}



$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const minInput = document.getElementById('minDate').value;
    const maxInput = document.getElementById('maxDate').value;
    const receivedDate = data[8]; // Column index where the date is located

    // Parse date from the table
    const date = new Date(receivedDate);

    // If only min is selected, use it for both min and max
    const minDate = minInput ? new Date(minInput) : null;
    const maxDate = maxInput ? new Date(maxInput) : (minDate ? new Date(minInput) : null);

    if (!minDate && !maxDate) return true;

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

$(document).ready(function () {
    loadAssetRecords();
});