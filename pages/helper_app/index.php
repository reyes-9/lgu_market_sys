<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helper Application - Public Market Monitoring System</title>
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
    <?php include '../../includes/nav.php'; ?>

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
                        <div class="col-md-6"><strong>Application Type:</strong> HELPER APPLICATION</div>
                        <div class="col-md-6"><strong>Application Status:</strong> New</div>
                        <div class="col-md-6"><strong>Date Submitted:</strong> <span id="current_date"></span> </div>
                        <div class="col-md-6"><strong>Application Form Number:</strong> <span id="app_number"></span></div>
                    </div>
                </div>
            </div>

            <form action="" id="marketSelectionForm">
                <div class="form-section">Select Stall</div>

                <table class="table table-borderless table-hover">
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

                    </tbody>
                </table>
                <div id="stall_message"></div>
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

                <div class="form-section">Stall Owner Information</div>
                <div class="row mt-2">
                    <div class="form-group"> <label for="">(Stall Owner Name)</label> </div>
                    <div class="form-group col-md-4">
                        <label>First Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" id="ownerFirstName" name="owner_first_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Middle Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" id="ownerMiddleName" name="owner_middle_name">
                        <small>Type N/A if you don't have middle name</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Last Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" id="ownerLastName" name="owner_last_name">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="owner_id">Stall Owner ID:<small class="error-message"></small></label>
                    <input type="text" class="form-control" id="ownerId" name="owner_id" readonly />
                </div>

                <div class="form-section"> Helper's Personal Information </div>

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

                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('detailsForm', 'marketSelectionForm')">Back</button>
                    <button type="button" class="form-button" id="detailsBtn">Next</button>
                </div>


            </form>

            <form class="d-none" id="documentUploadForm" method="POST" enctype="multipart/form-data">
                <div class="form-section">Upload Documents</div>

                <div class="form-group mb-3">
                    <label>Letter of Authorization <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="letterOfAuthorization" name="letter_authorization" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="letterOfAuthorizationError"></small>
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

                <div class="form-group mb-3">
                    <label>Barangay Clearance <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="barangayClearance" name="barangay_clearance" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="barangayClearanceError"></small>
                </div>

                <div class="form-group  mb-3">
                    <label>Proof of Residency <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="proofOfResidency" name="proof_of_residency" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="proofOfResidencyError"></small>
                </div>



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

            document.getElementById("marketBtn").addEventListener("click", validateMarket);
            document.getElementById("detailsBtn").addEventListener("click", validateDetailsForm);
            document.getElementById("documentUploadForm").addEventListener("submit", handleDocumentUpload);
            getCurrentDate();
            generateAndSubmitApplication();

        });

        function handleDocumentUpload(event) {
            event.preventDefault();

            if (!validateDocumentsForm()) {
                return;
            }

            const documentForm = event.target;
            const detailsForm = document.getElementById("detailsForm");
            const formData = new FormData(documentForm);
            const fields = ["market", "section", "stall"];

            // Append market, section, and stall IDs directly to FormData
            appendFormData(formData, fields);

            // Append detailsForm data
            new FormData(detailsForm).forEach((value, key) => {
                if (!formData.has(key)) {
                    formData.append(key, value);
                }
            });

            submitFormData(formData, documentForm, detailsForm);
        }

        function appendFormData(formData, fields) {
            fields.forEach(field => {
                let input = document.getElementById(field + "Input");
                let fieldValue = input.getAttribute("data-" + field + "-id");

                if (fieldValue) {
                    formData.append(field + "_id", fieldValue);
                }
            });
        }

        function submitFormData(formData, documentForm, detailsForm) {
            fetch("../actions/submit_helper_app.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayToast("Your application has been submitted successfully!", "success");
                        documentForm.reset();
                        detailsForm.reset();
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        displayToast("An error occurred while submitting the application", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    displayToast("An error occurred while submitting the application", "error");
                });
        }

        function switchForm(hideFormId, showFormId) {
            document.getElementById(hideFormId).classList.add("d-none");
            document.getElementById(showFormId).classList.remove("d-none");
        }

        function validateMarket() {

            const detailsForm = document.getElementById("detailsForm");
            const marketForm = document.getElementById("marketSelectionForm");
            const marketInput = document.getElementById("marketInput");
            const sectionInput = document.getElementById("sectionInput");
            const stallInput = document.getElementById("stallInput");

            const marketSelected = isMarketSelected();

            if (!marketSelected) {
                displayToast("Please complete all required fields", "error");
                return;
            }

            stall = getSelectedStallDetails();

            marketInput.value = stall.market_name;
            sectionInput.value = stall.section_name;
            stallInput.value = stall.stall_number;

            marketInput.dataset.marketId = marketSelected.market_id;
            sectionInput.dataset.sectionId = marketSelected.section_id;
            stallInput.dataset.stallId = stall.stall_id;

            detailsForm.classList.remove('d-none');
            marketForm.classList.add('d-none');
        }

        function isMarketSelected() {
            const selectedRadio = document.querySelector('input[name="selected_stall_id"]:checked');

            if (!selectedRadio) {
                return false;
            }

            // Get the parent row (tr)
            const selectedRow = selectedRadio.closest('tr');

            // Get the data attributes from the relevant td elements
            const market_id = selectedRow.querySelector('[data-market-id]').dataset.marketId;
            const section_id = selectedRow.querySelector('[data-section-id]').dataset.sectionId;

            return {
                market_id,
                section_id
            };
        }

        function getSelectedStallDetails() {
            const selectedRadio = document.querySelector('input[name="selected_stall_id"]:checked');

            if (selectedRadio) {
                const selectedRow = selectedRadio.closest('tr');
                const tds = selectedRow.querySelectorAll('td');

                // Create an associative array (object) with meaningful keys
                const stallDetails = {
                    stall_id: selectedRadio.value, // Use radio value as stall ID
                    stall_number: tds[1].textContent.trim(),
                    market_name: tds[2].textContent.trim(),
                    section_name: tds[3].textContent.trim(),
                    stall_size: tds[4].textContent.trim(),
                    rental_fee: tds[5].textContent.trim()
                };

                return stallDetails; // Return the object if needed
            } else {
                return null;
            }
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

            if (isValid) {
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
            const letterOdAuthorizationInput = document.getElementById("letterOfAuthorization");
            const barangayClearanceInput = document.getElementById("barangayClearance");
            const proofResidencyInput = document.getElementById("proofOfResidency");
            const validIdFileInput = document.getElementById("validIdFile");
            const validIdTypeInput = document.getElementById("validIdType");

            const letterOfAuthorizationError = document.getElementById("letterOfAuthorizationError");
            const barangayClearanceError = document.getElementById("barangayClearanceError");
            const proofResidencyError = document.getElementById("proofOfResidencyError");
            const validIdError = document.getElementById("validIdError");

            let isValid = true;

            if (!validateFile(letterOdAuthorizationInput, letterOfAuthorizationError)) {
                isValid = false;
            }

            if (!validateFile(barangayClearanceInput, barangayClearanceError)) {
                isValid = false;
            }

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
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../actions/profile_action.php')
                .then(response => response.json())
                .then(data => {

                    const stallsContainer = document.getElementById('stallsContainer');
                    let ownerIdInput = document.getElementById('ownerId');
                    let ownerFirstNameInput = document.getElementById("ownerFirstName");
                    let ownerMiddleNameInput = document.getElementById("ownerMiddleName");
                    let ownerLastNameInput = document.getElementById("ownerLastName");
                    let firstName;
                    let middleName;
                    let lastName;

                    stallsContainer.innerHTML = '';

                    if (!data.stalls || data.stalls.length === 0) {
                        document.getElementById('stall_message').textContent = 'No stalls available.';
                    } else {
                        document.getElementById('stall_message').textContent = '';

                        let fullName = data.user[0].name.trim(); // Remove extra spaces
                        let nameParts = fullName.split(" "); // Split name by spaces

                        let firstName = nameParts[0] || ""; // First part is first name
                        let middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(" ") : ""; // Middle name (if exists)
                        let lastName = nameParts.length > 1 ? nameParts[nameParts.length - 1] : ""; // Last part is last name

                        // Assign values to inputs
                        ownerFirstNameInput.value = firstName;
                        ownerMiddleNameInput.value = middleName;
                        ownerLastNameInput.value = lastName;

                        ownerIdInput.value = data.stalls[3].account_id;


                        data.stalls.forEach((stall, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>
                            <label class="radio-modern">
                                <input type="radio" name="selected_stall_id" value="${stall.id}" id="stall_${index}">
                                <span class="radio-checkmark"></span>
                            </label>
                        </td>
                        <td>${stall.stall_number}</td>
                        <td data-market-id="${stall.market_id}">${stall.market_name}</td>
                        <td data-section-id="${stall.section_id}">${stall.section_name}</td>
                        <td>${stall.stall_size}</td>
                        <td>${stall.rental_fee}</td>
                    `;
                            stallsContainer.appendChild(row);
                            // Add click event listener to each row
                            row.addEventListener("click", function() {
                                // Remove 'table-active' from all rows
                                document.querySelectorAll("#stallsContainer tr").forEach(tr => tr.classList.remove("table-active"));

                                // Select the radio button within the clicked row
                                const radio = row.querySelector('input[name="selected_stall_id"]');

                                if (radio) {
                                    radio.checked = true;
                                    row.classList.add("table-active"); // Highlight the selected row
                                }
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>

</body>

</html>