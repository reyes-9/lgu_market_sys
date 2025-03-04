<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../favicon_192.png">
  <link rel="stylesheet" href="../../../assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

  <?php include '../../../includes/nav.php'; ?>

  <!-- Toast -->
  <div class="toast-container mt-5 p-3 top-0 end-0">
    <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show" data-bs-autohide="false">
      <div class="toast-header text-bg-warning rounded-top">
        <svg class="mx-2" width="25" height="22" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <rect x="0" y="0" width="100" height="100" rx="20" fill="url(#grad1)" />
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#ff4c4c;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#b30000;stop-opacity:1" />
            </linearGradient>
          </defs>
          <polygon points="50,20 75,75 25,75" fill="white" />
          <rect x="47" y="40" width="6" height="20" fill="#ff4c4c" />
          <circle cx="50" cy="70" r="3" fill="#ff4c4c" />
        </svg>
        <strong class="me-auto">System Alerts</strong>
        <small>11 mins ago</small>
      </div>
      <div class="toast-body text-light rounded-bottom p-4">
        New system update available <br>
        Market maintenance scheduled
      </div>
    </div>
  </div>

  <div class="text-center mt-5 p-0">
    <h2 class="title light">Admin Dashboard</h2>
  </div>

  <div class="container-fluid p-0">

    <div class="row m-4 p-4">
      <div class="container-fluid">
        <div class="row announcement light p-4 m-3 text-center rounded shadow">
          <div class="line">
            <h3 class="stat-title mb-3">Announcements</h3>
          </div>

          <ul class="list-unstyled mt-5">
            <li> Public holiday on October 25th - Market will be closed.</li>
            <li> Maintenance work scheduled for next week.</li>
          </ul>
        </div>



        <!-- Statistic Cards Row -->
        <div class="row justify-content-center align-items-center text-center mb-4">

          <!-- Today's Money -->
          <div class="col-md-4 mb-3">
            <div class="card light shadow position-relative">
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
            <div class="card light shadow position-relative">
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
            <div class="card light shadow position-relative">
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
              <div class="card light shadow">
                <div class="card-body">
                  <h5 class="stat-title">Monthly Applications</h5>
                  <canvas id="websiteViewsChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Daily Sales -->
            <div class="col-md-6 mb-3">
              <div class="card light shadow">
                <div class="card-body">
                  <h5 class="stat-title">Violations Trend</h5>
                  <canvas id="dailySalesChart"></canvas>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="row text-center">
      <div class="container">
        <a href="../table/" class="btn btn-warning" tabindex="-1" role="button" aria-disabled="true">Manage Applications</a>
      </div>
    </div>

  </div>

  <hr>

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
    const title = document.querySelector('.title');
    const announcement = document.querySelector('.announcement');
    const cards = document.querySelectorAll('.card');

    console.log(announcement);

    themeToggleButton.addEventListener("click", () => {

      title.classList.toggle('dark');
      title.classList.toggle('light');
      announcement.classList.toggle('dark');
      announcement.classList.toggle('light');

      cards.forEach((cards) => {
        cards.classList.toggle('dark');
        cards.classList.toggle('light');
        console.log("Card:", cards.classList);
      });
    });
  </script>
</body>

</html>