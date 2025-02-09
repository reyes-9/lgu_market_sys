<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Portal - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../../images/favicon_192.png">
  <link rel="stylesheet" href="../../assets/css/vendor_portal.css">
  <?php include '../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

  <?php include '../../includes/nav.php'; ?>

  <div class="content-wrapper">

    <?php include '../../includes/menu.php'; ?>

    <div class="container-fluid">
      <div class="row m-5 p-5 shadow rounded-3 profile light">

        <div class="col-md-4">
          <!-- Notifications Section (Initially Hidden) -->
          <div id="notificationsSection" class="d-none notification-container">
            <button class="btn btn-return" id="returnBtn">
              <i class="bi bi-arrow-left"></i> Back
            </button>

            <h4>Notifications</h4>

            <ul class="list-group notification-list" id="notificationList">
            </ul>
            <button class="btn btn-outline-light d-none" id="markAllReadBtn"></button>
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
              <span class="position-absolute top-0 start-100 translate-middle p-2" id="notificationAlert">
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
    // Fetch user data and notifications after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {

      fetchUser();
      fetchNotifications();

      document.getElementById("returnBtn").addEventListener("click", function() {
        fetchNotifications();
      });
      document.getElementById('markAllReadBtn').addEventListener('click', markAllAsRead);

    });



    function fetchNotifications() {
      fetch('../actions/notifications.php')
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success' && Array.isArray(data.notifications)) {
            renderNotifications(data.notifications);

            const hasUnread = data.notifications.some(notification => notification.status === 'unread');
            const notificationAlert = document.getElementById('notificationAlert');
            const readBtn = document.getElementById('markAllReadBtn');

            console.log(hasUnread);

            if (hasUnread) {
              notificationAlert.classList.add("bg-danger", "rounded-circle");
              readBtn.classList.remove('d-none');
              readBtn.innerHTML = "<small>Mark all as read</small>";
            } else {
              notificationAlert.classList.remove("bg-danger", "rounded-circle");
              readBtn.classList.add('d-none');
              readBtn.innerHTML = "<small>All read</small>";
            }
          }
        })
        .catch(error => console.error('Error fetching notifications:', error));
    }

    function fetchUser() {
      fetch('../actions/profile_action.php')
        .then(response => response.json())
        .then(data => {
          if (data.user && data.user.length > 0) {
            const user = data.user[0];
            document.getElementById('name').textContent = user.name;
            document.getElementById('email').textContent = user.email;
            document.getElementById('profile-name').textContent = user.name;
            document.getElementById('profile-email').textContent = user.email;
            document.getElementById('profile-birthdate').textContent = user.birthdate;
            document.getElementById('profile-address').textContent = user.address;
            document.getElementById('profile-contact').textContent = user.contact;
          } else {
            console.error('User data not found');
          }
        })
        .catch(error => {
          console.error('Error fetching user data:', error);
        });
    }

    function timeAgo(timestamp) {
      const now = new Date();
      const past = new Date(timestamp);
      const diffInSeconds = Math.floor((now - past) / 1000);
      const diffInMinutes = Math.floor(diffInSeconds / 60);
      const diffInHours = Math.floor(diffInMinutes / 60);
      const diffInDays = Math.floor(diffInHours / 24);
      const diffInWeeks = Math.floor(diffInDays / 7);
      const diffInMonths = Math.floor(diffInDays / 30); // Approximate

      if (diffInSeconds < 60) return "Just now";
      if (diffInMinutes < 60) return `${diffInMinutes} min`;
      if (diffInHours < 24) return `${diffInHours}h`;
      if (diffInDays < 7) return `${diffInDays}d`;
      if (diffInWeeks < 4) return `${diffInWeeks}w`;

      return past.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      });
    }

    function renderNotifications(notifications) {
      const notificationList = document.getElementById('notificationList');

      // let unread = false;
      notificationList.innerHTML = ""; // Clear previous notifications

      notifications.forEach(notification => {
        const notificationItem = document.createElement('li');
        notificationItem.classList.add("list-group-item", "position-relative");

        // If notification is unread, add the 'unread' class
        if (notification.status === "unread") {
          notificationItem.classList.add("unread");

        } else {
          notificationItem.classList.remove("unread");

        }

        notificationItem.innerHTML = `
      <strong>${notification.type}</strong>
      <p class="message-preview mb-1">${notification.message}</p>
      <span class="time">${timeAgo(notification.created_at)}</span>
      `;

        notificationList.appendChild(notificationItem);
      });

    }

    function markAllAsRead() {
      const notificationAlert = document.getElementById('notificationAlert');
      const readBtn = document.getElementById('markAllReadBtn');
      fetch('../actions/notifications.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'mark_all_read'
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.read_status === 'success') {

            document.querySelectorAll('.list-group-item.unread').forEach(item => {
              item.classList.remove('unread');
            });
          }

        })
    }
  </script>

</body>

</html>