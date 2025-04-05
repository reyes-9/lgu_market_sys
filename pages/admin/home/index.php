<?php

require_once '../../../includes/session.php';

if ($_SESSION['user_type'] !== 'Admin' && $_SESSION['user_type'] !== 'Inspector') {
  echo '<script>
   alert("Please log in to continue.");
   window.location.href = "/lgu_market_sys/pages/login/index.php";
  </script>';
  exit;
}

?>


<!-- NEEDS TO CMOPLETE: 

                  MANAGE EXPIRED RECORDS
                  EXIPRED NOTIFICAIONS
                  STALL EXTENSION AND VIOLATION PAYMENTS (RECEIPT, THERE IS A BACKEND FOR THAT) 

                  -->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Public Market Monitoring System</title>
  <link rel="icon" type="image/png" href="../logo.png">
  <link rel="stylesheet" href="../../../assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

  <?php include '../../../includes/nav.php'; ?>

  <div class="text-start m-3 p-3 title d-flex align-items-center">
    <div class="icon-box me-3 shadow title-icon">
      <i class="bi bi-bar-chart-line-fill"></i>
    </div>
    <div>
      <h4 class="m-0">Admin - Dashboard</h4>
      <p class="text-muted mb-0">Monitor market activity, manage vendors, and track key performance metrics.</p>
    </div>
    <div class="ms-auto me-5">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item acitve" aria-current="page">Dashboard</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="container mt-4">
    <section class="my-5">
      <div class="text-start mb-4">
        <h4 class="fw-bold">Market Performance Overview</h4>
        <p class="text-muted">Track market metrics, vendor registrations, utilization trends, violations, and customer satisfaction for better management.</p>
      </div>
      <div class="row">
        <!-- Analytics Chart -->
        <div class="col-lg-8">
          <div class="card p-3">
            <div class="head d-flex justify-content-between align-items-center p-3">
              <h5 class="fw-bold">Market Utilization Trend</h5>

              <div class="btn-group">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Select Market
                </button>
                <ul class="dropdown-menu">
                  ...
                </ul>
              </div>
            </div>

            <canvas id="analyticsChart"></canvas>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-lg-4 d-flex flex-column gap-5">
          <div class="stats-card bg-white d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-primary fw-bold">New Vendors Registered</h6>
              <h5 class="fw-bold">1,563</h5>
              <p class="text-muted">May 23 - June 01 (2017)</p>
            </div>
            <div class="icon-box bg-primary"><i class="bi bi-person-plus-fill"></i></div>
          </div>

          <div class="stats-card bg-white d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-success fw-bold">Total Users</h6>
              <h5 class="fw-bold">30,564</h5>
              <p class="text-muted">May 23 - June 01 (2017)</p>
            </div>
            <div class="icon-box bg-success"><i class="bi bi-people-fill"></i></div>
          </div>

          <div class="stats-card bg-white d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-warning fw-bold">Total Registered Vendors</h6>
              <h5 class="fw-bold">20,564</h5>
              <p class="text-muted">May 23 - June 01 (2017)</p>
            </div>
            <div class="icon-box bg-warning"><i class="bi bi-shop"></i></div>
          </div>
        </div>
      </div>

      <!-- Task Stats -->
      <div class="row my-5">
        <div class="col-md-6">
          <div class="card p-3 text-center">
            <h6 class="text-secondary fw-bold">Violation Rate</h6>
            <h5 class="fw-bold">532 <span class="text-success">+1.69%</span></h5>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card p-3 text-center">
            <h6 class="text-secondary fw-bold">Customer Satisfaction Score</h6>
            <h5 class="fw-bold">4,569 <span class="text-danger">-0.5%</span></h5>
          </div>
        </div>
      </div>
    </section>

    <hr>

    <section class="container my-5">
      <div class="text-start mb-4">
        <h4 class="fw-bold">Market Management Tools</h4>
        <p class="text-muted">Manage market operations efficiently with these tools.</p>
      </div>

      <div class="row g-4 d-flex justify-content-center">
        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-exclamation-triangle text-danger fs-2 mb-3"></i>
              <h5 class="card-title-home fw-bold">Stall Violations</h5>
              <p class="card-text text-muted">Report and manage violations found during stall inspections.</p>
              <a class="btn btn-danger rounded-pill px-4" id="addViolationBtn" href="http://localhost/lgu_market_sys/pages/admin/violation/">
                <i class="bi bi-clipboard-plus"></i> Manage Violation
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-megaphone text-primary fs-2 mb-3"></i>
              <h5 class="card-title-home fw-bold">Market Announcements</h5>
              <p class="card-text text-muted">Post important updates for vendors and customers.</p>
              <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#announcementModal">
                <i class="bi bi-pencil-square"></i> Post Announcement
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-clipboard-check text-success fs-2 mb-3"></i>
              <h5 class="card-title-home fw-bold">Inspection Management</h5>
              <p class="card-text text-muted">Manage assigned inspections, update statuses, and mark them as completed or canceled.</p>
              <a class="btn btn-success rounded-pill px-4" id="manageInspectionBtn" href="http://localhost/lgu_market_sys/pages/admin/inspection/">
                <i class="bi bi-clipboard-check"></i> Manage Inspections
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-file-earmark-text text-orange fs-2 mb-3"></i>
              <h5 class="card-title-home fw-bold">Vendor Applications</h5>
              <p class="card-text text-muted">Manage vendor applications in one click.</p>
              <a class="btn btn-orange rounded-pill px-4" id="vendorAppBtn" href="http://localhost/lgu_market_sys/pages/admin/vendor/">
                <i class="bi bi-file-earmark-text"></i> Manage Applications
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-wallet fs-2 mb-3 text-indigo" id="paymentIcon"></i>
              <h5 class="card-title-home fw-bold">Payment Management</h5>
              <p class="card-text text-muted">Manage and track receipt uploads.</p>
              <a class="btn rounded-pill px-4 btn-indigo" id="managePaymentBtn" href="http://localhost/lgu_market_sys/pages/admin/payment/">
                <i class="bi bi-wallet"></i> Manage Payments
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 d-flex">
          <div class="card border-0 shadow-sm p-3 text-center modern-card w-100">
            <div class="card-body d-flex flex-column justify-content-between">
              <i class="bi bi-file-earmark-text text-warning fs-2 mb-3"></i>
              <h5 class="card-title-home fw-bold">Market Applications</h5>
              <p class="card-text text-muted">Manage stall and more applications in one click.</p>
              <a class="btn btn-warning rounded-pill px-4" id="manageAppBtn" href="http://localhost/lgu_market_sys/pages/admin/table/">
                <i class="bi bi-file-earmark-text"></i> Manage Applications
              </a>
            </div>
          </div>
        </div>

      </div>

    </section>

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="modal-container">
              <div class="d-flex align-items-center justify-content-between">
                <h4 class="modal-title fw-bold" id="announcementModalLabel">New Announcement</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <p class="text-muted me-5">
                Announcements inform vendors and customers about important market updates, events, and policies.
              </p>
              <hr class="mb-4">

              <form id="announcementForm">
                <div class="mb-3">
                  <label for="announcementTitle" class="form-label">Title</label>
                  <input type="text" class="form-control" id="announcementTitle" required>
                </div>

                <div class="mb-3">
                  <label for="announcementMessage" class="form-label">Message</label>
                  <textarea class="form-control" id="announcementMessage" rows="4" required></textarea>
                </div>

                <!-- Target Audience Dropdown -->
                <div class="mb-3">
                  <label for="announcementAudience" class="form-label">Target Audience</label>
                  <select class="form-select" id="announcementAudience" required>
                    <option value="all" selected>All Users</option>
                    <option value="vendors">Vendors</option>
                    <option value="admins">Admins</option>
                  </select>
                </div>

                <!-- Start Date and Expiry Date Inputs -->
                <div class="mb-3">
                  <label for="announcementStartDate" class="form-label">Start Date</label>
                  <input type="datetime-local" class="form-control" id="announcementStartDate" required>
                </div>

                <div class="mb-3">
                  <label for="announcementExpiryDate" class="form-label">Expiry Date</label>
                  <input type="datetime-local" class="form-control" id="announcementExpiryDate" required>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Post Announcement</button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <hr>

  <?php include '../../../includes/footer.php'; ?>
  <?php include '../../../includes/theme.php'; ?>
  <?php

  $user_type = $_SESSION['user_type'] ?? 'default';
  ?>
  <script>
    const user_type = "<?php echo $user_type; ?>";

    console.log(user_type);

    if (user_type === "Inspector") {
      document.querySelectorAll(".card-body").forEach((card) => {
        const title = card.querySelector(".card-title-home").textContent.trim();

        if (title !== "Inspection Management" && title !== "Stall Violations") {

          const icon = card.querySelector("i.fs-2");
          if (icon) {
            icon.classList.remove("text-danger", "text-primary", "text-success", "text-warning", "text-indigo", "text-orange");
            icon.classList.add("text-secondary");
          }

          const button = card.querySelector(".btn");
          if (button) {
            button.classList.remove("btn-danger", "btn-primary", "btn-success", "btn-warning", "btn-indigo", "btn-orange");
            button.classList.add("btn-secondary");
            button.disabled = true;
            button.style.pointerEvents = "none";
          }
        }
      });
    } else {
      document.querySelectorAll(".card-body").forEach((card) => {
        const title = card.querySelector(".card-title-home").textContent.trim();

        if (title === "Inspection Management" || title === "Stall Violations") {

          const icon = card.querySelector("i.fs-2");
          if (icon) {
            icon.classList.remove("text-danger", "text-primary", "text-success", "text-warning", "text-indigo", "text-orange");
            icon.classList.add("text-secondary");
          }

          const button = card.querySelector(".btn");
          if (button) {
            button.classList.remove("btn-danger", "btn-primary", "btn-success", "btn-warning", "btn-indigo", "btn-orange");
            button.classList.add("btn-secondary");
            button.disabled = true;
            button.style.pointerEvents = "none";
          }
        }
      });
    }
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const announcementForm = document.getElementById("announcementForm");

      announcementForm.addEventListener("submit", function(event) {
        event.preventDefault();
        postAnnouncement();
      });
    });

    function postAnnouncement() {
      const title = document.getElementById("announcementTitle").value.trim();
      const message = document.getElementById("announcementMessage").value.trim();
      const audience = document.getElementById("announcementAudience").value;
      const startDate = document.getElementById("announcementStartDate").value;
      const expiryDate = document.getElementById("announcementExpiryDate").value;

      if (title === "" || message === "" || startDate === "" || expiryDate === "") {
        alert("Please fill out all fields.");
        return;
      }

      // Prepare data for submission
      const formData = new URLSearchParams();
      formData.append("title", title);
      formData.append("message", message);
      formData.append("audience", audience);
      formData.append("start_date", startDate);
      formData.append("expiry_date", expiryDate);

      console.log("FormData being sent:");
      for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }

      // Send data to backend via AJAX
      fetch("../../actions/post_announcements.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: formData.toString(),
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert("Announcement posted successfully!");
            document.getElementById("announcementForm").reset();
            bootstrap.Modal.getInstance(document.getElementById("announcementModal")).hide(); // Close modal
          } else {
            alert("Failed to post announcement.");
          }
        })
        .catch(error => console.error("Error:", error));
    }
  </script>

  <script>
    // Chart.js Configuration
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Oct 23", "Oct 27", "Oct 31", "Nov", "Nov 08", "Nov 12", "Nov 16"],
        datasets: [{
          label: 'Performance',
          data: [45, 50, 60, 80, 70, 90, 85],
          borderColor: '#007bff',
          backgroundColor: 'rgba(0, 123, 255, 0.1)',
          borderWidth: 2,
          pointRadius: 4,
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>


</body>

</html>