<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../favicon_192.png">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

    <nav class="navbar navbar-expand-lg shadow-sm light" id="navbar">
        <div class="container">
            <a class="navbar-brand light" href="#">
                <img src="../favicon_192.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                Public Market Monitoring System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-light">
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/lgu_market_sys/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                    <li class="nav-item m-1 p-1">
                        <button class="btn-toggle" id="theme-toggle">
                            <i class="bi bi-moon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid text-center">
        <div class="container-fluid">
            <div class="row justify-content-center mb-3">
                <div class="col-lg-10 col-md-12 mt-5">
                    <div class="container stall-transfer">
                        <h4 class="mb-3">Stall Transfer Applications Table</h4>

                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button class="btn filter-button">All Applications</button>
                            <button class="btn filter-button">Approved</button>
                            <button class="btn filter-button">Pending</button>
                            <button class="btn filter-button">Rejected</button>
                        </div>

                        <div class="table-responsive table-container">
                            <table class="table table-striped table-borderless table-hover custom-table">
                                <thead class="table-primary sticky-header">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account</th>
                                        <th>Stall</th>
                                        <th>Section</th>
                                        <th>Market</th>
                                        <th>Application Type</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-stall-transfer">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container stall-app">
                        <h4 class="mb-3">Stall Applications Table</h4>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button class="btn filter-button">All Applications</button>
                            <button class="btn filter-button">Approved</button>
                            <button class="btn filter-button">Pending</button>
                            <button class="btn filter-button">Rejected</button>
                        </div>

                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-borderless custom-table">
                                <thead class="table-primary sticky-header">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account</th>
                                        <th>Stall</th>
                                        <th>Section</th>
                                        <th>Market</th>
                                        <th>Application Type</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-stall-app">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container stall-extend">
                        <h4>Stall Extension Applications Table</h4>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button class="btn filter-button">All Applications</button>
                            <button class="btn filter-button">Approved</button>
                            <button class="btn filter-button">Pending</button>
                            <button class="btn filter-button">Rejected</button>
                        </div>
                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-borderless custom-table">
                                <thead class="table-primary sticky-header">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account</th>
                                        <th>Stall</th>
                                        <th>Section</th>
                                        <th>Market</th>
                                        <th>Application Type</th>
                                        <th>Extension Duration</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-stall-extension">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container add-helper">
                        <h4>Helper Applications Table</h4>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button class="btn filter-button">All Applications</button>
                            <button class="btn filter-button">Approved</button>
                            <button class="btn filter-button">Pending</button>
                            <button class="btn filter-button">Rejected</button>
                        </div>
                        <div class="table-responsive table-container">
                            <table class="table table-striped table-hover table-borderless custom-table">
                                <thead class="table-primary sticky-header">
                                    <tr>
                                        <th>ID</th>
                                        <th>Account</th>
                                        <th>Stall</th>
                                        <th>Section</th>
                                        <th>Market</th>
                                        <th>Application Type</th>
                                        <th>Helper Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body-stall-helper">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="container">
            <a href="../home/" class="btn btn-warning" tabindex="-1" role="button" aria-disabled="true">Go to Dashboard</a>
        </div>
    </div>


    <?php include '../../../includes/footer.php'; ?>
    <?php include '../../../includes/theme.php'; ?>

    <script>
        // Website Views
        var ctx = document.getElementById('websiteViewsChart').getContext('2d');
        var websiteViewsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
                datasets: [{
                    label: 'Website Views',
                    data: [45, 30, 20, 35, 60, 70, 80],
                    backgroundColor: function(ctx) {
                        var chart = ctx.chart;
                        var {
                            ctx: chartCtx,
                            chartArea
                        } = chart;

                        if (!chartArea) {
                            // This prevents errors during the initial chart creation before the layout is calculated
                            return;
                        }

                        return chart.data.datasets[0].data.map(function(value, index) {
                            // Create a gradient for each bar
                            var gradient = chartCtx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, '#186fc4');
                            gradient.addColorStop(1, '#003366');
                            return gradient;
                        });
                    },
                    borderColor: '#003366', // Border color for the bars (optional)
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Daily Sales 
        var ctx2 = document.getElementById('dailySalesChart').getContext('2d');
        var dailySalesChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
                datasets: [{
                    label: 'Daily Sales',
                    data: [200, 220, 180, 350, 400, 390, 320, 450, 380, 300, 310, 410],
                    borderColor: '#186fc4',
                    fill: false,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        window.onload = () => {
            fetchTableStall();
            fetchTableStallTransfer();
            fetchTableStallExtend();
            fetchTableAddHelper();
            setInterval(fetchTableData, 10000); // Subsequent updates every 5 seconds
        };

        function fetchTableAddHelper() {
            fetch('../../actions/admin_action.php')
                .then(response => response.json())
                .then(data => {
                    const stallHelperTableBody = document.getElementById('table-body-stall-helper');
                    stallHelperTableBody.innerHTML = '';
                    // HELPER
                    data.helper.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${row.id}</td>
                        <td>${row.account_name}</td>
                        <td>${row.stall_number}</td>
                        <td>${row.section_name}</td>
                        <td>${row.market_name}</td>
                        <td>${row.application_type}</td>
                        <td>${row.helper_name}</td>
                        <td>${row.status}</td>
                        <td>${row.created_at}</td>
                        <td>
                            <div class="btn-group dropend">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" href="#">Approve</button></li>
                                    <li><button class="dropdown-item" href="#">Reject</button></li>
                                    <li><button class="dropdown-item" href="#">Delete</button></li>
                                    <li><button class="dropdown-item" href="#">View Document</button></li>
                                </ul>
                            </div>
                        </td>
                    `;
                        stallHelperTableBody.appendChild(tr);
                    });
                }).catch(error => console.error('Error fetching table data:', error));
        }

        function fetchTableStallExtend() {
            fetch('../../actions/admin_action.php')
                .then(response => response.json())
                .then(data => {
                    const stallExtensionTableBody = document.getElementById('table-body-stall-extension');
                    stallExtensionTableBody.innerHTML = '';
                    // EXTENSION
                    data.stall_extension.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${row.id}</td>
                        <td>${row.account_name}</td>
                        <td>${row.stall_number}</td>
                        <td>${row.section_name}</td>
                        <td>${row.market_name}</td>
                        <td>${row.application_type}</td>
                        <td>${row.ext_duration} Month(s)</td>
                        <td>${row.status}</td>
                        <td>${row.created_at}</td>
                        <td>
                            <div class="btn-group dropend">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" href="#">Approve</button></li>
                                    <li><button class="dropdown-item" href="#">Reject</button></li>
                                    <li><button class="dropdown-item" href="#">Delete</button></li>
                                    <li><button class="dropdown-item" href="#">View Application</button></li>
                                </ul>
                            </div>
                        </td>
                    `;
                        stallExtensionTableBody.appendChild(tr);
                    });
                }).catch(error => console.error('Error fetching table data:', error));
        }

        function fetchTableStallTransfer() {
            fetch('../../actions/admin_action.php')
                .then(response => response.json())
                .then(data => {
                    const stallTransferTableBody = document.getElementById('table-body-stall-transfer');
                    stallTransferTableBody.innerHTML = '';
                    // TRANSFER
                    data.stall_transfer.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${row.id}</td>
                        <td>${row.account_name}</td>
                        <td>${row.stall_number}</td>
                        <td>${row.section_name}</td>
                        <td>${row.market_name}</td>
                        <td>${row.application_type}</td>
                        <td>${row.status}</td>
                        <td>${row.created_at}</td>
                        <td>
                            <div class="btn-group dropend">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" href="#">Approve</button></li>
                                    <li><button class="dropdown-item" href="#">Reject</button></li>
                                    <li><button class="dropdown-item" href="#">Delete</button></li>
                                    <li><button class="dropdown-item" href="#">View Application</button></li>
                                </ul>
                            </div>
                        </td>
                    `;
                        stallTransferTableBody.appendChild(tr);
                    });
                })
        }

        // Fetch data from the server and populate the table
        function fetchTableStall() {
            fetch('../../actions/admin_action.php')
                .then(response => response.json())
                .then(data => {
                    const stallTableBody = document.getElementById('table-body-stall-app');

                    // Clear existing rows
                    stallTableBody.innerHTML = '';

                    // STALL
                    data.stall.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.account_name}</td>
                    <td>${row.stall_number}</td>
                    <td>${row.section_name}</td>
                    <td>${row.market_name}</td>
                    <td>${row.application_type}</td>
                    <td>${row.status}</td>
                    <td>${row.created_at}</td>
                    <td>
                        <div class="btn-group dropend">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Select
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" href="#">Approve</button></li>
                                <li><button class="dropdown-item" href="#">Reject</button></li>
                                <li><button class="dropdown-item" href="#">Delete</button></li>
                                <li><button class="dropdown-item" href="#">View Application</button></li>
                            </ul>
                        </div>
                    </td>
                `;
                        stallTableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching table data:', error));
        }
    </script>

    <script>
        var ctx = document.getElementById('applicationsChart').getContext('2d');
        var applicationsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Applications',
                    data: [12, 19, 3, 5, 2, 3, 7],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>
    <script>
        // Theme
        const profile = document.querySelector('.profile');
        const divide = document.querySelector('.divide');
        const table = document.querySelector('.custom-table');

        themeToggleButton.addEventListener("click", () => {
            profile.classList.toggle("dark");
            profile.classList.toggle("light");
            divide.classList.toggle('dark');
            divide.classList.toggle('light');
            table.classList.toggle('dark');
            table.classList.toggle('light');
        });
    </script>
</body>

</html>