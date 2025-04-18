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

<?php
require_once '../../includes/session.php';
?>


<body class="body light">

  <?php include '../../includes/nav.php'; ?>

  <div class="content-wrapper">
    <div class="container-fluid">

      <!-- Banner Section -->
      <div class="payment-banner d-flex justify-content-between align-items-center px-5 py-4 shadow">
        <div class="banner-text">
          <h4 class="text-white">Stall Payment</h4>
          <p class="text-white mt-3">Don't wait until the last minute! <br>Upload your payment receipt for verification today to avoid any violations. Be sure to submit it before the due date.</p>

        </div>
        <button class="btn btn-primary submit-receipt-btn" data-bs-toggle="modal" data-bs-target="#submitReceiptModal">Submit Receipt</button>
      </div>

      <div class="row m-5 py-5 px-3 mx-auto shadow rounded-4 w-75 table-container">
        <div class="col-md-12">
          <div class="stall-card">
            <h3 id="title">Stalls</h3>
            <h5 id="stall_message"></h5>

            <table class="table table-striped table-borderless table-hover">
              <thead>
                <tr>
                  <th><strong>Market</strong></th>
                  <th><strong>Section</strong></th>
                  <th><strong>Stall No.</strong></th>
                  <th><strong>Stall Size</strong></th>
                  <th><strong>Rental Fee</strong></th>
                  <th><strong>Rent Expiration Date</strong></th>
                  <th><strong>Extension</strong></th>
                  <th><strong>Helper</strong></th>
                </tr>
              </thead>
              <tbody id="stallsContainer">
                <!-- Dynamic stall rows will be inserted here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <hr>
      <div class="container mb-5">

        <div class="mt-5 d-flex flex-wrap justify-content-center gap-4">

          <div class="card custom-card garbage-bg">
            <div class="card-body">
              <div class="text-start">
                <h5 class="card-title">Garbage Collection</h5>
              </div>
              <p class="card-text text-start">Request garbage collection for the market.</p>
              <button class="btn btn-sm btn-dark w-50" id="requestCollectionBtn" data-bs-toggle="modal" data-bs-target="#garbageRequestModal">
                Request
              </button>
            </div>
          </div>

          <div class="card custom-card extension-bg">
            <div class="card-body">
              <div class="text-start">
                <h5 class="card-title">Stall Extension</h5>
              </div>
              <p class="card-text text-start">Apply for a stall extension easily.</p>
              <a href="/lgu_market_sys/pages/stall_extend" class="btn btn-sm btn-dark w-50">Apply Now</a>
            </div>
          </div>

          <div class="card custom-card helper-bg">
            <div class="card-body">
              <div class="text-start">
                <h5 class="card-title">Add a Helper</h5>
              </div>
              <p class="card-text text-start">Register a helper for your stall.</p>
              <a href="/lgu_market_sys/pages/helper_app" class="btn btn-sm btn-dark w-50">Register</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Submit Receipt Modal -->
  <div class="modal fade" id="submitReceiptModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="submitReceiptModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-container">
            <div class="d-flex align-items-center justify-content-between">
              <h4 class="modal-title fw-bold" id="submitReceiptModalLabel">Upload Reciept</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="text-muted me-5">
              Upload your payment receipt for verification and to avoid violations.
            </p>
            <hr class="mb-4">

            <form id="submitRecieptForm" enctype="multipart/form-data">

              <table class="table table-borderless table-hover">
                <thead>
                  <tr>
                    <th><strong>Select</strong></th>
                    <th><strong>Stall No.</strong></th>
                    <th><strong>Market</strong></th>
                    <th><strong>Section</strong></th>
                    <th><strong>Rental Fee</strong></th>
                    <th><strong>Payment Status</strong></th>
                    <th><strong>Expiration Date</strong></th>
                  </tr>
                </thead>
                <tbody id="recieptStallsContainer">

                </tbody>
              </table>
              <h5 id="receipt_stall_message"></h5>
              <div class="container text-start w-50 m-0">

                <div class="mb-3">
                  <label for="receiptFile" class="form-label">Upload Reciept (PDF or Image)</label>
                  <input type="file" class="form-control" id="receiptFile" name="receipt_file" accept=".pdf, image/*">
                </div>

                <label for="receiptFile" class="form-label">Paid Amount</label>
                <div class="input-group mb-3">
                  <span class="input-group-text">₱</span>
                  <input type="number" class="form-control" id="paidAmount" name="paid_amount" aria-label="Amount (to the nearest dollar)">
                </div>
                <input type="hidden" id="sourceType" name="source_type" value="stall">
              </div>

              <button class="btn btn-dark mt-3" id="uploadPaymentReceipt" type="button">Upload</button>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Garbage Collection Request Modal -->
  <div class="modal fade" id="garbageRequestModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="garbageRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-container">
            <div class="d-flex align-items-center justify-content-between">
              <h4 class="modal-title fw-bold" id="garbageRequestModalLabel">Request Garbage Collection</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="text-muted me-5">
              Submit a request for garbage collection in the market area. Once the request count reaches the required threshold, it will be processed.
            </p>
            <hr class="mb-4">

            <form id="garbageRequestForm">
              <div class="mb-3">
                <label for="marketName" class="form-label">Market:</label>
                <select class="form-select" id="market" required>
                  <option value="" disabled selected>-- Select Market --</option>
                </select>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary" id="requestCollectionBtn" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="The garbage collection request will be submitted when the request count reached 20 request.">Submit Request</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../../includes/footer.php'; ?>
  <?php include '../../includes/theme.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // Fetch and populate stalls data
      fetchStallsData();
      populateSubmitReceiptTable()
      fetchMarkets();

      const uploadButton = document.getElementById('uploadPaymentReceipt');

      uploadButton.addEventListener('click', function() {
        const selectedStall = document.querySelector('input[name="selected_stall_id"]:checked');
        const fileInput = document.getElementById('receiptFile');
        const paid_amount = document.getElementById('paidAmount').value;

        if (validateReceiptForm(selectedStall, fileInput, paid_amount)) {
          submitPayment();
        }
      });

      // Event listener for garbage request form submission
      const form = document.getElementById("garbageRequestForm");
      form.addEventListener("submit", handleGarbageRequestSubmission);

      function fetchStallsData() {
        fetch('../actions/profile_action.php')
          .then(response => response.json())
          .then(data => {
            console.log(data);
            populateViewStallsTable(data.stalls);
          })
          .catch(error => {
            console.error('Error fetching data:', error);
          });
      }
    });

    function submitPayment() {
      const formData = new FormData(document.getElementById('submitRecieptForm'));
      fetch('../actions/submit_stall_receipt.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            alert('Payment successfully submitted!');
            location.reload();
          } else {
            alert('Error: ' + data.message); // Display the error message if not successful
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while submitting the payment.'); // Display a generic error if the fetch fails
        });

    }

    // Validate Receipt Form
    function validateReceiptForm(stall, fileInput, paid_amount) {
      let valid = true;

      // Validate file input
      if (!fileInput.files.length || !stall || !paid_amount) {
        alert("Please complete the form.");
        valid = false;
      } else {
        const file = fileInput.files[0];
        const allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.webp)$/i; // Added more image formats
        if (!allowedExtensions.exec(file.name)) {
          alert("Invalid file type. Only PDF or image files are allowed.");
          valid = false;
        }

        return valid;
      }
    }

    function populateViewStallsTable(stalls) {
      const stallsContainer = document.getElementById('stallsContainer');
      stallsContainer.innerHTML = ''; // Clear the table body

      if (!stalls || stalls.length === 0) {
        document.getElementById('stall_message').textContent = 'No stalls available.';
      } else {
        stalls.forEach(stall => {
          const row = document.createElement('tr');
          row.innerHTML = `
                                        <td>${stall.market_name}</td>
                                        <td>${stall.section_name}</td>
                                        <td>${stall.stall_number}</td>
                                        <td>${stall.stall_size}</td>
                                        <td>${stall.rental_fee}</td>
                                        <td class="${new Date(stall.expiration_date) < new Date() ? 'text-danger' : 'text-success'}">
                                          <strong>${stall.expiration_date ? new Date(stall.expiration_date).toLocaleDateString() : 'N/A'}</strong>
                                        </td>
                                        <td>${stall.extension_duration != null ? stall.extension_duration : 'N/A'}</td>
                                        <td>${stall.helper_name && stall.helper_name.trim() !== '' ? stall.helper_name : 'N/A'}</td>

                                    `;
          stallsContainer.appendChild(row);
        });

      }
    }


    // <td class="${
    //                                     new Date(stall.expiration_date) < new Date()
    //                                 ? 'text-danger'
    //                                            : (new Date(stall.expiration_date) <= new Date(Date.now() + 3 * 24 * 60 * 60 * 1000)
    //                                              ? 'text-success'
    //                                             : '')
    //         }">   

    function populateSubmitReceiptTable() {
      fetch('../actions/get_unpaid_stalls.php')
        .then(response => response.json())
        .then(data => {
          const recieptStallsContainer = document.getElementById('recieptStallsContainer');
          recieptStallsContainer.innerHTML = '';

          // Check if response is valid
          if (!data.success || !data.unpaid_stalls || data.unpaid_stalls.length === 0) {
            document.getElementById('receipt_stall_message').textContent = 'No stalls available.';
            return;
          }

          data.unpaid_stalls.forEach(stall => {
            const row = document.createElement('tr');
            console.log(stall)
            row.innerHTML = `
                  <td>
                       <label class="radio-modern">
                           <input type="radio" name="selected_stall_id" value="${stall.id}">
                           <span class="radio-checkmark"></span>
                       </label>
                   </td>
                   <td>${stall.stall_number}</td>
                   <td>${stall.market_name || 'N/A'}</td>
                   <td>${stall.section_name || 'N/A'}</td>
                  <td>${stall.rental_fee || 'N/A'}</td>
                  <td class="${
                    stall.payment_status === 'Overdue' ? 'text-secondary' :
                    stall.payment_status === 'Unpaid' ? 'text-danger' :
                    stall.payment_status === 'Payment_Period' ? 'text-success' :
                    '' }">
                       <strong>${stall.payment_status || 'N/A'}</strong> 
                      </td>
                                <td>${stall.expiration_date ? stall.expiration_date.split(' ')[0] : 'N/A'}</td>
                            `;
            recieptStallsContainer.appendChild(row);
            console.log(stall.expiration_date)

            // Click to select radio button
            row.addEventListener("click", () => {
              const radio = row.querySelector('input[name="selected_stall_id"]');
              if (radio) {
                radio.checked = true;
              }
            });
          });
        })
        .catch(error => {
          console.error('Error fetching data:', error);
          document.getElementById('stall_message').textContent = 'Error loading stalls.';
        });
    }

    function handleGarbageRequestSubmission(event) {
      event.preventDefault(); // Prevent default form submission

      const form = document.getElementById("garbageRequestForm");
      const formData = new FormData(form);
      const marketSelect = document.getElementById("market");

      if (!marketSelect.value) {
        alert("Please select a market.");
        return;
      }

      formData.append("market_id", marketSelect.value); // Append market value

      // Log FormData
      console.log("Logging FormData before submission:");
      for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
      }

      // Send the request
      fetch("../actions/add_and_submit_garbage_request.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert("Garbage request submitted successfully!");
            form.reset();
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An error occurred. Please try again.");
        });
    }

    function fetchMarkets() {
      fetch('../actions/get_market.php')
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then(data => {
          console.log('Received data:', data);
          const select = document.getElementById('market');
          select.innerHTML = '';

          data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.market_name;
            select.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Fetch error:', error);
        });
    }
  </script>
  <script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
</body>

</html>