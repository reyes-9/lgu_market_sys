<!DOCTYPE html>
<html lang="en">
<?php
require_once '../../includes/session.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Vendor - Public Market Monitoring System</title>
    <?php include '../../includes/cdn-resources.php'; ?>
    <link rel="stylesheet" href="../../assets/css/toast.css">
    <style>
        @import url(../../assets/css/main.css);

        body {
            background-color: #f4f6f8;
        }

        .container-fluid {
            background-color: #f8f9fa;
        }


        .form-container {
            background-color: #f8f9fc;
            /* Soft white */
            color: #003366;
            border-radius: 8px;
            box-shadow: 0 1px 100px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 70% !important;
            margin: 30px auto;
        }

        .form-container h2 {

            color: #003366;
        }

        .form-section {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 50px;
            color: #003366;
            border-bottom: 2px solid #ffc107;
            /* Keeping the warning accent */
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
            color: #003366;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #bbb;
            /* Lighter border */
        }

        .form-control:focus {
            border-color: #003366;
            box-shadow: 0 0 5px rgba(0, 51, 102, 0.3);
        }

        .form-button {
            display: block;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            flex: 1;
            width: 100px;
            max-width: 200px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px auto;
        }

        .form-button:hover {
            background-color: #002447;
        }

        .row {
            margin-bottom: 25px;
        }

        input,
        select {
            background-color: #ffffff !important;
            /* Pure white for better contrast */
            color: #003366 !important;
            border-color: #bbb !important;
        }

        .error {
            border-color: #d32f2f !important;
        }

        .error-message {
            color: #d32f2f !important;
            font-size: 0.85rem !important;
            display: inline !important;
        }

        #mobileError {
            color: #d32f2f !important;
            font-size: 0.85rem !important;
        }

        #zipError {
            color: #d32f2f !important;
            font-size: 0.85rem !important;
        }

        #emailError {
            color: #d32f2f !important;
            font-size: 0.85rem !important;
        }

        #altEmailError {
            color: #d32f2f !important;
            font-size: 0.85rem !important;
        }

        .dropdown-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .dropdown-wrapper select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 40px;
        }

        /* Style the dropdown icon */
        .dropdown-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            pointer-events: none;
            color: #555;
        }
    </style>
</head>
<?php


// Get session status

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body>
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>

    <?php include '../../includes/nav.php'; ?>

    <div class="form-container">
        <h2>Create Vendor Profile</h2>
        <p class="text-muted">Apply for market stalls, track your application status, and manage your vendor profile with ease.</p>

        <div class="form-section">Personal Information</div>


        <form id="detailsForm" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Email: <small class="error-message"></small></label>
                    <input type="email" class="form-control" id="email" name="email" readonly>
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
                            <option value="Other">Other</option>
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

            <div class="row my-3 ">
                <div class="form-group col-md-4">
                    <label>Zip Code: <small class="error-message"></small></label>
                    <input type="text" class="form-control" id="zipcode" name="zip_code">
                    <small id="zipError" class="d-none">ZIP code must be exactly 4 digits.</small>
                </div>
            </div>
            <button type="button" class="form-button" id="regBtn">Create</button>
        </form>


    </div>


    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/toast.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("regBtn").addEventListener("click", validateDetailsForm);
            getEmail();
        });

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

                submitForm();

            } else {
                displayToast("Please complete all required fields", "error");
            }
        }

        function submitForm() {

            const form = document.getElementById("detailsForm");
            const formData = new FormData(form);


            console.log("Form Data:");
            for (const [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }


            fetch("../actions/create_profile.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayToast("Registration successful!", "success");
                        form.reset();
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        console.error(data.message);
                        displayToast("Registration failed. Please try again.", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    displayToast("An error occurred. Please try again later.", "error");
                });
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

        function getEmail() {
            fetch('../actions/get_email.php')
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        document.getElementById('email').value = data.email;
                    } else {
                        console.error('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching email:', error);
                });
        }
    </script>


</body>

</html>