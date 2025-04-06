<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Public Market Monitoring System</title>
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

        .form-control {
            border-radius: 8px;
            height: 45px;
        }

        .form-container {
            background-color: #f8f9fc;
            color: #003366 !important;
            border-radius: 8px;
            /* box-shadow: -10px 0 40px rgba(0, 0, 0, 0.1); */
            padding: 30px;
        }

        input {
            color: #003366 !important;
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
        }

        .form-button:hover {
            background-color: rgb(2, 40, 78);
            color: white;
        }

        /* OTP */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 18px;
            max-width: 350px;
            margin: 0 auto;
        }

        .otp-box {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 22px;
            border: 2px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .otp-box:focus {
            border-color: #007bff;
        }


        .otp-button {
            background-color: #003366;
            color: white;
        }

        .otp-button:hover {
            background-color: rgb(2, 40, 78);
            color: white;
        }

        /* Add animation to the form when it becomes visible */
        .otp_form.show {
            animation: slideUp 0.1s ease-out;
            display: block;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* Slide-up animation */
        @keyframes slideUp {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <?php
    session_start();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid vh-100">
        <div class="row w-100">
            <!-- Left Side: Form -->
            <div class=" form-container col-md-4 p-5 m-5">

                <h2 class="mb-3"> Sign Up for Public Market Monitoring System</h2>
                <p class="text-muted">Create an account to view markets and stalls. </p>

                <div class="form-container otp_form d-none">
                    <div class="row justify-content-center w-75">
                        <form method="POST" action="verify.php" class="p-4">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? '') ?>">

                            <div class="mb-4 text-center">
                                <h3>Enter Code</h3>
                                <p class="text-muted">We've already sent the code, please check your email.</p>
                            </div>

                            <div class="otp-container mb-4 text-center">
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required autofocus>
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required>
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required>
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required>
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required>
                                <input type="text" name="otp[]" maxlength="1" class="otp-box form-control d-inline-block text-center bg-white rounded shadow" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn otp-button">Verify</button>
                            </div>
                        </form>
                    </div>
                </div>

                <form class="ms-4 w-75 py-3 m-auto" id="signupForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                    </div>
                    <p class="text-danger" id="emailError"></p>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirm_password" required>
                        <button type="button" class="btn  border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('confirmPassword', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                    <p class="text-danger" id="passwordError"></p>
                    <button type="submit" class="form-button">Sign Up</button>

                    <p class="my-4">Already have an account? <a href="/lgu_market_sys/pages/login/index.php">Login here</a></p>

                    <p class="text-muted mt-3 text-center small">
                        By proceeding, you agree to the
                        <a href="#" class="text-decoration-none">Terms and Conditions</a> and
                        <a href="#" class="text-decoration-none">Privacy Policy</a>.
                    </p>
                </form>

            </div>

            <!-- Right Side: Illustration -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-end text-white p-0">
                <div class="w-100 text-end">
                    <img src="../../assets/images/24237610_6922108.jpg" class="img-fluid rounded w-75 m-5" alt="Illustration">
                </div>
            </div>
        </div>
    </div>
    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/toast.js"></script>
    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirmPassword").value;
            let passwordError = document.getElementById("passwordError");

            // Email validation (basic regex check)
            let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return;
            }
            if (password.length < 8) {
                passwordError.textContent = "Password must be at least 8 characters long.";
                displayToast('Password too short', "error");
                return;
            } else {
                passwordError.textContent = "";
            }

            if (password !== confirmPassword) {
                passwordError.textContent = "Passwords do not match.";
                displayToast('Incorrect Inputs', "error");
                return;
            } else {
                passwordError.textContent = "";
            }

            submitSignupForm();
        });


        function submitSignupForm() {
            let formData = new FormData(document.getElementById("signupForm"));

            fetch("../actions/signup_action.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    displayToast(data.message, data.success ? "success" : "error");
                    if (data.success) {
                        document.getElementById("signupForm").reset();

                        var otpForm = document.querySelector(".otp_form");
                        otpForm.classList.remove("d-none"); // Remove the hidden class
                        otpForm.classList.add("show"); // Add the animation class to trigger animation

                        // setTimeout(function() {
                        //     window.location.href = "/lgu_market_sys/pages/login/index.php";
                        // }, 2000);
                    }

                })
                .catch(error => {
                    console.error("Error:", error);
                    displayToast("Something went wrong. Please try again.", "error");
                });
        }
    </script>

    <script>
        function togglePassword(inputId, button) {
            let passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                button.innerHTML = '<i class="bi bi-eye-slash-fill"></i>'; // Change icon to 'hide'
            } else {
                passwordInput.type = "password";
                button.innerHTML = '<i class="bi bi-eye-fill"></i>'; // Change icon to 'show'
            }
        }
    </script>
    <script>
        document.querySelectorAll('.otp-box').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {

                // Automatically move focus to the next input box
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // If user deletes a value, focus on the previous input
                if (input.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>

</html>