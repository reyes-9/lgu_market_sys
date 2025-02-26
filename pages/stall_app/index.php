<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stall Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/stall_app.css">
    <link rel="stylesheet" href="../../assets/css/toast.css">
    <?php include '../../includes/cdn-resources.php'; ?>

</head>
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body class="body light">
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>

    <!-- NAVBAR -->
    <?php include '../../includes/nav.php'; ?>

    <!-- MAIN CONTENT -->

    <div class="content-wrapper">

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
                        <div class="col-md-6"><strong>Date Submitted:</strong> <span id="current_date"></span> </div>
                        <div class="col-md-6"><strong>Application Form Number:</strong> <span id="app_number"></span></div>
                    </div>
                </div>
            </div>

            <form action="" id="marketSelectionForm">
                <div class="form-section">Select Market</div>
                <!-- Market Dropdown -->
                <small> <strong>Note:</strong> Only available stalls are displayed for selection.</small>
                <div class="mb-3 form-group">
                    <label for="market">Market: <small class="error-message"></small></label>
                    <select class="form-select" id="market" onchange="getStallData()" required>
                        <option value="" disabled selected>-- Select Market --</option>
                    </select>
                    <span id="market_address"></span>
                </div>

                <!-- Section and Stall (side by side) -->
                <div class="row">
                    <div class="col form-group">
                        <label for="section">Section: <small class="error-message"></small></label>
                        <select class="form-select" id="section" onchange="getStallData()">
                            <option value="" disabled selected>-- Select Section --</option>
                        </select>
                    </div>
                    <div class="col form-group">
                        <label for="stall">Stall Number: <small class="error-message"></small></label>
                        <select class="form-select" id="stall" required>
                            <option value="" disabled selected>-- Select Stall Number --</option>
                        </select>
                    </div>
                </div>
                <div id="stallInfo"></div>
                <div id="message"></div>
                <button type="button" class="form-button" id="marketBtn">Next</button>
            </form>


            <form class="d-none" id="detailsForm" method="POST" action="">
                <!-- Personal Information -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div class="form-section">Selected Market</div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Market: <small class="error-message"></small></label>
                        <input class="form-control" id="marketInput" name="market" value="" data-market-id="" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Section: <small class="error-message"></small></label>
                        <input class="form-control" id="sectionInput" name="section" value="" data-section-id="" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Stall No.: <small class="error-message"></small></label>
                        <input class="form-control" id="stallInput" name="stall" value="" data-stall-id="" readonly>
                    </div>
                </div>

                <div class="form-section">Personal Information</div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Email: <small class="error-message"></small></label>
                        <input type="email" class="form-control" id="email" name="email">
                        <small id="emailError" class="d-none">Invalid email format.</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Alternate Email: <small class="error-message"></small></label>
                        <input type="email" class="form-control" id="altEmail" name="alt_email">
                        <small id="altEmailError" class="d-none">Invalid email format.</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Mobile Number: <small class="error-message"></small></label>
                        <input type="tel" class="form-control" id="mobile" name="contact_no">
                        <small id="mobileError" class="d-none">Mobile number must be exactly 11 digits.</small>
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
                        <small>Type N/A if you don't have middle name</small>
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
                            <select class="form-control" name="civil_status">
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Separated">Separated</option>
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
                        <input type="text" class="form-control" id="zipcode" name="zip_code">
                        <small id="zipError" class="d-none">ZIP code must be exactly 4 digits.</small>
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

                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('detailsForm', 'marketSelectionForm')">Back</button>
                    <button type="button" class="form-button" id="detailsBtn">Next</button>
                </div>


            </form>

            <form class="d-none" id="documentUploadForm" method="POST" enctype="multipart/form-data">
                <div class="form-section">Upload Documents</div>

                <!-- Proof of Residency -->
                <div class="form-group mb-3">
                    <label>Proof of Residency (Utility Bill, Barangay Certificate, etc.) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="proofResidency" name="proof_residency" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="proofResidencyError"></small>
                </div>

                <!-- Valid ID Selection -->
                <div class="form-group mb-3">
                    <label>Valid ID Type <small class="text-danger">*</small></label>
                    <select class="form-control" id="validIdType" name="valid_id_type">
                        <option value="">Select Valid ID</option>
                        <option value="Passport">Passport</option>
                        <option value="Drivers_license">Driver’s License</option>
                        <option value="Umid">UMID</option>
                        <option value="SSS">SSS ID</option>
                        <option value="GSIS">GSIS ID</option>
                        <option value="PRC">PRC ID</option>
                        <option value="Postal">Postal ID</option>
                        <option value="Voters">Voter’s ID</option>
                        <option value="Philhealth">PhilHealth ID</option>
                        <option value="TIN">TIN ID</option>
                        <option value="National_Id">PhilSys National ID</option>
                    </select>
                </div>

                <!-- Valid ID Upload -->
                <div class="form-group mb-3">
                    <label>Upload Valid ID <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="validIdFile" name="valid_id_file" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="validIdError"></small>
                </div>
                <input type="hidden" id="applicationNumber" name="application_number" value="">



                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('documentUploadForm', 'detailsForm')">Back</button>
                    <button type="submit" class="form-button" id="submitApplication">Submit Documents</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>
    <script src="../../assets/js/toast.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById("marketBtn").addEventListener("click", handleMarketSelection);
            document.getElementById("detailsBtn").addEventListener("click", validateDetailsForm);
            getCurrentDate();
            generateAndSubmitApplication();

        });
        document.getElementById("documentUploadForm").addEventListener("submit", function(event) {
            event.preventDefault();

            if (!validateDocumentsForm()) {
                console.log("Document Upload Failed.");
                return;
            }

            const documentForm = this;
            const detailsForm = document.getElementById("detailsForm");
            const formData = new FormData(documentForm);
            const fields = ["market", "section", "stall"];

            // Append market, section, and stall IDs directly to FormData
            fields.forEach(field => {
                let input = document.getElementById(field + "Input");
                let fieldValue = input.getAttribute("data-" + field + "-id");

                if (fieldValue) {
                    formData.append(field + "_id", fieldValue);
                }
            });

            // Append detailsForm data
            new FormData(detailsForm).forEach((value, key) => {
                if (!formData.has(key)) {
                    formData.append(key, value);
                }
            });

            console.log("Combined Form Data:");
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch("../actions/submit_stall_app.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayToast("Your application has been submitted successfully!", "success");
                        documentForm.reset();
                        detailsForm.reset();
                    } else {
                        displayToast("An error occurred while submitting the application", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    displayToast("An error occurred while submitting the application", "error");
                });
        });

        // Function to generate and submit application form
        function generateAndSubmitApplication() {
            // Function to fetch the last application ID from the server
            function getLastApplicationId() {
                return $.ajax({
                    url: '../actions/get_last_app_id.php', // Endpoint to get the last application ID
                    type: 'GET',
                    dataType: 'json'
                });
            }

            // Function to generate application number
            function generateApplicationNumber(lastApplicationId) {
                let currentDate = new Date();
                let formattedDate = currentDate.getFullYear() +
                    ("0" + (currentDate.getMonth() + 1)).slice(-2) +
                    ("0" + currentDate.getDate()).slice(-2); // YYYYMMDD format

                let applicationNumber = "APP-" + formattedDate + "-" + String(lastApplicationId + 1).padStart(6, '0');
                return applicationNumber;
            }

            // When the page loads, generate the application number
            getLastApplicationId().done(function(response) {
                // Assuming the response contains the last application ID
                let lastApplicationId = response.last_application_id; // Example: 123
                let applicationNumber = generateApplicationNumber(lastApplicationId);
                document.getElementById('app_number').textContent = applicationNumber;

                // Set the generated application number in the input field
                $('#applicationNumber').val(applicationNumber);

                // Handle form submission
                $('#c').submit(function(e) {
                    e.preventDefault();

                    // Get the application number
                    let applicationNumber = $('#applicationNumber').val();

                    // Send the form data to the server for saving
                    $.ajax({
                        url: 'submit_stall_app.php',
                        type: 'POST',
                        data: {
                            application_number: applicationNumber
                        },
                        contentType: 'application/x-www-form-urlencoded',
                        success: function(response) {
                            alert(response.message);
                        }
                    });
                });
            }).fail(function() {
                console.error('Error fetching last application ID');
            });
        }

        function switchForm(hideFormId, showFormId) {
            document.getElementById(hideFormId).classList.add("d-none");
            document.getElementById(showFormId).classList.remove("d-none");
        }

        function handleMarketSelection() {
            const marketSelect = document.getElementById("market");
            const sectionSelect = document.getElementById("section");
            const stallSelect = document.getElementById("stall");

            const marketVal = marketSelect.value;
            const sectionVal = sectionSelect.value;
            const stallVal = stallSelect.value;

            let marketTxt = getSelectedText("market");
            let sectionTxt = getSelectedText("section");
            let stallTxt = getSelectedText("stall");

            const detailsForm = document.getElementById("detailsForm");
            const marketForm = document.getElementById("marketSelectionForm");

            const marketInput = document.getElementById("marketInput");
            const sectionInput = document.getElementById("sectionInput");
            const stallInput = document.getElementById("stallInput");

            let isValid = true;

            if (marketVal === "") {
                marketSelect.classList.add("error");
                isValid = false;
            } else {
                marketSelect.classList.remove("error");
            }

            if (sectionVal === "") {
                sectionSelect.classList.add("error");
                isValid = false;
            } else {
                sectionSelect.classList.remove("error");
            }

            if (stallVal === "") {
                stallSelect.classList.add("error");
                isValid = false;
            } else {
                stallSelect.classList.remove("error");
            }

            if (isValid) {
                marketInput.value = marketTxt;
                sectionInput.value = sectionTxt;
                stallInput.value = stallTxt;

                marketInput.dataset.marketId = marketVal;
                sectionInput.dataset.sectionId = sectionVal;
                stallInput.dataset.stallId = stallVal;

                detailsForm.classList.remove('d-none');
                marketForm.classList.add('d-none');
            } else {
                displayToast("Please complete all required fields", "error");
            }
        }

        function getSelectedText(selectId) {
            const selectElement = document.getElementById(selectId);
            if (selectElement.selectedIndex !== -1) {
                return selectElement.options[selectElement.selectedIndex].text;
            }
            return "Not selected";
        }

        function validateDetailsForm() {
            let isValid = true;
            const detailsForm = document.getElementById("detailsForm");
            const documentForm = document.getElementById("documentUploadForm");
            const inputs = detailsForm.querySelectorAll("input, select");
            const mobileError = document.getElementById("mobileError");
            const zipError = document.getElementById("zipError");
            const emailInput = document.getElementById("email");
            const altEmailInput = document.getElementById("altEmail");
            const emailError = document.getElementById("emailError");
            const altEmailError = document.getElementById("altEmailError");

            inputs.forEach(input => {
                const parentDiv = input.closest(".form-group");
                if (!parentDiv) return;

                const errorMessage = parentDiv.querySelector(".error-message");
                if (!errorMessage) return;

                if (input.value.trim() === "") {
                    input.classList.add("error");
                    errorMessage.textContent = "*";
                    isValid = false;
                    console.log(`Invalid Input: ${input.name} | Value: "${input.value}"`);
                } else {
                    input.classList.remove("error");
                    errorMessage.textContent = "";
                }
            });

            if (!isMobileValid()) {
                isValid = false;
                mobileError.classList.remove("d-none");
            } else {
                mobileError.classList.add("d-none");

            }

            if (!isZipValid()) {
                isValid = false;
                zipError.classList.remove("d-none");
            } else {
                zipError.classList.add("d-none")
            }

            const emailValidation = isEmailValid(emailInput.value, altEmailInput.value);
            console.log("EMAIL", emailValidation.emailValid);
            console.log("ALT EMAIL", emailValidation.altEmailValid);

            if (!emailValidation.emailValid) {
                isValid = false;
                emailError.classList.remove("d-none");
            } else {
                emailError.classList.add("d-none");
            }

            if (!emailValidation.altEmailValid) {
                isValid = false;
                altEmailError.classList.remove("d-none");
            } else {
                altEmailError.classList.add("d-none");
            }


            console.log("Mobile Valid:", isMobileValid());
            console.log("Zip Valid:", isZipValid());
            console.log("Final isValid:", isValid);

            if (isValid) {
                console.log("Form is valid, switching view...");
                documentForm.classList.remove('d-none');
                detailsForm.classList.add('d-none');
            } else {
                displayToast("Please complete all required fields", "error");
            }
        }

        function isMobileValid() {
            const mobileInput = document.getElementById("mobile");
            const mobileValue = mobileInput.value.trim();
            if (/^\d{11}$/.test(mobileValue)) {
                return true;
            } else {
                return false;
            }
        }

        function isZipValid() {
            const zipInput = document.getElementById("zipcode");
            const zipValue = zipInput.value.trim();
            if (/^\d{4}$/.test(zipValue)) {
                return true;
            } else {
                return false;
            }
        }

        function isEmailValid(email, altEmail) {
            const emailResult = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);
            const altEmailResult = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(altEmail);

            return {
                emailValid: emailResult,
                altEmailValid: altEmailResult
            };
        }

        function validateSelect(input) {
            if (input.value.trim() === "") {
                input.classList.add("error");
                return false;
            } else {
                input.classList.remove("error");
                return true;
            }
        }

        function validateFile(inputElement, errorElement) {
            if (!inputElement || !inputElement.files[0]) {
                errorElement.textContent = "Please upload a file.";
                return false;
            }

            const file = inputElement.files[0];
            const allowedTypes = ["application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "image/png"];
            const maxFileSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                errorElement.textContent = `Invalid file type: ${file.name}. Allowed types: PDF, DOCX, JPG, PNG.`;
                return false;
            }

            if (file.size > maxFileSize) {
                errorElement.textContent = `File too large: ${file.name}. Maximum size is 5MB.`;
                return false;
            }

            errorElement.textContent = ""; // Clear error if valid
            return true;
        }

        function validateDocumentsForm() {
            const proofResidencyInput = document.getElementById("proofResidency");
            const validIdFileInput = document.getElementById("validIdFile");
            const validIdTypeInput = document.getElementById("validIdType");

            const proofResidencyError = document.getElementById("proofResidencyError");
            const validIdError = document.getElementById("validIdError");

            let isValid = true;

            if (!validateFile(proofResidencyInput, proofResidencyError)) {
                isValid = false;
            }

            if (!validateFile(validIdFileInput, validIdError)) {
                isValid = false;
            }

            if (!validateSelect(validIdTypeInput)) {
                isValid = false;
            }

            if (!isValid) {
                displayToast("Please complete all required fields", "error");
            }

            return isValid; // Return true if valid, false otherwise
        }

        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Adds leading zero if necessary
            const day = String(today.getDate()).padStart(2, '0'); // Adds leading zero if necessary
            const dateHTML = document.getElementById('current_date');
            dateHTML.textContent = `${year}-${month}-${day}`;
        }
    </script>

    <script>
        let locationsData; // Store the fetched data for later use

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

                    // Filter unavailable stalls (not 'available')
                    let availableStalls = data.available_stalls || [];

                    if (availableStalls.length === 0) {
                        message.innerHTML = `<p style="color: #d32f2f"><strong>There are no available stalls available in this section</strong></p>`;
                        stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';
                        return;
                    }

                    message.innerHTML = '';
                    stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';

                    availableStalls.forEach(stall => {
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
    </script>

</body>

</html>