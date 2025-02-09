<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helper Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/stall.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body class="body light">

    <!-- NAVBAR -->
    <?php include '../../includes/nav.php'; ?>

    <!-- MAIN CONTENT -->

    <div class="content-wrapper">

        <?php include '../../includes/menu.php'; ?>

        <div class="container-fluid px-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6">
                    <div class="container shadow rounded-3 p-5 application light">

                        <!-- Response Modal -->
                        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                            <div class="modal-dialog  modal-dialog-centered">
                                <div class="modal-content text-center">
                                    <i class="bi bi-check-circle-fill icon-animation"></i>
                                    <div class="modal-body" id="responseModalBody">
                                        <!-- Message content will go here -->
                                    </div>
                                    <div class="text-center text-secondary">
                                        <p>Click anywhere to continue.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-center mb-4">Helper Application</h2>

                        <h5>Select Stall</h5>
                        <hr>
                        <h5 id="stall_message"></h5>
                        <form id="application_form" action="../actions/application_action.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="application_type" value="add helper">

                            <table class="table table-striped table-borderless table-hover custom-table light">
                                <thead>
                                    <tr>
                                        <th><strong>Select</strong></th>
                                        <th><strong>Stall No.</strong></th>
                                        <th><strong>Market</strong></th>
                                        <th><strong>Section</strong></th>
                                        <th><strong>Stall Size</strong></th>
                                        <th><strong>Rental Fee</strong></th>
                                    </tr>
                                </thead>
                                <tbody id="stallsContainer">
                                    <!-- Dynamic stall rows will be inserted here -->
                                </tbody>
                            </table>

                            <div class="row mt-4">
                                <div class="col">
                                    <label for="First Name">First Name:</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
                                </div>
                                <div class="col">
                                    <label for="Last Name">Last Name:</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col">
                                    <label for="validIdType">Select Valid Id</label>
                                    <select class="form-control" id="validIdType" name="valid_id_type" required>
                                        <option value="" disabled selected>-- Select an ID --</option>
                                        <option value="Philippine Passport">Philippine Passport</option>
                                        <option value="Social Security System (SSS) ID">Social Security System (SSS) ID</option>
                                        <option value="Driver’s License">Driver’s License</option>
                                        <option value="PhilHealth ID">PhilHealth ID</option>
                                        <option value="Taxpayer Identification Number (TIN) ID">Taxpayer Identification Number (TIN) ID</option>
                                        <option value="Unified Multi-Purpose ID (UMID)">Unified Multi-Purpose ID (UMID)</option>
                                        <option value="Voter’s ID">Voter’s ID</option>
                                        <option value="Postal ID">Postal ID</option>
                                    </select>
                                </div>
                                <br>
                            </div>
                            <div class="row my-4">
                                <div class="col">
                                    <div class="w-50" id="id_section">
                                        <label for="documents"><span id="id_section_label"></span></label>
                                        <input type="file" class="form-control" id="document" name="document" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-warning">Submit Application</button>
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
        // Theme
        const application = document.querySelector('.application');
        const table = document.querySelector('.custom-table');

        themeToggleButton.addEventListener("click", () => {
            application.classList.toggle("dark");
            application.classList.toggle("light");
            table.classList.toggle('dark');
            table.classList.toggle('light');
        });

        // Valid Id Selection
        const validIdType = document.getElementById('validIdType');
        const idSection = document.getElementById('id_section');
        const idSectionLabel = document.getElementById('id_section_label');

        validIdType.addEventListener('change', function() {
            const selectedValue = validIdType.value;

            if (selectedValue) {
                idSection.style.display = 'block'; // Show the ID section
                idSectionLabel.textContent = `Upload ${validIdType.options[validIdType.selectedIndex].text}`;
            } else {
                idSection.style.display = 'none'; // Hide the ID section if no valid option is selected
                idSectionLabel.textContent = '';
            }
        });


        // Fetch the user data from the backend
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../actions/profile_action.php')
                .then(response => response.json())
                .then(data => {

                    // Handle stalls
                    console.log(data);
                    const stallsContainer = document.getElementById('stallsContainer');
                    stallsContainer.innerHTML = ''; // Clear previous entries

                    if (!data.stalls || data.stalls.length === 0) {
                        document.getElementById('stall_message').textContent = 'No stalls available.';
                    } else {
                        document.getElementById('stall_message').textContent = '';

                        data.stalls.forEach((stall, index) => { // Make sure 'index' is added as the second parameter here
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <tr style="background-color:orange;">
                                    <td>
                                        <label class="radio-modern">
                                            <input type="radio" name="selected_stall_id" value="${stall.id}" id="stall_${index}">
                                            <span class="radio-checkmark"></span>
                                        </label>
                                    </td>
                                    <td>${stall.stall_number}</td>
                                    <td>${stall.market_name}</td>
                                    <td>${stall.section_name}</td>
                                    <td>${stall.stall_size}</td>
                                    <td>${stall.rental_fee}</td>
                                </tr>
                            `;
                            stallsContainer.appendChild(row);

                            // Select all rows containing the radio buttons
                            document.querySelectorAll("tr").forEach(row => {
                                // Add a click event listener to each row
                                row.addEventListener("click", function() {
                                    // Get the radio button within the clicked row
                                    const radio = row.querySelector('input[name="selected_stall_id"]');

                                    if (radio) {

                                        radio.checked = true;
                                        const selectedStallId = radio.value;
                                        console.log("Selected Stall Id:", selectedStallId);

                                    }
                                });
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });


        document.getElementById('application_form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Create FormData object
            for (const [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            fetch('../actions/application_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        document.getElementById('responseModalBody').innerHTML = data.messages.join('<br>');
                        document.getElementById('responseModalBody').classList.remove('text-danger');
                        document.getElementById('responseModalBody').classList.add('text-success');
                    } else {
                        document.getElementById('responseModalBody').innerHTML = data.messages.join('<br>');
                        document.getElementById('responseModalBody').classList.remove('text-success');
                        document.getElementById('responseModalBody').classList.add('text-danger');
                    }

                    // Show the modal
                    const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
                    responseModal.show();

                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('responseMessage').innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                });
        });
    </script>
</body>

</html>