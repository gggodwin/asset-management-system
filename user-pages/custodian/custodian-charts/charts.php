<div class="col-lg-12 col-xl-12">
    <div class="card">
        <div class="card-body">
            <!-- Flexbox to align title on the left and dropdown on the right -->
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Inventory Stock Summary</h3>

                <div class="dropdown">
                    <button class="btn btn-ghost-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter Inventory
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown" id="categoryFilter">
                        <li><a class="dropdown-item" href="#" data-value="all">All Categories</a></li>
                        <li><a class="dropdown-item" href="#" data-value="low_stock">Low Stock</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </div>
            </div>

            <div id="inventory-chart" class="chart-lg"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let originalData = []; // Store full dataset
    let chartInstance; // Store chart instance

    // Fetch data from server
    fetch('custodian-fetch/fetch_consumables.php', {
        method: 'GET', // Optional, 'GET' is the default for fetch
        headers: {
            'X-Requested-With': 'XMLHttpRequest',  // Add this header to identify the request as an AJAX request
        }
    })
    .then(response => response.json())
    .then(data => {
        //console.log("Fetched Data:", data);

        if (!data.success || !Array.isArray(data.items)) {
            console.error("Invalid data format: Expected 'items' array", data);
            return;
        }

        originalData = data.items; // Save full dataset

        // Extract unique categories
        const uniqueCategories = [...new Set(data.items.map(item => item.category))];

        // Populate category filter dropdown
        const categoryFilter = document.getElementById('categoryFilter');
        uniqueCategories.forEach(category => {
            if (category) {
                const listItem = document.createElement('li');
                listItem.innerHTML = `<a class="dropdown-item" href="#" data-value="${category}">${category}</a>`;
                categoryFilter.appendChild(listItem);
            }
        });

        // Initialize chart with all data
        renderChart(originalData);

        // Add event listeners for filter selection
        categoryFilter.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                document.getElementById('filterDropdown').textContent = this.textContent;
                filterAndUpdateChart(selectedValue);
            });
        });
    })
    .catch(error => console.error("Error fetching inventory data:", error));

    // Function to render the chart
    function renderChart(data) {
        const itemNames = data.map(item => item.particular);
        const quantities = data.map(item => parseInt(item.quantity, 10)); // Updated from current_stock

        if (window.ApexCharts) {
            chartInstance = new ApexCharts(document.getElementById('inventory-chart'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 400,
                    parentHeightOffset: 0,
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            reset: true
                        }
                    },
                    stacked: false, // Ensure bars are not stacked
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        dataLabels: { position: "top" },
                        grouped: true // Ensures side-by-side bars
                    }
                },
                dataLabels: { enabled: false },
                fill: { opacity: 1 },
                series: [
                    { name: "Current Stock", data: quantities }  // Only showing quantity now
                ],
                tooltip: { theme: 'dark' },
                grid: {
                    padding: { top: -20, right: 0, left: -4, bottom: -4 },
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } }
                },
                xaxis: {
                    categories: itemNames,
                    labels: {
                        rotate: -45,
                        style: { fontSize: '12px' },
                        formatter: function(value) {
                            return value.length > 15 ? value.substring(0, 15) + "..." : value;
                        }
                    }
                },
                yaxis: { labels: { padding: 4 } },
                colors: [tabler.getColor("blue")],  // Only one color now for current stock
                legend: { show: true, position: 'top' }
            });

            chartInstance.render();
        } else {
            console.error("ApexCharts library not loaded.");
        }
    }

    // Function to update chart based on filters
    function filterAndUpdateChart(selectedFilter) {
        let filteredData = originalData;

        // Filter logic
        if (selectedFilter === "low_stock") {
            filteredData = filteredData.filter(item => parseInt(item.quantity, 10) < 1);  // Low stock if quantity is less than 1
        } else if (selectedFilter !== "all") {
            filteredData = filteredData.filter(item => item.category === selectedFilter);
        }

        updateChart(filteredData);
    }

    // Function to update chart with filtered data
    function updateChart(filteredData) {
        if (!chartInstance) return;

        const newItemNames = filteredData.map(item => item.particular);
        const newQuantities = filteredData.map(item => parseInt(item.quantity, 10)); // Only showing quantity now

        chartInstance.updateOptions({
            xaxis: { categories: newItemNames },
            series: [
                { name: "Current Stock", data: newQuantities }  // Only showing quantity now
            ]
        });
    }

    // Handle item deletion
    function deleteItem(itemId) {
        // Perform delete logic (AJAX request or similar)
        fetch(`custodian-fetch/delete_item.php?id=${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',  // Add this header to identify the request as an AJAX request
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from originalData
                originalData = originalData.filter(item => item.id !== itemId);

                // Re-render the chart with updated data
                renderChart(originalData);
            } else {
                console.error('Failed to delete item:', data.message);
            }
        })
        .catch(error => console.error('Error deleting item:', error));
    }
});

</script>
