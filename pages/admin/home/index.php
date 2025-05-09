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

                <div class="mb-3 form-group shadow-lg">
                  <select class="form-select" id="market">
                    <option value="" disabled selected>Select Market</option>
                  </select>
                </div>

                <div class="mb-3 ms-3 form-group shadow-lg">
                  <select class="form-select" id="market" required>
                    <option value="">Daily</option>
                    <option value="">Monthly</option>
                    <option value="">Annual</option>
                  </select>
                </div>

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
            <span id="violationBadge" class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">
              New
              <span class="visually-hidden">unread messages</span>
            </span>
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
            <span id="inspectionBadge" class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">
              New
              <span class="visually-hidden">unread messages</span>
            </span>
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
            <span id="vendorAppBadge" class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">
              New <span class="visually-hidden">unread messages</span>
            </span>
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
            <span id="paymentBadge" class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">
              New
              <span class="visually-hidden">unread messages</span>
            </span>
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
            <span id="marketAppBadge" class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger">
              New
              <span class="visually-hidden">unread messages</span>
            </span>
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

    <hr>

    <section class="container my-5">

      <div class="d-flex justify-content-between align-items-center">
        <div class="text-start mb-4">
          <h4 class="fw-bold">Reports Generation</h4>
          <p class="text-muted">Generate detailed reports to monitor and optimize market operations effectively.</p>
        </div>
      </div>

      <div class="card p-4 mb-4">
        <form id="reportGenerationForm">
          <div class="row g-3">
            <div class="col-md-6">

              <div class="mb-3 form-group">
                <label class="form-label fw-bold">Select Market</label>
                <select class="form-select" id="marketForReports" name="marketId">
                  <!-- Dynamically Added -->
                </select>
              </div>

              <div class="mb-4">
                <label class="form-label fw-bold">Select Report Category</label>
                <div class="d-flex flex-wrap gap-2">

                  <input type="radio" class="btn-check" name="reportCategory" id="stallVendor" value="Stall & Vendor Reports" autocomplete="off" checked>
                  <label class="btn btn-outline-pill" for="stallVendor">
                    Stall & Vendor Reports
                  </label>

                  <!-- <input type="radio" class="btn-check" name="reportCategory" id="paymentFinancial" autocomplete="off">
                  <label class="btn btn-outline-pill" for="paymentFinancial">
                    Payment & Financial Reports
                  </label> -->

                  <input type="radio" class="btn-check" name="reportCategory" id="violationEnforcement" autocomplete="off">
                  <label class="btn btn-outline-pill" for="violationEnforcement">
                    Violation & Enforcement Reports
                  </label>

                  <input type="radio" class="btn-check" name="reportCategory" id="operational" autocomplete="off">
                  <label class="btn btn-outline-pill" for="operational">
                    Operational Reports
                  </label>

                  <input type="radio" class="btn-check" name="reportCategory" id="feedback" autocomplete="off">
                  <label class="btn btn-outline-pill" for="feedback">
                    Feedback Reports
                  </label>

                </div>
              </div>


              <label for="reportType" class="form-label fw-bold">Select Report Type</label>
              <select class="form-select" id="reporOptionsContainer" name="reportType">
                <!-- Dynamically Populated -->
              </select>


            </div>
            <div class="col-md-6">
              <div class="d-flex flex-column w-100 h-100">

                <!-- Report Title Input -->
                <div>
                  <label for="reportTitle" class="form-label fw-bold">Report Title</label>
                  <input type="text" id="reportTitle" class="form-control" name="reportTitle" placeholder="ex. Market Stall Occupancy Report">
                </div>

                <label for="dateRange" class="form-label my-3 fw-bold">Select Date Range (Start - End)</label>
                <div class="d-flex align-items-center">
                  <input type="date" class="form-control" name="startDate" id="startDate">
                  <i class="bi bi-arrow-right mx-3 fw-bold"></i>
                  <input type="date" class="form-control" name="endDate" id="endDate">
                </div>

                <!-- Generate Button -->
                <hr>
                <div class="d-flex justify-content-center mt-3">
                  <button type="submit" class="btn btn-success w-75 mt-auto" id="generateReportBtn">
                    <i class="bi bi-bar-chart"></i> Generate Report
                  </button>
                </div>

              </div>
            </div>
          </div>
        </form>
      </div>


      <!-- Report Output -->
      <div id="reportOutput" class="p-4 d-none">
        <h5 class="fw-bold mb-3" id="reportTitleTable">Report Title</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="reportTable">
            <!-- Dynamically populated rows -->
          </table>
        </div>
        <div class="mt-3 text-end">
          <button class="btn btn-success" id="exportReportAsExcelBtn"><i class="bi bi-file-earmark-excel"></i> Export as Excel</button>
          <button class="btn btn-danger" id="exportReportAsPdfBtn"><i class="bi bi-file-earmark-pdf"></i> Export as PDF</button>
        </div>
      </div>


    </section>

    <hr>

    <section class="container my-5">

      <div class="d-flex justify-content-between align-items-center">
        <div class="text-start mb-4">
          <h4 class="fw-bold">Suspended and Terminated Accounts</h4>
          <p class="text-muted">Manage market operations efficiently with these tools.</p>
        </div>
        <div class="text-start mb-4">
          <button class="btn btn-dark" id="exportSuspended&TerminatedUsers">Export</button>
        </div>
      </div>

      <div class="table-responsive rounded">
        <table class="table table-hover align-middle shadow-sm px-5">
          <thead class="table-dark text-center">
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Status</th>
              <th>Violations & Issuance Dates</th>
              <th>Termination Date</th>
              <th>Market</th>
              <th>Stall Number</th>
            </tr>
          </thead>
          <tbody class="text-center">

            <tr>
              <!-- Dynamically Added Content -->
            </tr>
            <tr>
              <!-- Dynamically Added Content -->
            </tr>
          </tbody>
        </table>
        <div id="violatorMessage"></div>
      </div>
    </section>

    <hr>

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
  <?php
  $user_type = $_SESSION['user_type'] ?? 'default';
  ?>

  <!-- Reports -->
  <script>
    let reportsExport = [];
    let reportFileName;
    const reportForm = document.getElementById("reportGenerationForm");

    reportForm.addEventListener("submit", function(e) {
      e.preventDefault();

      const startDate = document.getElementById("startDate").value.trim();
      const endDate = document.getElementById("endDate").value.trim();
      const reportTitle = document.getElementById("reportTitle").value.trim();
      const reportType = document.getElementById('reporOptionsContainer').value;

      const exportPDFBtn = document.getElementById('exportReportAsPdfBtn');
      const exportCSVBtn = document.getElementById('exportReportAsExcelBtn');
      exportPDFBtn.dataset.report_type = toCamelCase(reportType);
      exportPDFBtn.dataset.report_title = reportTitle;
      exportCSVBtn.dataset.report_type = toCamelCase(reportType);
      exportCSVBtn.dataset.report_title = reportTitle;

      if (startDate === "" || endDate === "" || reportTitle === "") {
        alert("Please, complete the form.");
        exit();
      }


      const formData = new FormData(reportForm);

      fetch("../../actions/generate_report.php", {
          method: "POST",
          body: formData,
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {

            console.log("Data REPORTS: ", data.reports)

            const report = data.reports;
            console.log("FORMAT: ", [report])

            reportsExport = [report];
            reportFileName = report.rows[0].title;

            console.log("REPORT TITLE: ", report.rows[0].title);

            populateReport(report, reportType);
            alert("✅ " + data.message);

          } else {
            alert("❌ " + data.message);
          }
        })
        .catch(err => {
          console.error("Request failed", err);
        });

    });

    function populateReport(response, reportType) {
      console.log("RESPONSE: ", response);

      const headers = response.headers;
      const reportData = response.rows;

      console.log("Headers:", headers);
      console.log("First row of data:", reportData);

      const reportTable = document.getElementById('reportTable');
      const reportTitle = document.getElementById('reportTitleTable');

      // Clear existing content
      reportTable.innerHTML = '';
      reportTitle.textContent = reportData[0].title;

      let statusHeader = [];
      let statusHeaderKeys = [];

      switch (reportType) {
        case 'Stall Utilization Report':
          statusHeader = ['Title', 'Market Name', 'Total Stalls', 'Occupied Stalls', 'Occupancy Rate', 'Availability Rate'];
          statusHeaderKeys = ['title', 'market_name', 'total_stalls', 'occupied_stalls', 'occupancy_rate', 'availability_rate'];
          break;
        case 'Vendor Master List Report':
          statusHeader = ['Title', 'Market Name', 'Total Vendors'];
          statusHeaderKeys = ['title', 'market_name', 'total_vendors'];
          break;
        case 'Violation Summary Report':
          statusHeader = ['Title', 'Market Name', 'Violation Count', 'Pending Count', 'Resolved Count', 'Escalated Count', 'Vendor Count', 'Most Common Violation'];
          statusHeaderKeys = ['title', 'market_name', 'violation_count', 'pending_count', 'resolved_count', 'escalated_count', 'vendor_count', 'most_common_violation'];
          break;
        case 'Stall Transfer Requests Report':
          statusHeader = ['Title', 'Market Name', 'Total Transfer Requests', 'Pending Count', 'Approved Count', 'Rejected Count'];
          statusHeaderKeys = ['title', 'market_name', 'total_transfer_requests', 'pending_count', 'approved_count', 'rejected_count'];
          break;
        case 'Stall Extension Requests Report':
          statusHeader = ['Title', 'Market Name', 'Total Extension Requests', 'Pending Count', 'Approved Count', 'Rejected Count'];
          statusHeaderKeys = ['title', 'market_name', 'total_extension_requests', 'pending_count', 'approved_count', 'rejected_count'];
          break;

      }

      console.log("REPOSRT TYPE INSIDE THE FUNCTION: ", reportType)

      // Build status header
      const statusRow = document.createElement('tr');
      statusRow.innerHTML = headers.map(header => {
        if (statusHeader.includes(header)) {
          return `<th>${header}</th>`;
        }
        return '';
      }).join('');
      reportTable.appendChild(statusRow);

      const row = reportData[0]; // Only process the first (and only) row
      const dataRowStats = document.createElement('tr');
      dataRowStats.innerHTML = headers.map(header => {
        const key = convertHeaderToKey(header);
        console.log("KEY: ", key);
        if (statusHeaderKeys.includes(key)) {
          return `<td>${row[key] ?? ''}</td>`;
        }
        return '';
      }).join('');
      reportTable.appendChild(dataRowStats);

      // Build table header
      const headerRow = document.createElement('tr');
      headerRow.innerHTML = headers.map(header => {
        if (statusHeader.includes(header)) {
          return '';
        }
        return `<th>${header}</th>`;
      }).join('');
      reportTable.appendChild(headerRow);

      // Build data rows
      reportData.forEach(row => {
        const dataRow = document.createElement('tr');
        dataRow.innerHTML = headers.map(header => {
          const key = convertHeaderToKey(header);
          if (statusHeaderKeys.includes(key)) {
            return '';
          }
          return `<td>${row[key] ?? ''}</td>`;
        }).join('');
        reportTable.appendChild(dataRow);
      });

      // Show report section
      document.getElementById('reportOutput').classList.remove('d-none');
    }


    // Function to dynamically show report options based on selected category
    const optionsData = {
      "stallVendor": [
        "Stall Utilization Report",
        "Vendor Master List Report"
      ],
      "violationEnforcement": [
        "Violation Summary Report"
      ],
      "operational": [
        "Stall Transfer Requests Report",
        "Stall Extension Requests Report",
      ],
    };

    showOptions();

    // Initialize the report options when a radio button is selected
    document.querySelectorAll('input[name="reportCategory"]').forEach(radio => {
      radio.addEventListener("change", showOptions);
    });

    // Function to update report options based on selected report category
    function showOptions() {
      const selectedCategory = document.querySelector('input[name="reportCategory"]:checked')?.id;
      const optionsContainer = document.getElementById("reporOptionsContainer");

      // Clear previous options
      optionsContainer.innerHTML = "";

      if (selectedCategory) {
        const optionsList = optionsData[selectedCategory];
        if (optionsList) {
          const ul = document.createElement("ul");

          optionsList.forEach(option => {
            const optionEl = document.createElement("option");
            optionEl.textContent = option;
            optionEl.value = option;
            ul.appendChild(optionEl);
          });

          optionsContainer.appendChild(ul);
        }
      }
    }
    // Utility: Convert header label to object key
    function convertHeaderToKey(header) {
      return header
        .toLowerCase()
        .replace(/[^a-z0-9 ]/g, '') // remove punctuation
        .trim()
        .replace(/\s+/g, '_'); // replace spaces with _
    }
  </script>


  <!-- Export As PDF -->
  <script>
    const exportPDFBtn = document.getElementById('exportReportAsPdfBtn');
    exportPDFBtn.addEventListener('click', () => {
      console.log("REPORT EXPORT: ", reportsExport[0])
      console.log("REPORT FILENAME: ", reportFileName)
      const postKey = exportPDFBtn.dataset.report_type;
      const title = exportPDFBtn.dataset.report_title;

      exportPDF({
        dataArray: reportsExport[0],
        filename: reportFileName + '.pdf',
        postKey: postKey,
        title: title
      });
    });

    function exportPDF({
      dataArray,
      filename,
      postKey,
      title
    }) {

      console.log('ARRAY DATA from the PDF Function: ', dataArray);
      // Convert array to JSON string and prepare form data
      const formData = new URLSearchParams();
      formData.append('data', JSON.stringify(dataArray));
      formData.append('postKey', postKey);
      formData.append('title', title);

      fetch('../../actions/create_pdf.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: formData.toString()
        })
        .then(response => {
          if (!response.ok) throw new Error('PDF generation failed');
          return response.blob();
        })
        .then(blob => {
          const blobUrl = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = blobUrl;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          a.remove();
          window.URL.revokeObjectURL(blobUrl);
        })
        .catch(error => {
          alert('Error: ' + error.message);
        });
    }
  </script>

  <!-- Export As CSV -->
  <script>
    const exportCSVBtn = document.getElementById('exportReportAsExcelBtn')
    exportCSVBtn.addEventListener('click', () => {
      console.log("REPORT EXPORT: ", reportsExport[0])
      console.log("REPORT FILENAME: ", reportFileName)
      const postKey = exportCSVBtn.dataset.report_type;
      const title = exportCSVBtn.dataset.report_title;

      exportCSV({
        dataArray: reportsExport[0],
        filename: reportFileName + '.csv',
        postKey: postKey,
        title: title
      });
    });

    // Add event listener to the button
    let suspendedAndTerminatedUsers = [];
    document.getElementById('exportSuspended&TerminatedUsers').addEventListener('click', () => {
      exportCSV({
        dataArray: suspendedAndTerminatedUsers,
        filename: 'terminated_suspended_users.csv',
        postKey: 'violators'
      });
    });

    function exportCSV({
      dataArray,
      filename,
      postKey,
      title
    }) {

      if (!postKey) {
        console.error("Missing postKey");
        return;
      }

      const formData = new FormData();
      formData.append('data', JSON.stringify(dataArray));
      formData.append('postKey', postKey);
      formData.append('title', title);

      // Optionally log the formData to check its content (for debugging purposes)
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]); // Logs key-value pairs
      }

      for (let [key, value] of formData.entries()) {
        console.log(`\n\n${key}: `, value);
      }

      fetch('../../actions/create_csv.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          const contentType = response.headers.get("Content-Type");

          if (contentType && contentType.includes("text/csv")) {
            return response.blob();
          } else {
            return response.json();
          }
        })
        .then(data => {
          if (data instanceof Blob) {
            const link = document.createElement('a');
            const blobUrl = URL.createObjectURL(data);
            link.href = blobUrl;
            link.download = filename;
            link.click();
            URL.revokeObjectURL(blobUrl);
          } else {
            console.error(data.message || 'An unknown error occurred.');
          }
        })
        .catch(error => {
          console.error('Fetch error:', error);
        });
    }
  </script>

  <!-- SSE -->
  <script>
    // Connect to SSE server
    const eventSource = new EventSource('../../actions/sse_new_applications.php');

    eventSource.addEventListener('market_app_badge', function(e) {
      const toDisplay = (e.data === 'true');

      const cardBadge = document.getElementById('marketAppBadge');
      if (toDisplay) {
        cardBadge.classList.remove('d-none');
      } else {
        cardBadge.classList.add('d-none');
      }
    });

    eventSource.addEventListener('violation_badge', function(e) {
      const toDisplay = (e.data === 'true');

      const cardBadge = document.getElementById('violationBadge');
      if (toDisplay) {
        cardBadge.classList.remove('d-none');
      } else {
        cardBadge.classList.add('d-none');
      }
    });

    eventSource.addEventListener('vendor_app_badge', function(e) {
      const toDisplay = (e.data === 'true');

      const cardBadge = document.getElementById('vendorAppBadge');
      if (toDisplay) {
        cardBadge.classList.remove('d-none');
      } else {
        cardBadge.classList.add('d-none');
      }
    });

    eventSource.addEventListener('payment_badge', function(e) {
      const toDisplay = (e.data === 'true');

      const cardBadge = document.getElementById('paymentBadge');
      if (toDisplay) {
        cardBadge.classList.remove('d-none');
      } else {
        cardBadge.classList.add('d-none');
      }
    });

    eventSource.addEventListener('inspection_badge', function(e) {
      const toDisplay = (e.data === 'true');

      const cardBadge = document.getElementById('inspectionBadge');
      if (toDisplay) {
        cardBadge.classList.remove('d-none');
      } else {
        cardBadge.classList.add('d-none');
      }
    });
  </script>

  <!-- Post Announcement -->
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

      // console.log("FormData being sent:");
      // for (const [key, value] of formData.entries()) {
      //   console.log(`${key}: ${value}`);
      // }

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

  <!-- Fetch Markets -->
  <script>
    fetch('../../actions/get_market.php')
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(data => {

        const select = document.getElementById('market');
        const selectForReports = document.getElementById('marketForReports');
        select.innerHTML = '';
        selectForReports.innerHTML = '';

        data.forEach(item => {
          const option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.market_name;
          select.appendChild(option);
          selectForReports.appendChild(option);
        });
      })
      .catch(error => {
        console.error('Fetch error:', error);
      });

    function getUtilizationRate() {
      const marketselect = document.getElementById("")
      const url = `../../actions/get_market_utilization_rate.php?market_id${encodeURIComponent(marketId)}`

      fetch(url)
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then(data => {
          console.log('Received data:', data);

        })
        .catch(error => {
          console.error('Fetch error:', error);
        });
    }
  </script>


  <!-- Fetch Suspended & Terminated Accounts -->
  <script>
    fetch("../../actions/get_suspended_terminated_accounts.php")
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(json => {

        if (!json.success) throw new Error(json.message);
        if (json.success && json.message) {
          const message_container = document.getElementById("violatorMessage");
          message_container.innerHTML = json.message;
          return;
        }

        const data = json.data; // <-- the actual violators array

        const tableBody = document.querySelector("tbody");
        tableBody.innerHTML = '';

        data.forEach(item => {
          suspendedAndTerminatedUsers.push(item);
          const row = document.createElement("tr");
          row.innerHTML = `
        <td>${item.user_id}</td>
        <td>${item.name}</td>
        <td class="status-${item.status.toLowerCase()}">${item.status}</td>
        <td>${item.reason}</td>
        <td>${item.date}</td>
        <td>${item.market}</td>
        <td>${item.stall_number}</td>
      `;
          tableBody.appendChild(row);
        });
      })
      .catch(error => {
        console.error('Fetched error:', error.message);
      });
  </script>

  <!-- Chart.js Configuration -->
  <script>
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Oct 23", "Oct 27", "Oct 31", "Nov", "Nov 08", "Nov 12", "Nov 16"],
        datasets: [{
          label: 'Performance',
          data: [45, 50, 60, 80, 70, 90, 85],
          borderColor: '#007bff',
          backgroundColor: 'rgba(0, 123, 255, 0.18)',
          borderWidth: 3,
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

  <script>
    function toCamelCase(text) {
      return text
        .replace(/(?:^\w|[A-Z]|\b\w|\s+|_)/g, (match, index) =>
          index === 0 ? match.toLowerCase() : match.toUpperCase()
        )
        .replace(/\s+/g, '')
        .replace(/_/g, '');
    }
  </script>

  <script>
    const user_type = "<?php echo $user_type; ?>";

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

        if (title === "Inspection Management") {

          const icon = card.querySelector("i.fs-2");
          if (icon) {
            icon.classList.remove("text-danger", "text-primary", "text-success", "text-warning", "text-indigo", "text-orange");
            icon.classList.add("text-secondary");
          }

          const inspectionBadge = document.getElementById("inspectionBadge");
          if (inspectionBadge) {
            inspectionBadge.classList.remove("bg-danger");
            inspectionBadge.classList.add("bg-secondary");
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
</body>

</html>