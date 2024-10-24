<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../favicon_192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div class="container-fluid p-0 text-center">
        <div class="row m-5 p-5">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div>
                                    <h4>Stall Applications Table</h4>
                                    <div class="filter-buttons mb-5">
                                        <button class="btn">All Applications</button>
                                        <button class="btn">Approved</button>
                                        <button class="btn">Pending</button>
                                        <button class="btn">Rejected</button>
                                    </div>

                                    <table class="table table-striped table-borderless table-hover custom-table mt-4 light">
                                        <thead>
                                            <tr>
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
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>John Doe</td>
                                                <td>12A</td>
                                                <td>+1234567890</td>
                                                <td>Helper</td>
                                                <td>Pending</td>
                                                <td>Food</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Action</button></li>
                                                            <li><button class="dropdown-item" href="#">Another action</button></li>
                                                            <li><button class="dropdown-item" href="#">Something else here</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jane Smith</td>
                                                <td>34B</td>
                                                <td>+0987654321</td>
                                                <td>Stall</td>
                                                <td>Pending</td>
                                                <td>Clothing</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div>
                                    <h4>Stall Transfer Applications Table</h4>
                                    <div class="filter-buttons mb-5">
                                        <button class="btn">All Applications</button>
                                        <button class="btn">Approved</button>
                                        <button class="btn">Pending</button>
                                        <button class="btn">Rejected</button>
                                    </div>
                                    <table class="table table-striped table-borderless table-hover custom-table mt-4 light">
                                        <thead>
                                            <tr>
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
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>John Doe</td>
                                                <td>12A</td>
                                                <td>+1234567890</td>
                                                <td>Helper</td>
                                                <td>Pending</td>
                                                <td>Food</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jane Smith</td>
                                                <td>34B</td>
                                                <td>+0987654321</td>
                                                <td>Stall</td>
                                                <td>Pending</td>
                                                <td>Clothing</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div>
                                    <h4>Stall Extension Applications Table</h4>
                                    <div class="filter-buttons mb-5">
                                        <button class="btn">All Applications</button>
                                        <button class="btn">Approved</button>
                                        <button class="btn">Pending</button>
                                        <button class="btn">Rejected</button>
                                    </div>
                                    <table class="table table-striped table-borderless table-hover custom-table mt-4 light">
                                        <thead>
                                            <tr>
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
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>John Doe</td>
                                                <td>12A</td>
                                                <td>+1234567890</td>
                                                <td>Helper</td>
                                                <td>Pending</td>
                                                <td>Food</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jane Smith</td>
                                                <td>34B</td>
                                                <td>+0987654321</td>
                                                <td>Stall</td>
                                                <td>Pending</td>
                                                <td>Clothing</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div>
                                    <h4>Helper Applications Table</h4>
                                    <div class="filter-buttons mb-5">
                                        <button class="btn">All Applications</button>
                                        <button class="btn">Approved</button>
                                        <button class="btn">Pending</button>
                                        <button class="btn">Rejected</button>
                                    </div>
                                    <table class="table table-striped table-borderless table-hover custom-table mt-4 light">
                                        <thead>
                                            <tr>
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
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>John Doe</td>
                                                <td>12A</td>
                                                <td>+1234567890</td>
                                                <td>Helper</td>
                                                <td>Pending</td>
                                                <td>Food</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jane Smith</td>
                                                <td>34B</td>
                                                <td>+0987654321</td>
                                                <td>Stall</td>
                                                <td>Pending</td>
                                                <td>Clothing</td>
                                                <td>
                                                    <div class="btn-group dropend">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><button class="dropdown-item" href="#">Approve</button></li>
                                                            <li><button class="dropdown-item" href="#">Reject</button></li>
                                                            <li><button class="dropdown-item" href="#">Delete</button></li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
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

            </div>
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

        // Daily Sales Chart
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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