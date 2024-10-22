<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Monitoring Module</title>
</head>
<style>
    body {
        background-color: #F5F5F5;
    }

    header {
        background-color: #282828;
        padding: 15px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    nav ul li {
        margin: 0 15px;
    }

    nav ul li a {
        color: #ffffff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    nav ul li a:hover {
        color: #4caf50;
    }

    .center {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: auto;
        width: 50%;
        height: 100vh;
        /* This will center the content vertically as well */
    }

    .center span {
        margin-bottom: 10px;
        display: flex;
        justify-content: space-evenly;
        width: 50%;
        /* Make the span take full width to align text and input */
    }

    .center input {
        flex-grow: 1;
        /* Input will take the remaining space */
        margin-left: 5%;
        height: 20px;
    }

    .custom-button {
        background-color: #3498db;
        /* Button background color */
        border: none;
        /* Remove default borders */
        color: white;
        /* Text color */
        padding: 12px 24px;
        /* Padding inside the button */
        text-align: center;
        /* Center the text */
        text-decoration: none;
        /* Remove underline */
        display: inline-block;
        /* Keep inline with other elements */
        font-size: 16px;
        /* Font size */
        font-weight: bold;
        /* Bold text */
        border-radius: 8px;
        /* Rounded corners */
        cursor: pointer;
        /* Change cursor on hover */
        transition: background-color 0.3s, transform 0.3s;
        /* Smooth transition for hover effects */
    }

    /* Hover effect */
    .custom-button:hover {
        background-color: #2980b9;
        /* Darker background on hover */
        transform: scale(1.05);
        /* Slightly enlarge the button on hover */
    }

    /* Active state (when clicked) */
    .custom-button:active {
        background-color: #1d6fa5;
        /* Even darker background when active */
        transform: scale(0.98);
        /* Slightly shrink on click */
    }

    .styled-input {
        padding: 12px;
        font-size: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        width: 80%;
        margin: 8px 0;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .styled-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.5);
        outline: none;
    }

    .center {
        margin: auto;
        width: 50%;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }
</style>

<?php
session_start();

// Generate a new CSRF token if one doesn't already exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body>
    <div class="center">
        <h2>Login</h2>
        <hr>
        <form action="login_action.php" method="POST" id="login" onsubmit="return validateForm()">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="styled-input">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="styled-input">
            </div>

            <button class="custom-button" type="submit">Login</button>
        </form>
    </div>
</body>

<script>
    function validateEmail(email) {
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailPattern.test(email); // Returns true or false
    }

    function validateForm() {
        let email = document.forms["login"]["email"].value;
        let password = document.forms["login"]["password"].value;
        let isValid = true;

        if (validateEmail(email) === false) {
            alert("Invalid Email Format");
            isValid = false;
        }
        // Check if all inputs are filled out
        if (email === "" || password === "") {
            alert("Fields must be filled out");
            isValid = false;
        }
        return isValid;
    }
</script>

</html>