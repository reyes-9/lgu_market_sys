<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Stall Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/stall_app.css">
    <link rel="stylesheet" href="../../assets/css/toast.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>

<style>
    .dynamic-section {
        display: none;
    }
</style>
<script>

</script>

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
                        <div class="col-md-6"><strong>Application Type:</strong> STALL TRANSFER/SUCCESSION</div>
                        <div class="col-md-6"><strong>Application Status:</strong> New</div>
                        <div class="col-md-6"><strong>Date Submitted:</strong> <span id="current_date"></span> </div>
                        <div class="col-md-6"><strong>Application Form Number:</strong> <span id="app_number"></span></div>
                    </div>
                </div>
            </div>

            <!-- Response Modal -->
            <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
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

            <h2 class="text-center mb-4">Transfer Stall Application</h2>

            <div class="mb-3">
                <form action="" id="transferTypeForm">
                    <div class="form-section">Transfer Type</div>
                    <div class="form-group">

                        <label class="mx-3">Select transfer type: <small class="error-message"></small></label>

                        <input type="radio" class="btn-check" id="transfer" name="application_type" value="Transfer">
                        <label class="btn btn-outline-primary mx-2" id="transferLbl" for="transfer">Transfer</label>

                        <input type="radio" class="btn-check" id="succession" name="application_type" value="Succession">
                        <label class="btn btn-outline-primary mx-2" id="successionLbl" for="succession">Succession</label>
                    </div>
                </form>
            </div>

            <form class="pt-2" id="marketSelectionForm">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div class="form-section">Select Market</div>
                <!-- Market Dropdown -->
                <small> <strong>Note:</strong> Only occupied stalls are displayed for selection.</small>
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

            <form class="d-none" id="transferDetailsForm">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="form-section">Transfer Details</div>
                <!-- Reason for Transfer (Optional) -->
                <div class="form-group mb-3">
                    <label for="transfer_reason">Reason for Transfer:</label>
                    <textarea class="form-control" id="transfer_reason" name="transfer_reason" rows="3" placeholder="Provide the reason for transferring the stall (optional)"></textarea>
                </div>

                <!-- Current Stall Owner Information (Optional) -->
                <div class="row mt-2">
                    <div class="form-group"> <label for="">(Current Stall Owner Name)</label> </div>
                    <div class="form-group col-md-4">
                        <label>First Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="current_first_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Middle Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="current_middle_name">
                        <small>Type N/A if you don't have middle name</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Last Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="current_last_name">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="current_owner_id">Current Stall Owner ID:<small class="error-message"></small></label>
                    <input type="text" class="form-control" name="current_owner_id" />
                </div>

                <!-- New Stall Owner Information -->
                <!-- <div class="form-section">New Owner Personal Information</div>
                <div class="row mt-2" id="newOwnerInfoTransfer">

                </div> -->

                <!-- Submit Button -->
                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('transferDetailsForm', 'marketSelectionForm')">Back</button>
                    <button type="button" class="form-button" id="transferDetailsBtn">Next</button>
                </div>
            </form>

            <form class="d-none" id="transferDocumentsForm" enctype="multipart/form-data">
                <div class="form-section">Current Owner Documents</div>
                <div class="form-group mb-3">
                    <label>Deed of Transfer (Photo) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="deedOfTransfer" name="deed_of_transfer" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="deedOfTransferError"></small>
                </div>
                <div class="form-group">
                    <label>Valid ID Type <small class="text-danger">*</small></label>
                    <select class="form-control" id="currOwnerValidIdType" name="valid_id_type_curr">
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
                    <label>Valid ID <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="currOwnerValidIdFile" name="valid_id_file_curr" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="currOwnerValidIdFIleError"></small>
                </div>

                <div class="form-section">New Owner Documents</div>

                <div class="form-group mb-3">
                    <label>Barangay Clearance (Photo) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="barangayClearanceTransfer" name="barangay_clearance_transfer" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="barangayClearanceTransferError"></small>
                </div>

                <div class="form-group mb-3">
                    <label>Community Tax Certificate / Cedula (Photo) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="communityTaxCertTransfer" name="community_tax_cert_transfer" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="communityTaxCertTransferError"></small>
                </div>

                <div class="form-group">
                    <label>Valid ID Type <small class="text-danger">*</small></label>
                    <select class="form-control" id="newOwnerValidIdType" name="valid_id_type_new">
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
                    <input type="file" class="form-control" id="newOwnerValidIdFile" name="valid_id_file_new" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="newOwnerValidIdFIleError"></small>
                </div>

                <input type="hidden" id="applicationNumber" name="application_number" value="">

                <!-- Submit Button -->
                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('transferDocumentsForm', 'transferDetailsForm')">Back</button>
                    <button type="submit" class="form-button" id="transferDetailsBtn">Submit</button>
                </div>
            </form>

            <form class="d-none" id="successionDetailsForm">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <!-- Deceased Owner Information -->
                <div class="form-section">Deceased Owner Information</div>
                <div class="row mt-2">
                    <div class="form-group"> <label for="">(Deceased Owner Name)</label> </div>
                    <div class="form-group col-md-4">
                        <label>First Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="deceased_first_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Middle Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="deceased_middle_name">
                        <small>Type N/A if you don't have middle name</small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Last Name: <small class="error-message"></small></label>
                        <input type="text" class="form-control" name="deceased_last_name">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="deceased_owner_id">Deceased Stall Owner ID:<small class="error-message"></small></label>
                    <input type="text" class="form-control" name="deceased_owner_id" />
                </div>

                <!-- Successor Information -->
                <!-- <div class="form-section">Successor Personal Information</div>
                <div class="row mt-2" id="newOwnerInfoSuccession">

                </div> -->

                <!-- Relationship to Deceased (Optional) -->
                <div class="mb-3">
                    <label for="relationship_to_deceased">Relationship to Deceased (Optional):</label>
                    <input type="text" class="form-control" id="relationship_to_deceased" name="relationship_to_deceased" placeholder="Enter the relationship to the deceased (if applicable)" />
                </div>

                <!-- Submit Button -->
                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('successionDetailsForm', 'marketSelectionForm')">Back</button>
                    <button type="button" class="form-button" id="successionDetailsBtn">Next</button>
                </div>
            </form>

            <form class="d-none" id="successionDocumentsForm" enctype="multipart/form-data">

                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="form-group mb-3">
                    <label for="death_certificate">Death Certificate of the Deceased Stall Owner:</label>
                    <input type="file" class="form-control" id="deathCert" name="death_cert" accept=".pdf, .jpg, .jpeg, .png" />
                    <small class="error-message" id="deathCertError"></small>
                </div>

                <div class="form-group mb-3">
                    <label for="death_certificate">Proof of Relationship (new owner):</label>
                    <input type="file" class="form-control" id="proofOfRelationship" name="proof_of_relationship" accept=".pdf, .jpg, .jpeg, .png" />
                    <small class="error-message" id="proofOfRelationshipError"></small>
                </div>

                <div class="form-group mb-3">
                    <label>Upload Barangay Clearance (Photo) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="barangayClearanceSuccession" name="barangay_clearance_succession" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="barangayClearanceSuccessionError"></small>
                </div>
                <div class="form-group mb-3">
                    <label>Upload Community Tax Certificate / Cedula (Photo) <small class="text-danger">*</small></label>
                    <input type="file" class="form-control" id="communityTaxCertSuccession" name="community_tax_cert_succession" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="communityTaxCertSuccessionError"></small>
                </div>

                <div class="form-group">
                    <label>Valid ID Type <small class="text-danger">*</small></label>
                    <select class="form-control" id="successionValidIdType" name="valid_id_type_succession">
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
                    <input type="file" class="form-control" id="successionValidIdFile" name="valid_id_file_succession" accept=".pdf, .jpg, .jpeg, .png">
                    <small class="error-message" id="successionValidIdFileError"></small>
                </div>

                <!-- Submit Button -->
                <div class="container d-flex  justify-content-center gap-5">
                    <button type="button" class="form-button" onclick="switchForm('successionDocumentsForm', 'successionDetailsForm')">Back</button>
                    <button type="submit" class="form-button" id="successionDetailsBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>
    <script src="../../assets/js/toast.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const appType = "";
            const transferForm = document.getElementById("transferDocumentsForm");
            const successionForm = document.getElementById("successionDocumentsForm");

            document.getElementById("marketBtn").addEventListener("click", handleMarketSelection);
            document.getElementById("transferDetailsBtn").addEventListener("click", validateDetailsForm);
            document.getElementById("successionDetailsBtn").addEventListener("click", validateDetailsForm);
            getCurrentDate();

            if (transferForm) {
                transferForm.addEventListener("submit", handleDocumentFormSubmission);
            }
            if (successionForm) {
                successionForm.addEventListener("submit", handleDocumentFormSubmission);
            }

            generateAndSetAppNumber();

        });

        function handleDocumentFormSubmission(event) {
            event.preventDefault();

            const documentForm = event.target;
            const appType = document.querySelector('input[name="application_type"]:checked')?.value;

            if (!appType) {
                displayToast("Please select an application type", "error");
                return;
            }

            // Validate documents based on application type
            if (typeof validateDocumentsForm === "function" && !validateDocumentsForm(appType)) {
                return;
            }

            const formData = new FormData(documentForm);
            formData.append("application_type", appType);
            const fields = ["market", "section", "stall"];

            // Append market, section, and stall IDs directly to FormData
            fields.forEach(field => {
                let input = document.getElementById(field + "Input");
                let fieldValue = input?.getAttribute("data-" + field + "-id");
                if (fieldValue) {
                    formData.append(field + "_id", fieldValue);
                }
            });

            // Append application number to FormData
            let applicationNumber = document.getElementById('applicationNumber').value;
            formData.append("application_number", applicationNumber);

            // Determine detailsForm and URL
            let detailsForm;
            if (appType === "Transfer") {
                detailsForm = document.getElementById("transferDetailsForm");
            } else if (appType === "Succession") {
                detailsForm = document.getElementById("successionDetailsForm");
            } else {
                displayToast("Invalid application type", "error");
                return;
            }

            // Ensure detailsForm exists before appending data
            if (detailsForm) {
                new FormData(detailsForm).forEach((value, key) => {
                    if (!formData.has(key)) {
                        formData.append(key, value);
                    }
                });
            }

            // Submit the form via AJAX
            fetch("../actions/submit_transfer_app.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayToast("Your application has been submitted successfully!", "success");
                        documentForm.reset();
                        if (detailsForm) detailsForm.reset();
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        displayToast(data.message || "An error occurred while submitting the application", "error");
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

        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Adds leading zero if necessary
            const day = String(today.getDate()).padStart(2, '0'); // Adds leading zero if necessary
            const dateHTML = document.getElementById('current_date');
            dateHTML.textContent = `${year}-${month}-${day}`;
        }

        function generateAndSetAppNumber() {
            // Function to fetch the last application ID from the server
            function getLastApplicationId() {
                return $.ajax({
                    url: '../actions/get_last_app_id.php',
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

                });
            }).fail(function() {
                console.error('Error fetching last application ID');
            });
        }

        function getSelectedText(selectId) {
            const selectElement = document.getElementById(selectId);
            if (selectElement.selectedIndex !== -1) {
                return selectElement.options[selectElement.selectedIndex].text;
            }
            return "Not selected";
        }

        function handleMarketSelection() {
            const marketSelect = document.getElementById("market");
            const sectionSelect = document.getElementById("section");
            const stallSelect = document.getElementById("stall");

            const marketVal = marketSelect.value;
            const sectionVal = sectionSelect.value;
            const stallVal = stallSelect.value;

            const selectedValue = document.querySelector('input[name="application_type"]:checked');
            const transferLabel = document.getElementById("transferLbl");
            const successionLabel = document.getElementById("successionLbl");

            let isValid = true;

            if (!selectedValue) {
                transferLabel.classList.add("error");
                successionLabel.classList.add("error");
                isValid = false;
            } else {
                transferLabel.classList.remove("error");
                successionLabel.classList.remove("error");
                appType = selectedValue.value;
            }

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
                const transferDetailsForm = document.getElementById("transferDetailsForm");
                const marketForm = document.getElementById("marketSelectionForm");
                const transferTypeForm = document.getElementById("transferTypeForm");

                if (selectedValue.value === "Transfer") {
                    updateForm(transferDetailsForm, [marketForm, transferTypeForm], marketVal, sectionVal, stallVal);
                } else if (selectedValue.value === "Succession") {
                    updateForm(successionDetailsForm, [marketForm, transferTypeForm], marketVal, sectionVal, stallVal);
                }

            } else {
                displayToast("Please complete all required fields", "error");
            }
        }

        function updateForm(showForm, hideForms, marketVal, sectionVal, stallVal) {
            // Check if the form is a details form
            const isDetailsForm = showForm.id === "transferDetailsForm" || showForm.id === "successionDetailsForm";

            if (isDetailsForm) {
                const existingRow = document.getElementById("marketInfoRow");
                if (existingRow) {
                    existingRow.remove();
                }

                const marketInfoRow = document.createElement("div");
                marketInfoRow.classList.add("row");
                marketInfoRow.id = "marketInfoRow";
                marketInfoRow.innerHTML = `
            <div class="form-section">Market Details</div>
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
            `;

                let marketTxt = getSelectedText("market");
                let sectionTxt = getSelectedText("section");
                let stallTxt = getSelectedText("stall");

                // Show the selected form and prepend the market info row
                showForm.classList.remove("d-none");
                showForm.prepend(marketInfoRow);

                const marketInput = document.getElementById("marketInput");
                const sectionInput = document.getElementById("sectionInput");
                const stallInput = document.getElementById("stallInput");

                marketInput.value = marketTxt;
                sectionInput.value = sectionTxt;
                stallInput.value = stallTxt;

                marketInput.dataset.marketId = marketVal;
                sectionInput.dataset.sectionId = sectionVal;
                stallInput.dataset.stallId = stallVal;
            } else {
                // Show the selected form without adding the market info row
                showForm.classList.remove("d-none");
            }

            // Hide other forms
            hideForms.forEach(form => form.classList.add("d-none"));

        }


        function validateDetailsForm() {
            let isValid = true;

            const selectedRadio = document.querySelector('input[name="application_type"]:checked');

            const transferDetailsForm = document.getElementById("transferDetailsForm");
            const transferDocumentsForm = document.getElementById("transferDocumentsForm");
            const transferInputs = transferDetailsForm.querySelectorAll("input, select");

            const successionDetailsForm = document.getElementById("successionDetailsForm");
            const successionDocumentsForm = document.getElementById("successionDocumentsForm");
            const successionInputs = successionDetailsForm.querySelectorAll("input, select");

            if (selectedRadio.value === "Transfer") {
                transferInputs.forEach(input => {
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
            } else if (selectedRadio.value === "Succession") {
                successionInputs.forEach(input => {
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
            }

            if (isValid) {
                if (selectedRadio.value === "Transfer") {
                    updateForm(transferDocumentsForm, [transferDetailsForm], "", "", "", "");
                } else if (selectedRadio.value === "Succession") {
                    updateForm(successionDocumentsForm, [successionDetailsForm], "", "", "", "");
                } else {
                    alert("No option selected.");
                }
            } else {
                displayToast("Please complete all required fields", "error");
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

        function validateSelect(input) {

            if (input.value.trim() === "") {
                input.classList.add("error");
                return false;
            } else {
                input.classList.remove("error");
                return true;
            }
        }

        function validateDocumentsForm(type) {

            let isValid = true;


            if (type === "Transfer") {

                const deedOfTransferInput = document.getElementById("deedOfTransfer");
                const currOwnerValidIdTypeInput = document.getElementById("currOwnerValidIdType");
                const currOwnerValidIdFileInput = document.getElementById("currOwnerValidIdFile");
                const barangayClearanceTransferInput = document.getElementById("barangayClearanceTransfer");
                const communityTaxCertTransferInput = document.getElementById("communityTaxCertTransfer");
                const newOwnerValidIdTypeInput = document.getElementById("newOwnerValidIdType");
                const newOwnerValidIdFileInput = document.getElementById("newOwnerValidIdFile");

                const deedOfTransferError = document.getElementById("deedOfTransferError");
                const currOwnerValidIdFIleError = document.getElementById("currOwnerValidIdFIleError");
                const barangayClearanceTransferError = document.getElementById("barangayClearanceTransferError");
                const communityTaxCertTransferError = document.getElementById("communityTaxCertTransferError");
                const newOwnerValidIdFIleError = document.getElementById("newOwnerValidIdFIleError");


                if (!validateFile(deedOfTransferInput, deedOfTransferError)) {
                    isValid = false;
                }

                if (!validateFile(currOwnerValidIdFileInput, currOwnerValidIdFIleError)) {
                    isValid = false;
                }

                if (!validateFile(barangayClearanceTransferInput, barangayClearanceTransferError)) {
                    isValid = false;
                }
                if (!validateFile(communityTaxCertTransferInput, communityTaxCertTransferError)) {
                    isValid = false;
                }

                if (!validateFile(newOwnerValidIdFileInput, newOwnerValidIdFIleError)) {
                    isValid = false;
                }

                if (!validateSelect(currOwnerValidIdTypeInput)) {
                    isValid = false;
                }

                if (!validateSelect(newOwnerValidIdTypeInput)) {
                    isValid = false;
                }

                if (!isValid) {
                    displayToast("Please complete all required fields", "error");
                }
            }
            if (type === "Succession") {

                const death_cert = document.getElementById("deathCert");
                const proofOfRelationship = document.getElementById("proofOfRelationship");
                const barangayClearanceSuccession = document.getElementById("barangayClearanceSuccession");
                const communityTaxCertSuccession = document.getElementById("communityTaxCertSuccession");
                const successionValidIdType = document.getElementById("successionValidIdType");
                const successionValidIdFile = document.getElementById("successionValidIdFile");

                const deathCertError = document.getElementById("deathCertError");
                const proofOfRelationshipError = document.getElementById("proofOfRelationshipError");
                const barangayClearanceSuccessionError = document.getElementById("barangayClearanceSuccessionError");
                const communityTaxCertSuccessionError = document.getElementById("communityTaxCertSuccessionError");
                const successionValidIdFileError = document.getElementById("successionValidIdFileError");

                if (!validateFile(death_cert, deathCertError)) {
                    isValid = false;
                }

                if (!validateFile(proofOfRelationship, proofOfRelationshipError)) {
                    isValid = false;
                }

                if (!validateFile(barangayClearanceSuccession, barangayClearanceSuccessionError)) {
                    isValid = false;
                }

                if (!validateFile(communityTaxCertSuccession, communityTaxCertSuccessionError)) {
                    isValid = false;
                }

                if (!validateFile(successionValidIdFile, successionValidIdFileError)) {
                    isValid = false;
                }

                if (!validateSelect(successionValidIdType)) {
                    isValid = false;
                }

                if (!isValid) {
                    displayToast("Please complete all required fields", "error");
                }

            }

            return isValid;
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

                    // Filter unavailable stalls (not 'available')
                    let unavailableStalls = data.unavailable_stalls || [];

                    if (unavailableStalls.length === 0) {
                        message.innerHTML = `<p style="color: #d32f2f"><strong>There are no occupied stalls available in this section</strong></p>`;
                        stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';
                        return;
                    }

                    message.innerHTML = '';
                    stallSelect.innerHTML = '<option value="">-- Select Stall Number --</option>';

                    unavailableStalls.forEach(stall => {
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