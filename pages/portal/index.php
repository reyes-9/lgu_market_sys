<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Portal - Public Market Monitoring System</title>
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

        <div class="col-md-4">
          <!-- Notifications Section (Initially Hidden) -->
          <div id="notificationsSection" class="mt-3 d-none notification-container">
            <button class="btn btn-return" id="returnBtn">
              <i class="bi bi-arrow-left"></i> Back
            </button>

            <h4>All Notifications</h4>

            <ul class="list-group notification-list" id="notification">
              <li class="list-group-item position-relative">
                New message received <span class="time">Just now</span>
              </li>
              <li class="list-group-item position-relative">
                New message received <span class="time">Just now</span>
              </li>
              <li class="list-group-item position-relative">
                New message received <span class="time">Just now</span>
              </li>
              <li class="list-group-item position-relative">
                New message received <span class="time">Just now</span>
              </li>
              <li class="list-group-item position-relative">
                New message received <span class="time">Just now</span>
              </li>
              <li class="list-group-item">Pending approval needed</li>
              <li class="list-group-item">Your request was approved</li>
              <li class="list-group-item">Your stall renewal is due soon</li>
            </ul>
          </div>

          <div class="text-center profile-card h-100" id="profileCard">

            <img id="profile_picture" src="../../images/default_profile_pic.png" alt="Profile Image" class="img-fluid w-25 h-auto my-3">

            <h2 class=""><span id="name"></span></h2>
            <p><span id="email"></span></p>
            <br>
            <hr>

            <!-- Notifications Button -->

            <button type="button" class="btn btn-warning position-relative" id="toggleNotifications">
              Notifications
              <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger rounded-circle">
                <span class="visually-hidden">New alerts</span>
              </span>
            </button>

          </div>
        </div>

        <!-- Profile Info -->
        <div class="col-md-8 px-5 divide">
          <div class="profile-card">
            <h3>Information</h3>
            <hr>
            </button>
            <br>

            <table class="table table-striped table-borderless table-hover custom-table light">
              <tbody>
                <tr>
                  <td><strong>Name:</strong></td>
                  <td id="profile-name"></td>
                </tr>
                <tr>
                  <td><strong>Email:</strong></td>
                  <td id="profile-email"></td>
                </tr>
                <tr>
                  <td><strong>Birthdate:</strong></td>
                  <td id="profile-birthdate"></td>
                </tr>
                <tr>
                  <td><strong>Address:</strong></td>
                  <td id="profile-address"></td>
                </tr>
                <tr>
                  <td><strong>Contact:</strong></td>
                  <td id="profile-contact"></td>
                </tr>
              </tbody>
            </table>
            <div class="mt-3 text-end"><a href="../track_app/" class="btn btn-warning m-1">Track Applications</a></div>
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

    var notifcation_section = document.getElementById('notificationsSection');
    var profile_section = document.getElementById('profileCard');

    // Toggle Notification Section
    document.getElementById('toggleNotifications').addEventListener('click', function() {
      notifcation_section.classList.toggle('d-none');
      profile_section.classList.toggle('d-none');
    });

    document.getElementById('returnBtn').addEventListener('click', function() {
      notifcation_section.classList.toggle('d-none');
      profile_section.classList.toggle('d-none');
    });
  </script>
  <script>
    // Fetch the user data from the backend
    document.addEventListener('DOMContentLoaded', function() {
        fetch('../actions/profile_action.php')
          .then(response => response.json())
          .then(data => {
            // Access user data
            const user = data.user[0];
            document.getElementById('name').textContent = user.name;
            document.getElementById('email').textContent = user.email;
            // document.getElementById('bio').textContent = user.bio;
            document.getElementById('profile-name').textContent = user.name;
            document.getElementById('profile-email').textContent = user.email;
            document.getElementById('profile-birthdate').textContent = user.birthdate;
            document.getElementById('profile-address').textContent = user.address;
            document.getElementById('profile-contact').textContent = user.contact;

          })
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  </script>
</body>

</html>