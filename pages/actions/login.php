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
    </style>
</head>

<body>

    <?php
    session_start();

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
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
                <h2 class="mb-3"> Log in to continue</h2>
                <p class="text-muted">Use our services anytime you want.</p>

                <form class="ms-4 w-75 py-3 m-auto" action="login_action.php" method="POST" id="login" onsubmit="return validateForm()">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="mb-3">
                        <input type="text" id="email" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>

                    <button class="form-button" type="submit">Login</button>
                    <p class="my-4">Don't have an account? <a href="/lgu_market_sys/pages/actions/signup.php">Sign up here</a></p>


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
            document.querySelector("form").addEventListener("submit", function(event) {
                validateForm(event);
            });
        });

        function validateForm(event) {
            event.preventDefault(); // Prevent default form submission

            let form = document.getElementById("login");
            let email = document.forms["login"]["email"].value.trim();
            let password = document.forms["login"]["password"].value.trim();

            if (email === "" || password === "") {
                displayToast("Fields must be filled out", "error");
                return;
            }

            if (!validateEmail(email)) {
                displayToast("Invalid Email Format", "error");
                return;
            }

            // Prepare form data
            let formData = new FormData(document.forms["login"]);

            // Send data to backend
            fetch("login_action.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayToast("Login successful!", "success");

                        // Redirect based on user type
                        let redirectUrl = data.user_type === 'Admin' ?
                            "http://localhost/lgu_market_sys/pages/admin/home/" :
                            "http://localhost/lgu_market_sys/";

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 1500);

                    } else {
                        form.reset();
                        displayToast(data.message, "error");
                    }
                })
                .catch(error => {
                    console.error("Login Error:", error);
                    displayToast("An error occurred. Please try again.", "error");
                });
        }

        function validateEmail(email) {
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
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
</body>

</html>