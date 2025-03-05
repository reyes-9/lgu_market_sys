<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Portal - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../../images/favicon_192.png">
  <link rel="stylesheet" href="../../assets/css/vendor_portal.css">
  <?php include "../../includes/cdn-resources.php"; ?>
</head>

<body class="body light">

  <?php include '../../includes/nav.php'; ?>

  <div class="content-wrapper">

    <div class="container-fluid">
      <div class="row m-5 p-5 shadow rounded-3 profile light">

        <div class="col-md-4">

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
            <p><span id="profile-address"></span></p>
            <p><span id="email"></span></p>
            <p><span id="profile-name"></span></p>
            <p><span id="profile-contact"></span></p>
            <p><span id="profile-email"></span></p>
            <p><span id="profile-birthdate"></span></p>

            <br>
            <hr>

            <!-- Notifications Button -->
            <button type="button" class="btn btn-warning position-relative" id="toggleNotifications">
              <i class="bi bi-bell-fill"></i>

              <span class="position-absolute top-0 start-100 translate-middle p-2 " id="notificationAlert">
                <span class="visually-hidden">New alerts</span>
              </span>
            </button>
            <a href="../track_app/" class="btn btn-warning mx-3"><i class="bi bi-arrow-repeat"></i> Track Applications</a>

          </div>
        </div>

        <div class="col-md-8 px-5 divide">

          <!-- Violation Modal -->
          <div class="modal fade text-dark" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="violationModalLabel">Add Violation</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="violationForm">
                    <div class="mb-3">
                      <label for="vendor" class="form-label">Select Vendor</label>
                      <select id="vendor" class="form-control" required></select>
                    </div>
                    <div class="mb-3">
                      <label for="violationType" class="form-label">Violation Type</label>
                      <select id="violationType" class="form-control" required>
                        <option value="Late Payment">Late Payment</option>
                        <option value="Unauthorized Selling">Unauthorized Selling</option>
                        <option value="Sanitation Issue">Sanitation Issue</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="remarks" class="form-label">Remarks</label>
                      <textarea id="remarks" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Violation</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="container mt-4 text-center">
            <h2 class="mb-3">Violations Summary</h2>
            <div class="violation card">
              <div class="card-body">
                <div class="row">
                  <!-- Critical Violations Card -->
                  <div class="col-md-4">
                    <div class="info-card shadow rounded bg-danger text-white"
                      data-bs-toggle="tooltip" title="Critical issues that require immediate action">
                      <h4 id="criticalTxt"></h4>
                      <p class="mb-0">Critical Violations</p>
                    </div>
                  </div>
                  <!-- Pending Violations Card -->
                  <div class="col-md-4">
                    <div class="info-card shadow rounded bg-warning text-dark"
                      data-bs-toggle="tooltip" title="Pending violations awaiting resolution">
                      <h4 id="pendingTxt"></h4>
                      <p class="mb-0">Pending Violations</p>
                    </div>
                  </div>
                  <!-- Resolved Violations Card -->
                  <div class="col-md-4">
                    <div class="info-card shadow rounded bg-success text-white"
                      data-bs-toggle="tooltip" title="Resolved issues that have been addressed">
                      <h4 id="resolvedTxt"></h4>
                      <p class="mb-0">Resolved Violations</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-end mt-3">
                <a href="../violation/" class="btn btn-warning">
                  <i class="bi bi-arrow-right-circle"></i> Manage Violations
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <?php include '../../includes/footer.php'; ?>
  <?php include '../../includes/theme.php'; ?>

  <script>
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

    // Enable tooltips after page load
    document.addEventListener("DOMContentLoaded", function() {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      fetchUser();
      fetchNotifications();
      fetchStatusCount();

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

      notificationList.innerHTML = "";

      notifications.forEach(notification => {
        const notificationItem = document.createElement('li');
        notificationItem.classList.add("list-group-item", "position-relative");

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

    function fetchStatusCount() {
      fetch('../actions/violation_action.php')
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            throw new Error(data.error || "Failed to fetch status count.");
          }

          let pendingCount = document.getElementById('pendingTxt');
          let criticalCount = document.getElementById('criticalTxt');
          let resolveCount = document.getElementById('resolvedTxt');

          pendingCount.innerHTML = `<i class="bi bi-x-circle"></i> ${data.count.Pending}`;
          criticalCount.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${data.count.Critical}`;
          resolveCount.innerHTML = `<i class="bi bi-check-circle"></i> ${data.count.Resolved}`;
        })
    }
  </script>

</body>

</html>