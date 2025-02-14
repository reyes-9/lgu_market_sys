<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stall Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/stall_app.css">
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

        <div class="container-fluid px-5 d-none">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6">
                    <div class="container shadow rounded-3 p-5 application light">
                        <h2 class="text-center mb-4">Stall Application</h2>

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

                        <form id="application_form" action="../actions/application_action.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="application_type" value="stall">

                            <!-- Market Dropdown -->
                            <!-- <div class="mb-3">
                                <label for="market">Market:</label>
                                <select class="form-select" id="market" name="market" onchange="loadStallsWithSection()" required>
                                    <option value="" disabled selected>-- Select Market --</option>
                                </select>
                                <span id="market_address"></span>
                            </div> -->

                            <!-- Section and Stall (side by side) -->
                            <!-- <div class="row mb-3">
                                <div class="col">
                                    <label for="section">Section:</label>
                                    <select class="form-select" id="section" name="section" onchange="loadStallsWithSection()">
                                        <option value="" disabled selected>-- Select Section --</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="stall">Stall Number:</label>
                                    <select class="form-select" id="stall" name="stall" required>
                                        <option value="" disabled selected>-- Select Stall Number --</option>
                                    </select>
                                </div>
                            </div> -->

                            <!-- Stall Info -->
                            <!-- <div id="stallInfo" class="stall-info mb-3">
                                Select a stall number to view information.
                            </div> -->

                            <!-- QC ID and Current ID (side by side) -->
                            <!-- <div class="row mb-3">
                                <div class="col">
                                    <label for="documents">Upload Required Documents:</label>
                                    <input type="file" class="form-control" id="documents" name="documents[]" multiple required>
                                </div>
                            </div> -->
                            <br>
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-warning">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-container">

            <div class="form-header">
                <div class="header-top">
                    <!-- <img src="logo1.png" alt="Logo" class="logo left"> -->
                    <div class="header-text">
                        <h3>Republic of the Philippines</h3>
                        <h2>QUEZON CITY</h2>
                        <h3><strong>PUBLIC MARKET MONITORING SYSTEM</strong></h3>
                        <p>publicmarketmonitoring@gmail.com</p>
                    </div>
                    <!-- <img src="logo2.png" alt="Logo" class="logo right"> -->
                </div>

                <div class="header-bottom container">
                    <div class="row">
                        <div class="col-md-6"><strong>Application Type:</strong> STALL APPLICATION</div>
                        <div class="col-md-6"><strong>Application Status:</strong> New</div>
                        <div class="col-md-6"><strong>Date Submitted:</strong> 02/14/2025</div>
                        <div class="col-md-6"><strong>Application Form Number:</strong> NSA006106</div>
                    </div>
                </div>
            </div>

            <form action="" id="marketSelectionForm">
                <div class="form-section">Select Market</div>
                <!-- Market Dropdown -->
                <div class="mb-3 form-group">
                    <label for="market">Market:</label>
                    <select class="form-select" id="market" name="market" onchange="getStallData()" required>
                        <option value="" disabled selected>-- Select Market --</option>
                    </select>
                    <span id="market_address"></span>
                </div>

                <!-- Section and Stall (side by side) -->
                <div class="row">
                    <div class="col form-group">
                        <label for="section">Section:</label>
                        <select class="form-select" id="section" name="section" onchange="getStallData()">
                            <option value="" disabled selected>-- Select Section --</option>
                        </select>
                    </div>
                    <div class="col form-group">
                        <label for="stall">Stall Number:</label>
                        <select class="form-select" id="stall" name="stall" required>
                            <option value="" disabled selected>-- Select Stall Number --</option>
                        </select>
                    </div>
                </div>
                <div id="stallInfo"></div>
                <div id="message"></div>
                <button type="button" class="form-button" id="marketBtn">Next</button>
            </form>

            <form class="d-none" id="stallApplicationForm" method="POST" action="">
                <!-- Personal Information -->

                <div class="form-section">Selected Market</div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Market: <small class="error-message"></small></label>
                        <input class="form-control" id="marketInput" value="" data-market-id="" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Section: <small class="error-message"></small></label>
                        <input class="form-control" id="sectionInput" value="" data-section-id="" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Stall No.: <small class="error-message"></small></label>
                        <input class="form-control" id="stallInput" value="" data-stall-id="" disabled>
                    </div>
                </div>

                <div class="form-section">Personal Information</div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Email: <small class="error-message"></small></label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Alternate Email: <small class="error-message"></small></label>
                        <input type="email" class="form-control" name="alt_email">
                    </div>
                    <div class="form-group col-md-4">
                        <label> Number: <small class="error-message"></small></label>
                        <input type="tel" class="form-control" name="contact_no">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group col-md-4">
                        <label>First Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="first_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Middle Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="middle_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Last Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="last_name">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group col-md-4 position-relative">
                        <label>Sex: <small class="error-message"></small></label>
                        <div class="dropdown-wrapper">
                            <select class="form-control" name="sex">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <i class="bi bi-chevron-down dropdown-icon"></i>
                        </div>
                    </div>

                    <div class="form-group col-md-4 position-relative">
                        <label>Civil Status <small class="error-message"></small></label>
                        <div class="dropdown-wrapper">
                            <select class="form-control" name="status">
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                            </select>
                            <i class="bi bi-chevron-down dropdown-icon"></i>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nationality: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="nationality" value="Filipino">
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">Address</div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>House Number: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="house_no">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Street: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="street">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Subdivision: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="subdivision">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group col-md-4">
                        <label>Province: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="province">
                    </div>
                    <div class="form-group col-md-4">
                        <label>City: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="city">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Barangay: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="barangay">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group col-md-4">
                        <label>Zip Code: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="zip_code">
                    </div>
                </div>

                <!-- Market Information -->
                <div class="form-section">Market Information</div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>What will you sell? <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="market_items">
                    </div>
                </div>

                <button type="submit" class="form-button" id="submitBtn">Submit Application</button>

            </form>
        </div>



    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>


    <script>
        // Theme
        const application = document.querySelector('.application');
        themeToggleButton.addEventListener("click", () => {
            application.classList.toggle("dark");
            application.classList.toggle("light");
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById("submitBtn").addEventListener("click", validateForm);
            document.getElementById("marketBtn").addEventListener("click", handleMarketSelection);

        });

        function handleMarketSelection() {
            const marketVal = document.getElementById("market").value;
            const sectionVal = document.getElementById("section").value;
            const stallVal = document.getElementById("stall").value;

            let marketTxt = getSelectedText("market");
            let sectionTxt = getSelectedText("section");
            let stallTxt = getSelectedText("stall");

            const nextForm = document.getElementById("stallApplicationForm");
            const currentForm = document.getElementById("marketSelectionForm");

            const marketInput = document.getElementById("marketInput");
            const sectionInput = document.getElementById("sectionInput");
            const stallInput = document.getElementById("stallInput");

            if (marketVal !== "" && sectionVal !== "" && stallVal !== "") {

                marketInput.value = marketTxt;
                sectionInput.value = sectionTxt;
                stallInput.value = stallTxt;

                marketInput.dataset.marketId = marketVal;
                sectionInput.dataset.sectionId = sectionVal;
                stallInput.dataset.stallId = stallVal;

                nextForm.classList.remove('d-none');
                currentForm.classList.add('d-none');

            }
        }

        function getSelectedText(selectId) {
            const selectElement = document.getElementById(selectId);
            if (selectElement.selectedIndex !== -1) {
                return selectElement.options[selectElement.selectedIndex].text;
            }
            return "Not selected";
        }

        function validateForm(event) {
            let isValid = true;
            const inputs = document.querySelectorAll("input");

            inputs.forEach(input => {
                const parentDiv = input.closest(".form-group");
                if (!parentDiv) return;

                const errorMessage = parentDiv.querySelector(".error-message");
                if (!errorMessage) return;

                if (input.value.trim() === "") {
                    input.classList.add("error");
                    errorMessage.textContent = "*";
                    isValid = false;
                } else {
                    input.classList.remove("error");
                    errorMessage.textContent = "";
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        }
    </script>

    <script>
        let locationsData; // Store the fetched data for later use

        // Shows the stall info upon choosing a stall
        // And hiding it upon selecting another market or section 
        document.addEventListener('DOMContentLoaded', function() {
            const marketSelect = document.getElementById('market')
            const stallSelect = document.getElementById('stall');
            const sectionSelect = document.getElementById('section');
            const stallInfo = document.getElementById('stallInfo');

            getMarkets();
            getSections();

            stallSelect.addEventListener('change', showStallInfo);
            sectionSelect.addEventListener('change', function() {
                stallInfo.innerHTML = '';
            });

            marketSelect.addEventListener('change', function() {
                stallInfo.innerHTML = '';
                showMarketAddress();
            });




        });


        function showStallInfo() {
            const stallSelect = document.getElementById('stall');
            const selectedOption = stallSelect.options[stallSelect.selectedIndex];

            const dataInfo = selectedOption.getAttribute('data-info') || 'Rental Fee: N/A, Stall Size: N/A';

            // Parsing the data-info string
            const infoArray = dataInfo.split(', ');
            const rentalFee = infoArray[0] ? infoArray[0].split(': ')[1] : 'N/A';
            const stallSize = infoArray[1] ? infoArray[1].split(': ')[1] : 'N/A';

            // Update the stallInfo section with a table
            const stallInfo = `
            <div class="card custom-card">
                <div class="card-body text-center">
                    <h5 class="card-title">Stall Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-label">Rental Fee</span>
                                <span class="info-value">₱${rentalFee}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-label">Stall Size</span>
                                <span class="info-value">${stallSize} sqm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
            document.getElementById('stallInfo').innerHTML = stallInfo;
        }

        function getStallData() {
            const marketId = document.getElementById('market').value;
            const sectionId = document.getElementById('section').value;

            // Only make the request if both market and section are selected
            if (marketId && sectionId) {
                setStallData(marketId, sectionId);
            }
        }

        function setStallData(marketId, sectionId) {

            fetch('../actions/get_stalls.php?market_id=' + marketId + '&section_id=' + sectionId)
                .then(response => response.json())
                .then(data => {
                    let stallSelect = document.getElementById('stall');
                    let message = document.getElementById('message');

                    if (data.success === false) {
                        message.innerHTML = `<p style="color: #d32f2f"><strong>${data.message}</strong></p>`;
                        stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';
                        return;
                    }

                    message.innerHTML = '';
                    stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';
                    data.forEach(stall => {
                        let option = document.createElement('option');
                        option.value = stall.id;
                        option.setAttribute('data-info', 'Rental Fee: ' + stall.rental_fee + ', Stall Size: ' + stall.stall_size);
                        option.text = stall.stall_number;
                        stallSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching stalls:', error));
        }

        function showMarketAddress() {
            const marketSelect = document.getElementById('market');
            const marketAddressElement = document.getElementById('market_address');

            if (!marketSelect || !marketAddressElement) {
                console.error("Market select or market address element not found.");
                return;
            }

            const selectedOption = marketSelect.options[marketSelect.selectedIndex];
            const selectedId = selectedOption.value;

            if (!selectedId) {
                marketAddressElement.innerText = '';
                return;
            }

            if (!Array.isArray(locationsData)) {
                console.error("locationsData is not defined or not an array.");
                return;
            }

            const selectedLocation = locationsData.find(location => location.id == selectedId);
            marketAddressElement.innerText = selectedLocation ? selectedLocation.market_address : '';
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

        function getSections() {
            fetch('../actions/get_section.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    let sectionSelect = document.getElementById('section');
                    data.forEach(section => {
                        let option = document.createElement('option');
                        option.value = section.id;
                        option.text = section.section_name;
                        sectionSelect.appendChild(option);
                    });

                })
                .catch(error => {
                    console.error('Error fetching market locations:', error);
                    alert('Failed to load market locations. Please try again later.');
                });
        }



        document.getElementById("stallApplicationForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = this;
            const formData = new FormData(form);

            console.log("Form Data:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // // Submit form data via AJAX
            // fetch("submit_application.php", {
            //         method: "POST",
            //         body: formData
            //     })
            //     .then(response => response.json()) // Expecting JSON response
            //     .then(data => {
            //         if (data.success) {
            //             alert("Application submitted successfully!");
            //             form.reset(); // Reset form after successful submission
            //         } else {
            //             alert("Error: " + data.message);
            //         }
            //     })
            //     .catch(error => {
            //         console.error("Error:", error);
            //         alert("An error occurred while submitting the application.");
            //     });
        });


        // document.getElementById('application_form').addEventListener('submit', function(event) {
        //     event.preventDefault();

        //     const formData = new FormData(this);

        //     fetch('../actions/application_action.php', {
        //             method: 'POST',
        //             body: formData
        //         })
        //         .then(response => response.json())
        //         .then(data => {

        //             if (data.success) {
        //                 // document.getElementById('responseModalLabel').textContent = 'Message';
        //                 document.getElementById('responseModalBody').innerHTML = data.messages.join('<br>');
        //                 document.getElementById('responseModalBody').classList.remove('text-danger');
        //                 document.getElementById('responseModalBody').classList.add('text-success');
        //             } else {
        //                 // document.getElementById('responseModalLabel').textContent = 'Error';
        //                 document.getElementById('responseModalBody').innerHTML = data.messages.join('<br>');
        //                 document.getElementById('responseModalBody').classList.remove('text-success');
        //                 document.getElementById('responseModalBody').classList.add('text-danger');
        //             }

        //             // Show the modal
        //             const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
        //             responseModal.show();

        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //             document.getElementById('responseMessage').innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
        //         });
        // });
    </script>



</body>

</html>