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
            <!-- Button to Trigger Modal -->
            <button class="btn btn-dark m-2" id="requestCollectionBtn" data-bs-toggle="modal" data-bs-target="#garbageRequestModal">
              Request Garbage Collection
            </button>
            <a href="/lgu_market_sys/pages/stall_extend" class="btn btn-warning m-2">Stall Extension Application</a>
            <a href="/lgu_market_sys/pages/helper_app" class="btn btn-warning m-2">Add Helper Application</a>
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
                <select class="form-select" id="market" onchange="getStallData()" required>
                  <option value="" disabled selected>-- Select Market --</option>
                </select>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary" id="requestCollectionBtn">Submit Request</button>
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
    // Fetch the user data from the backend
    document.addEventListener('DOMContentLoaded', function() {

      getMarkets();

      const form = document.getElementById("garbageRequestForm");
      form.addEventListener("submit", handleGarbageRequestSubmission);


      fetch('../actions/profile_action.php')
        .then(response => response.json())
        .then(data => {

          // Handle stalls
          const stallsContainer = document.getElementById('stallsContainer');
          stallsContainer.innerHTML = '';

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

    function getMarkets() {
      fetch('../actions/get_market.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          locationsData = data; // Store data globally
          let marketLocationSelect = document.getElementById('market');
          data.forEach(location => {
            let option = document.createElement('option');
            option.value = location.id;
            option.text = location.market_name;
            marketLocationSelect.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Error fetching market locations:', error);
          alert('Failed to load market locations. Please try again later.');
        });
    }
  </script>
</body>

</html>