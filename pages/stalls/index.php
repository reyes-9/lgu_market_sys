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
      <div class="payment-banner d-flex flex-column justify-content-center px-5 py-4 shadow">
        <div class="d-flex">
          <div class="banner-text">
            <h4 class="text-white">Stall Payment</h4>
            <p class="text-white mt-3">Don't wait until the last minute! <br>Upload your payment receipt for verification today to avoid any violations. Be sure to submit it before the due date.</p>
          </div>

          <button class="btn submit-receipt-btn my-auto" data-bs-toggle="modal" data-bs-target="#submitStallReceiptModal">Stall Receipt</button>
        </div>

        <hr>

        <div class="d-flex">
          <div class="banner-text">
            <h4 class="text-white">Stall Extension Payment</h4>
            <p class="text-white mt-3">Don't wait until the last minute! <br>Upload your payment receipt for verification today to avoid any violations. Be sure to submit it before the due date.</p>
          </div>
          <button class="btn submit-receipt-btn my-auto" data-bs-toggle="modal" data-bs-target="#submitExtensionReceiptModal">Extension Receipt</button>
        </div>

      </div>

      <div class="row m-5 p-5 mx-auto shadow rounded-4 w-75 table-container">
        <div class="col-md-12">
          <div class="stall-card">
            <h3 id="title">Stalls</h3>
            <h5 id="stall_message"></h5>

            <table class="table table-striped table-borderless table-hover mt-5">
              <thead>
                <tr>
                  <th>Market</th>
                  <th>Section</th>
                  <th>Stall No</th>
                  <th>Stall Size</th>
                  <th>Rental Fee</th>
                  <th>Rent Exp. Date</th>
                  <th>Extension Exp. Date</th>
                  <th>Helper</th>
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
      <!-- Cards -->
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

  <!-- Stall Receipt Modal -->
  <div class="modal fade" id="submitStallReceiptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="submitStallReceiptModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 100%; margin: 0;">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-container">
            <div class="d-flex align-items-center justify-content-between">
              <h4 class="modal-title fw-bold" id="submitStallReceiptModalLabel">Upload Stall Receipt</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="text-muted me-5">
              Upload your payment receipt for verification and to avoid violations.
            </p>
            <hr class="mb-4">

            <form id="submitStallRecieptForm" enctype="multipart/form-data">

              <table class="table table-borderless table-hover">
                <thead>
                  <tr>
                    <th>Select</th>
                    <th>Stall Number</th>
                    <th>Market</th>
                    <th>Section</th>
                    <th>Rental Fee</th>
                    <th>Payment Status</th>
                    <th>Expiration Date</th>
                  </tr>
                </thead>
                <tbody id="recieptStallsContainer">

                </tbody>
              </table>
              <h5 id="receipt_stall_message"></h5>
              <br>
              <div class="container text-start w-50 m-0">

                <div class="mb-3">
                  <label for="stallReceiptFile" class="form-label">Upload Reciept (PDF or Image)</label>
                  <input type="file" class="form-control" id="stallReceiptFile" name="stall_receipt_file" accept=".pdf, image/*">
                </div>

                <label for="stallReceiptFile" class="form-label">Paid Amount</label>
                <div class="input-group mb-3">
                  <span class="input-group-text">₱</span>
                  <input type="number" class="form-control" id="stallPaidAmount" name="stall_paid_amount" aria-label="Amount (to the nearest dollar)">
                </div>
                <input type="hidden" id="sourceType" name="source_type" value="stall">
              </div>

              <button class="btn btn-dark mt-3" id="uploadStallPaymentReceipt" type="button">Upload</button>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Extension Receipt Modal -->
  <div class="modal fade" id="submitExtensionReceiptModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="submitExtensionReceiptModal" aria-hidden="true">
    <div class="modal-dialog modal-xl w-100" style="max-width: 100%; margin: 0;">
      <div class="modal-content">
        <div class="modal-body">
          <div class="modal-container">
            <div class="d-flex align-items-center justify-content-between">
              <h4 class="modal-title fw-bold" id="submitExtensionReceiptModalLabel">Upload Extension Reciept</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <p class="text-muted me-5">
              Upload your payment receipt for verification and to avoid violations.
            </p>
            <hr class="mb-4">

            <form id="submitExtensionRecieptForm" enctype="multipart/form-data">
              <table class="table table-borderless table-hover">
                <thead>
                  <tr>
                    <th>Select</th>
                    <th>Stall Number</th>
                    <th>Market</th>
                    <th>Section</th>
                    <th>Payment Status</th>
                    <th>Extension Cost</th>
                    <th>Extension Duration</th>
                    <th>Expiration Date</th>
                  </tr>
                </thead>
                <tbody id="receiptExtensionContainer">

                </tbody>
              </table>
              <h5 id="receipt_extension_message"></h5>
              <br>
              <div class="container text-start w-50 m-0">

                <div class="mb-3">
                  <label for="extensionReceiptFile" class="form-label">Upload Reciept (PDF or Image)</label>
                  <input type="file" class="form-control" id="extensionReceiptFile" name="extension_receipt_file" accept=".pdf, image/*">
                </div>

                <label for="receiptFile" class="form-label">Paid Amount</label>
                <div class="input-group mb-3">
                  <span class="input-group-text">₱</span>
                  <input type="number" class="form-control" id="extensionPaidAmount" name="extension_paid_amount" aria-label="Amount (to the nearest peso)">
                </div>
                <input type="hidden" id="sourceType" name="source_type" value="extension">
              </div>

              <button class="btn btn-dark mt-3" id="uploadExtensionPaymentReceipt" type="button">Upload</button>

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
                <button type="button" class="btn btn-primary" id="requestCollectionBtn" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="The garbage collection request will be submitted when the request count reached 20 request.">Submit Request</button>
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
      populateSubmitStallReceiptTable();
      populateSubmitExtensionReceiptTable();
      fetchMarkets();

      // Stall
      const stallModal = document.getElementById('submitStallReceiptModal');
      stallModal.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault(); // Stops the form from submitting or closing the modal
        }
      });

      const uploadStallButton = document.getElementById('uploadStallPaymentReceipt');
      uploadStallButton.addEventListener('click', function() {
        const selectedStall = document.querySelector('input[name="selected_stall_id"]:checked');
        const selectedRow = selectedStall.closest('tr');
        const fileInput = document.getElementById('stallReceiptFile');
        const paid_amount = parseFloat(document.getElementById('stallPaidAmount').value);
        const rental_fee = parseFloat(selectedRow.querySelector('td:nth-child(5)').textContent.trim());

        if (!selectedStall) {
          alert("Please select a stall before uploading a receipt.");
          return;
        }


        if (validateReceiptForm(selectedStall, fileInput, paid_amount, rental_fee)) {
          const stall_form = document.getElementById('submitStallRecieptForm')
          submitPayment(stall_form);
        }

      });

      // Extension
      const extensionModal = document.getElementById('submitExtensionReceiptModal');
      extensionModal.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault(); // Stops the form from submitting or closing the modal
        }
      });

      const uploadExtensionButton = document.getElementById('uploadExtensionPaymentReceipt');
      uploadExtensionButton.addEventListener('click', function() {
        const selectedStall = document.querySelector('input[name="selected_stall_id"]:checked');
        const selectedRow = selectedStall.closest('tr');
        const fileInput = document.getElementById('extensionReceiptFile');
        const paid_amount = document.getElementById('extensionPaidAmount').value;
        const rental_fee = parseFloat(selectedRow.querySelector('td:nth-child(6)').textContent.trim());

        console.log(selectedStall);

        if (!selectedRow) {
          alert("Please select a stall before uploading a receipt.");
          return;
        }


        if (validateReceiptForm(selectedStall, fileInput, paid_amount, rental_fee)) {
          const extension_form = document.getElementById('submitExtensionRecieptForm');
          submitPayment(extension_form);
        }
      });


      // Event listener for garbage request form submission
      const form = document.getElementById("garbageRequestForm");
      form.addEventListener("submit", handleGarbageRequestSubmission);

    });

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

    // Validate Receipt Form
    function validateReceiptForm(stall, fileInput, paid_amount, amount_due) {
      let valid = true;

      // Validate file input and stall
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
      }

      // Additional validation for paid_amount and amount_due
      if (valid && paid_amount < amount_due) {
        alert("Paid amount cannot be less than the amount due.");
        valid = false;
      }

      if (valid && paid_amount > amount_due) {
        alert("Paid amount cannot be greater than the amount due.");
        valid = false;
      }

      return valid;
    }

    function submitPayment(form) {
      const formData = new FormData(form);
      for (let [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
      }
      fetch('../actions/submit_receipt.php', {
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
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while submitting the payment.');
        });

    }

    function populateViewStallsTable(stalls) {
      const stallsContainer = document.getElementById('stallsContainer');
      stallsContainer.innerHTML = ''; // Clear the table body

      if (!stalls || stalls.length === 0) {
        document.getElementById('stall_message').textContent = 'No stall is subject for payment.';
      } else {
        stalls.forEach(stall => {
          const row = document.createElement('tr');
          row.innerHTML = `
                                        <td>${stall.market_name}</td>
                                        <td>${stall.section_name}</td>
                                        <td>${stall.stall_number}</td>
                                        <td>${stall.stall_size}</td>
                                        <td>${stall.rental_fee}</td>
                                        <td class="${new Date(stall.stall_expiration_date) < new Date() ? 'text-danger' : 'text-success'}">
                                          <strong>${stall.stall_expiration_date ? new Date(stall.stall_expiration_date).toLocaleDateString() : 'N/A'}</strong>
                                        </td>
                                         <td class="${new Date(stall.extension_expiration_date) < new Date() ? 'text-danger' : 'text-success'}">
                                          <strong>${stall.extension_expiration_date ? new Date(stall.extension_expiration_date).toLocaleDateString() : 'N/A'}</strong>
                                        </td>
                                        <td>${stall.helper_name && stall.helper_name.trim() !== '' ? stall.helper_name : 'N/A'}</td>

                                    `;
          stallsContainer.appendChild(row);
        });

      }
    }

    function populateSubmitExtensionReceiptTable() {
      fetch('../actions/get_unpaid_extensions.php')
        .then(response => response.json())
        .then(data => {
          const recieptExtensionsContainer = document.getElementById('receiptExtensionContainer');
          recieptExtensionsContainer.innerHTML = '';

          // Check if response is valid
          if (!data.success || !data.unpaid_extensions || data.unpaid_extensions.length === 0) {
            document.getElementById('receipt_extension_message').textContent = 'No stall extension is subject for payment.';
            return;
          }

          data.unpaid_extensions.forEach(stall => {
            const row = document.createElement('tr');
            console.log(stall);
            row.innerHTML = `
                  <td>
                       <label class="radio-modern">
                           <input type="radio" name="selected_stall_id" value="${stall.stall_id}">
                           <span class="radio-checkmark"></span>
                       </label>
                       <input type="hidden" id="selectedExtensionId" name="selected_extension_id" value="${stall.extension_id}">
                  </td>
                  <td>${stall.stall_number}</td>
                  <td>${stall.market_name || 'N/A'}</td>
                  <td>${stall.section_name || 'N/A'}</td>
                 
                  <td class="${
                    stall.payment_status === 'Overdue' ? 'text-secondary' :
                    stall.payment_status === 'Unpaid' ? 'text-danger' :
                    stall.payment_status === 'Payment_Period' ? 'text-success' :
                    '' }">
                       <strong>${stall.payment_status || 'N/A'}</strong> 
                  </td>
                  <td>${stall.extension_cost || 'N/A'}</td>
                  <td>${stall.duration || 'N/A'}</td>
                  <td>${stall.expiration_date ? stall.expiration_date.split(' ')[0] : 'N/A'}</td>
                            `;
            recieptExtensionsContainer.appendChild(row);

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

    function populateSubmitStallReceiptTable() {
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