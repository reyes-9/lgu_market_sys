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
            <img id="profile_picture" src="../images/default_profile_pic.png" alt="Profile Image" class="img-fluid w-25 h-auto my-3">
            <h2 class=""><span id="name"></span></h2>
            <p><span id="email"></span></p>
            <br>
            <hr>
            <h5>" <span id="bio"></span> "</h5>
          </div>
        </div>

        <div class="col-md-8 px-5 divide light">
          <div class="stall-card">

            <h3>Stalls</h3>
            <hr>
            <br>

            <h5 id="stall_message"></h5>

            <table class="table table-striped table-borderless table-hover custom-table light">
              <tbody>
                <tr>
                  <td><strong>Market</strong></td>
                  <td><strong>Section</strong></td>
                  <td><strong>Stall No.</strong></td>
                  <td><strong>Stall Size</strong></td>
                  <td><strong>Rental Fee</strong></td>
                </tr>
                <tr>
                  <td id="market"></td>
                  <td id="section"></td>
                  <td id="stall_number"></td>
                  <td id="stall_size"></td>
                  <td id="rental_fee"></td>
                </tr>
              </tbody>
            </table>

          </div>
          <div class="mt-5 text-end">
            <a href="/market-monitoring/pages/stall_extend" class="btn btn-warning m-2">Stall Extension Application</a>
            <a href="/market-monitoring/pages/helper_app" class="btn btn-warning m-2">Add Helper Application</a>
            <a href="#" class="btn btn-warning m-2 disabled">Market Fees</a>
            <a href="#" class="btn btn-warning m-2 disabled">Violations</a>
          </div>
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
      divide.classList.toggle('dark');
      divide.classList.toggle('light');
      table.classList.toggle('dark');
      table.classList.toggle('light');
    });

    // Fetch the user data from the backend
    document.addEventListener('DOMContentLoaded', function() {
        fetch('../actions/profile_action.php')
          .then(response => response.json())
          .then(data => {
            // Access user data
            const user = data.user[0];
            document.getElementById('name').textContent = user.name;
            document.getElementById('email').textContent = user.email;
            document.getElementById('bio').textContent = user.bio;

            if (!data.stalls || !data.stalls.length > 0) {
              const response = data.message[0];
              document.getElementById('stall_message').textContent = response.message;
              document.getElementById('stall_display').style.display = 'none';
            }

            const stall = data.stalls[0];
            document.getElementById('market').textContent = stall.market_name;
            document.getElementById('section').textContent = stall.section_name;
            document.getElementById('stall_number').textContent = stall.stall_number;
            document.getElementById('stall_size').textContent = stall.stall_size;
            document.getElementById('rental_fee').textContent = stall.rental_fee;
            document.getElementById('stall_message').textContent = '';
            document.getElementById('stall_display').style.display = 'block';
          })
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  </script>
</body>
</html>