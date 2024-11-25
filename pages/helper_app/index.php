<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helper Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/stall.css">
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
                        <h2 class="text-center mb-4">Helper Application</h2>

                        <h5>Select Stall</h5>
                        <hr>
                        <div id="responseMessage" class="alert mt-3" style="display:none;"></div>
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
    <!-- Bootstrap JS and dependencies -->

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
                    const responseMessage = document.getElementById('responseMessage');
                    if (data.success) {
                        responseMessage.innerHTML = `<div class="alert alert-success">${data.messages.join('<br>')}</div>`;
                        responseMessage.style.display = 'block';
                    } else {
                        responseMessage.innerHTML = `<div class="alert alert-danger">${data.messages.join('<br>')}</div>`;
                        responseMessage.style.display = 'block';
                    }
                    // console.table(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('responseMessage').innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>