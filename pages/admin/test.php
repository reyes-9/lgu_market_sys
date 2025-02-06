<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../../assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

  <div class="container my-5">
    <!-- Title and subtitle -->
    <div class="row text-center mb-4">
      <h2>Dashboard</h2>
      <p>Check the sales, value, and bounce rate by country.</p>
    </div>

    <!-- Statistic Cards Row -->
    <div class="row text-center mb-4">
        
      <!-- Today's Money -->
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm position-relative">
          <div class="icon-container">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="card-body">
            <h5 class="stat-title">Active Vendors</h5>
            <h3 class="stat-value">53k</h3>
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
            <h3 class="stat-value">2300</h3>
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
            <h5 class="stat-title">Total Occupied Stalls</h5>
            <h3 class="stat-value">3,462</h3>
            <p class="text-danger stat-change">-2% than yesterday</p>
          </div>
        </div>
      </div>

    <!-- Charts Row -->
    <div class="row">
      <!-- Website Views -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="stat-title">Website Views</h5>
            <p class="text-muted">Last Campaign Performance</p>
            <canvas id="websiteViewsChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Daily Sales -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="stat-title">Daily Sales</h5>
            <p class="text-muted">(+15%) increase in today's sales</p>
            <canvas id="dailySalesChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Completed Tasks -->
      <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="stat-title">Completed Tasks</h5>
            <p class="text-muted">Last Campaign Performance</p>
            <canvas id="completedTasksChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Website Views Chart
    var ctx1 = document.getElementById('websiteViewsChart').getContext('2d');
    var websiteViewsChart = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
        datasets: [{
          label: 'Website Views',
          data: [45, 30, 20, 35, 60, 70, 80],
          backgroundColor: '#4caf50'
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
          borderColor: '#4caf50',
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

    // Completed Tasks Chart
    var ctx3 = document.getElementById('completedTasksChart').getContext('2d');
    var completedTasksChart = new Chart(ctx3, {
      type: 'line',
      data: {
        labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Completed Tasks',
          data: [50, 100, 200, 300, 250, 400, 350, 450, 500],
          borderColor: '#4caf50',
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
</body>
</html>
