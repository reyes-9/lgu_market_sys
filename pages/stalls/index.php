<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stalls - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../../images/favicon_192.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../../assets/css/vendor_portal.css">
</head>

<body class="body light">

  <?php include '../../includes/nav.php'; ?>

  <div class="content-wrapper">
    <?php include '../../includes/menu.php'; ?>
    <div class="container-fluid">
      <div class="row m-5 p-5 shadow rounded-3 profile light">
        <!-- Sidebar -->
        <div class="col-md-4 text-center">
          <div class="text-center profile-card h-100">
            <img id="profile_picture" src="../../images/default_profile_pic.png" alt="Profile Image" class="img-fluid w-25 h-auto my-3">
            <h2 class=""><span id="name"></span></h2>
            <p><span id="email"></span></p>
            <br>
            <hr>
            <h5>" <span id="bio"></span> "</h5>
          </div>
        </div>

        <div class="col-md-8 px-5 divide">
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
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
          // Set user profile data
          const user = data.user[0];
          document.getElementById('name').textContent = user.name;
          document.getElementById('email').textContent = user.email;
          document.getElementById('bio').textContent = user.bio;

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