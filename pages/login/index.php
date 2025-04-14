<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Public Market Monitoring System</title>
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
    require_once '../../includes/session.php';

    // Check if user is logged in
    if (isset($_SESSION['account_id'])) {
        echo '<script>
            alert("You are already logged in.");
            window.location.href = "http://localhost/lgu_market_sys/";
           </script>';
        exit();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid vh-100">
        <div class="row w-100">
            <!-- Left Side: Form -->
            <div class="form-container col-md-4 p-5 m-5">

                <div class="form-container otp_form d-none" id="otp_div">
                    <div class="row justify-content-center w-75">
                        <form method="POST" class="p-4" id="otpForm">
                            <input type="hidden" name="otp_email" id="otpEmail">

                            <div class="mb-4 text-center">
                                <h3>Email Verification Required</h3>
                                <p class="text-muted">We’ve sent a 6-digit verification code to your email. Please enter it below to continue.</p>
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
                                <button type="submit" class="btn otp-button" id="verifyOtpBtn">Verify</button>
                            </div>
                        </form>
                        <div class="mb-3 text-center">
                            <span class="text-muted"><span id="otpTimer"><b>05:00</b></span></span>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-1">Didn't receive the code?</p>
                            <a href="#" id="resendOtpLink">Resend OTP</a>
                            <span id="resendStatus" class="text-muted d-block mt-2"></span>
                        </div>
                    </div>
                </div>

                <form class="ms-4 w-75 py-3 m-auto" method="POST" id="loginForm">
                    <div class="mb-5">
                        <h2 class="mb-3"> Log in to continue</h2>
                        <p class="text-muted">Use our services anytime you want.</p>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="mb-3">
                        <input type="text" id="email" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>

                    <button class="btn form-button" type="submit" id="loginBtn">
                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true" id="loginSpinner"></span>
                        <span id="loginStatus">Login</span>
                    </button>
                    <p class="my-4">Don't have an account? <a href="/lgu_market_sys/pages/signup/index.php">Sign up here</a></p>

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
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loginForm").addEventListener("submit", function(event) {
                validateForm(event);
            });
        });

        document.getElementById("resendOtpLink").addEventListener("click", async function(e) {
            e.preventDefault();
            const resendLink = this;
            const resendStatus = document.getElementById("otpTimer");
            const email = document.getElementById("otpEmail").value;
            const verifyBtn = document.getElementById("verifyOtpBtn");

            resendLink.style.pointerEvents = "none";
            clearInterval(window.otpInterval); // Always clear the old timer
            resendStatus.textContent = "Resending OTP...";
            verifyBtn.disabled = true;

            try {
                const response = await fetch("../actions/resend_otp.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const result = await response.json();
                console.log(result);
                resendStatus.textContent = result.message;

                if (result.success && result.otp_expiry && result.expires_in) {
                    const otpExpiry = new Date(result.otp_expiry).getTime() / 1000;
                    const now = Math.floor(Date.now() / 1000);
                    startOTPTimer(result.expires_in);
                    verifyBtn.disabled = false;
                }

                // Disable resend for 30 seconds
                let countdown = 30;
                const interval = setInterval(() => {
                    resendLink.textContent = `Resend OTP (${countdown}s)`;
                    if (--countdown < 0) {
                        clearInterval(interval);
                        resendLink.textContent = "Resend OTP";
                        resendLink.style.pointerEvents = "auto";
                    }
                }, 1000);
            } catch (error) {
                resendStatus.textContent = "Failed to resend OTP. Try again.";
                resendLink.style.pointerEvents = "auto";
            }
        });


        const verify_btn = document.getElementById("verifyOtpBtn");
        if (verify_btn) {
            verify_btn.addEventListener("click", async function(e) {
                e.preventDefault();
                await verifyOTP(); // Call the async function directly
            });
        }

        async function verifyOTP() {
            const form = document.getElementById("otpForm");
            const otpInputs = form.querySelectorAll("input[name='otp[]']");
            const otp_email = document.getElementById("otpEmail").value.trim();

            let otp = "";
            otpInputs.forEach(input => otp += input.value.trim());

            if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
                alert("Please enter a valid 6-digit code.");
                return;
            }

            // Prepare form data
            const formData = new URLSearchParams();
            formData.append("otp_email", otp_email);
            formData.append("otp", otp);

            try {
                const response = await fetch("../actions/verify_otp.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    displayToast(result.message, "success");
                    setTimeout(() => {
                        window.location.href = "http://localhost/lgu_market_sys/pages/login/";
                    }, 1500);
                } else {
                    displayToast(result.message, "error");
                }
            } catch (error) {
                console.error("Error:", error);
                alert("An error occurred while verifying OTP.");
            }
        }

        function validateForm(event) {
            event.preventDefault(); // Prevent default form submission
            let form = document.getElementById("loginForm");
            let formData = new FormData(form);

            let email = formData.get("email").trim();
            let password = formData.get("password").trim();

            if (email === "" || password === "") {
                displayToast("Fields must be filled out", "error");
                return;
            }

            if (!validateEmail(email)) {
                displayToast("Invalid Email Format", "error");
                return;
            }

            handleLogin(form);
        }

        async function handleLogin(form) {
            const formData = new FormData(form);
            let email = formData.get("email").trim();
            const submitButton = document.getElementById("loginBtn");
            const spinner = document.getElementById("loginSpinner");
            const statusText = document.getElementById("loginStatus");

            // Show spinner and update text
            spinner.classList.remove("d-none");
            statusText.textContent = "Verifying...";
            submitButton.disabled = true;

            try {

                const response = await fetch("../actions/login_action.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                console.log(data);
                if (data.isVerified === false) {
                    displayToast(data.message || "Please verify your email first.", "warning");
                    document.getElementById("otp_div").classList.remove("d-none");
                    document.getElementById("otp_div").classList.add("show");
                    document.getElementById("loginForm").classList.add("d-none");
                    document.getElementById("otpEmail").value = email;
                    startOTPTimer(300);
                } else {
                    if (data.success && data.isVerified) {

                        displayToast("Login successful!", "success");

                        let redirectUrl = data.user_type === 'Admin' ?
                            "http://localhost/lgu_market_sys/pages/admin/home/" :
                            "http://localhost/lgu_market_sys/";

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 1500);
                    } else {

                        displayToast(data.message, "error");
                        form.reset();
                    }
                }

            } catch (error) {
                console.error("Login Error:", error);
                displayToast("An error occurred. Please try again.", "error");
            } finally {
                // Reset button state
                spinner.classList.add("d-none");
                statusText.textContent = "Login";
                submitButton.disabled = false;
            }

        }

        function validateEmail(email) {
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        function startOTPTimer(duration) {
            let timer = duration;
            const display = document.getElementById("otpTimer");
            const verifyBtn = document.getElementById("verifyOtpBtn");

            if (window.otpInterval) clearInterval(window.otpInterval); // Clear previous

            window.otpInterval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;

                verifyBtn.disabled = false;

                display.textContent = `OTP Expires in: ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                if (--timer < 0) {
                    clearInterval(window.otpInterval);
                    display.textContent = "";
                    display.textContent = "OTP expired. Please request a new one.";
                    verifyBtn.disabled = true;
                    verifyBtn.classList.add("disabled");
                }
            }, 1000);
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