<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stalls - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../../images/favicon_192.png">
  <link rel="stylesheet" href="../../assets/css/vendor_portal.css">
  <?php include '../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

  <?php include '../../includes/nav.php'; ?>

  <div class="content-wrapper">
    <?php include '../../includes/menu.php'; ?>
    <div class="container-fluid">
      <div class="row m-5 p-5 shadow rounded-3 profile light w-5">

        <div class="col-md-12 px-5">
          <div class="stall-card">
            <h3>Stalls</h3>
            <hr>
            <br>

            <h5 id="stall_message"></h5>

            <table class="table table-striped table-borderless table-hover custom-table light">
              <thead>
                <tr>
                  <th><strong>Market</strong></th>
                  <th><strong>Section</strong></th>
                  <th><strong>Stall No.</strong></th>
                  <th><strong>Stall Size</strong></th>
                  <th><strong>Rental Fee</strong></th>
                </tr>
              </thead>
              <tbody id="stallsContainer">
                <!-- Dynamic stall rows will be inserted here -->
              </tbody>
            </table>
          </div>

          <div class="mt-5 text-end">
            <a href="/lgu_market_sys/pages/stall_extend" class="btn btn-warning m-2">Stall Extension Application</a>
            <a href="/lgu_market_sys/pages/helper_app" class="btn btn-warning m-2">Add Helper Application</a>
            <a href="#" class="btn btn-warning m-2 disabled">Market Fees</a>
            <a href="#" class="btn btn-warning m-2 disabled">Violations</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../../includes/footer.php'; ?>
  <?php include '../../includes/theme.php'; ?>

  <script>
    // Theme
    const profile = document.querySelector('.profile');
    const divide = document.querySelector('.divide');
    const table = document.querySelector('.custom-table');

    themeToggleButton.addEventListener("click", () => {
      profile.classList.toggle("dark");
      profile.classList.toggle("light");
      table.classList.toggle('dark');
      table.classList.toggle('light');
    });

    // Fetch the user data from the backend
    document.addEventListener('DOMContentLoaded', function() {
      fetch('../actions/profile_action.php')
        .then(response => response.json())
        .then(data => {

          // Handle stalls
          const stallsContainer = document.getElementById('stallsContainer');
          stallsContainer.innerHTML = ''; // Clear previous entries

          if (!data.stalls || data.stalls.length === 0) {
            document.getElementById('stall_message').textContent = 'No stalls available.';
          } else {
            document.getElementById('stall_message').textContent = '';

            // Loop through each stall and create table rows
            data.stalls.forEach(stall => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${stall.market_name}</td>
                <td>${stall.section_name}</td>
                <td>${stall.stall_number}</td>
                <td>${stall.stall_size}</td>
                <td>${stall.rental_fee}</td>
              `;
              stallsContainer.appendChild(row);
            });
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    });
  </script>
</body>

</html>