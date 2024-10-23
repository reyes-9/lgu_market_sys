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

  <div class="container-fluid p-0">
    <div class="row m-5 p-5">
      <div class="container-fluid">

        <!-- Title and subtitle -->
        <div class="row text-center mb-4">
          <h2>Admin Dashboard</h2>
          <p>Check the sales, value, and bounce rate by country.</p>
        </div>

        <!-- Statistic Cards Row -->
        <div class="row  justify-content-center align-items-center text-center mb-4">

          <!-- Today's Money -->
          <div class="col-md-4 mb-3">
            <div class="card shadow-sm position-relative">
              <div class="icon-container">
                <i class="bi bi-person-fill"></i>
              </div>
              <div class="card-body">
                <h5 class="stat-title">Active Vendors</h5>
                <h3 class="stat-value">762</h3>
                <p class="text-success stat-change">+55% than last week</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card shadow-sm position-relative">
              <div class="icon-container">
                <i class="bi bi-file-earmark-fill"></i>
              </div>
              <div class="card-body">
                <h5 class="stat-title">Today's Applications</h5>
                <h3 class="stat-value">49</h3>
                <p class="text-success stat-change">+3% than last month</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card shadow-sm position-relative">
              <div class="icon-container">
                <i class="bi bi-bag-fill"></i>
              </div>
              <div class="card-body">
                <h5 class="stat-title">Pending Applications</h5>
                <h3 class="stat-value">130</h3>
                <p class="text-danger stat-change">-2% than yesterday</p>
              </div>
            </div>
          </div>

          <!-- Charts Row -->
          <div class="row">
            <!-- Website Views -->
            <div class="col-md-6 mb-3">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="stat-title">Website Views</h5>
                  <p class="text-muted">Views Graph</p>
                  <canvas id="websiteViewsChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Daily Sales -->
            <div class="col-md-6 mb-3">
              <div class="card shadow-sm">
                <div class="card-body">
                  <h5 class="stat-title">Daily Applications</h5>
                  <p class="text-muted">Vendors who submits application.</p>
                  <canvas id="dailySalesChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card shadow-sm position-relative">
              <div class="system_alert_icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
              </div>
              <div class="card-body">
                <h5 class="stat-title mb-3">System Alerts</h5>
                <ul class="list-unstyled">
                  <li>New system update available</li>
                  <li>Market maintenance scheduled</li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <hr>

  <!-- Dashboard Header -->
  <div class="dashboard-header">
    <h1 class="h3">Admin Dashboard</h1>
    <button class="btn btn-primary">+ Add New Application</button>
  </div>

  <!-- Summary of Key Metrics -->
  <div class="row">
    <!-- Active Vendors -->
    <div class="col-md-3">
      <div class="card dashboard-card bg-gradient-info">
        <div class="card-body card-stats">
          <i class="fas fa-user-friends card-icon"></i>
          <div>
            <h5 class="card-title">Active Vendors</h5>
            <p class="stat-number">150</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Applications -->
    <div class="col-md-3">
      <div class="card dashboard-card bg-gradient-warning">
        <div class="card-body card-stats">
          <i class="fas fa-hourglass-half card-icon"></i>
          <div>
            <h5 class="card-title">Pending Applications</h5>
            <p class="stat-number">12</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Approved Applications -->
    <div class="col-md-3">
      <div class="card dashboard-card bg-gradient-success">
        <div class="card-body card-stats">
          <i class="fas fa-check-circle card-icon"></i>
          <div>
            <h5 class="card-title">Approved Applications</h5>
            <p class="stat-number">35</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Rejected Applications -->
    <div class="col-md-3">
      <div class="card dashboard-card bg-gradient-danger">
        <div class="card-body card-stats">
          <i class="fas fa-times-circle card-icon"></i>
          <div>
            <h5 class="card-title">Rejected Applications</h5>
            <p class="stat-number">5</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stalls and Feedback -->
  <div class="row">
    <!-- Market Stalls -->
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-store-alt card-icon"></i> Market Stalls</h5>
          <p class="stat-number">Occupied: 130 | Vacant: 20</p>
        </div>
      </div>
    </div>

    <!-- Recent Feedback -->
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-comment card-icon"></i> Recent Feedback</h5>
          <ul class="list-unstyled">
            <li>User 1: Issue with stall location</li>
            <li>User 2: Need for more vendors</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Vendor Activities and Alerts -->
  <div class="row">
    <!-- Recent Vendor Activities -->
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-clipboard-list card-icon"></i> Recent Vendor Activities</h5>
          <ul class="list-unstyled">
            <li>Vendor A applied for stall transfer</li>
            <li>Vendor B submitted a new application</li>
          </ul>
        </div>
      </div>
    </div>



    <!-- Application Trends Chart -->
    <div class="row">
      <div class="col-md-12">
        <div class="card dashboard-card chart-card">
          <h5 class="card-title"><i class="fas fa-chart-line card-icon"></i> Application Trends</h5>
          <canvas id="applicationsChart"></canvas>
        </div>
      </div>
    </div>



    <!-- Announcements -->
    <div class="row">
      <div class="col-md-12">
        <div class="card dashboard-card">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-bullhorn card-icon"></i> Announcements</h5>
            <ul class="announcement-list">
              <li><i class="fas fa-exclamation-circle"></i> Public holiday on October 25th - Market will be closed.</li>
              <li><i class="fas fa-exclamation-circle"></i> Maintenance work scheduled for next week.</li>
            </ul>
          </div>
        </div>
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