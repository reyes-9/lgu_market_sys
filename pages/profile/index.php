<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <?php include_once "../../includes/cdn-resources.php" ?>
</head>

<body>
    <?php include '../../includes/nav.php'; ?>

    <div class="profile-header">
        <h1>Hello, Jesse</h1>
        <p>Welcome to your profile! View your stall applications, track approvals, manage your vendor details, and stay updated on market activities.</p>

        <!-- <button class="btn btn-info btn-edit">Edit profile</button> -->
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-4 edit-profile">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-4 profile-title">
                        <h4>Profile</h4>
                        <button class="btn btn-sm btn-dark btn-settings"> Edit </button>
                    </div>
                    <form class="" id="detailsForm" method="POST" action="">
                        <!-- Personal Information -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

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
                                <input type="text" class="form-control" id="firstName" name="first_name">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Middle Name: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="middleName" name="middle_name">
                                <small>Type N/A if you don't have middle name</small>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last Name: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="lastName" name="last_name">
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
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>House Number: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="houseNumber" name="house_no">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Street: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="street" name="street">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Subdivision: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="subdivision" name="subdivision">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group col-md-4">
                                <label>Province: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="province" name="province">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Barangay: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="barangay" name="barangay">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group col-md-4">
                                <label>Zip Code: <small class="error-message"></small></label>
                                <input type="text" class="form-control" id="zipcode" name="zip_code">
                                <small id="zipError" class="d-none">ZIP code must be exactly 4 digits.</small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center">
                    <h5>Jessica Jones, 27</h5>
                    <p>Bucharest, Romania</p>
                    <hr>
                    <!-- <button class="btn btn-info mb-2">Connect</button>
                    <button class="btn btn-dark">Message</button> -->
                    <div class="mt-1">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>22</strong></td>
                                    <td>Stalls</td>
                                </tr>
                                <tr>
                                    <td><strong>10</strong></td>
                                    <td>Extensions</td>
                                </tr>
                                <tr>
                                    <td><strong>89</strong></td>
                                    <td>Violations</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <p><span class="badge bg-primary">Vendor</span> - <span> San Jose Public Market </span></p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>

    <script>
        // Function to fetch user data from the backend
        function fetchUserData() {
            // The URL where the PHP script is located
            const url = '../actions/get_profile_data.php';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.log(data.error);
                    } else {
                        // Fill in the form fields with the user data
                        document.getElementById('email').value = data.email || '';
                        document.getElementById('altEmail').value = data.alt_email || '';
                        document.getElementById('mobile').value = data.contact_no || '';
                        document.getElementById('firstName').value = data.first_name || '';
                        document.getElementById('middleName').value = data.middle_name || '';
                        document.getElementById('lastName').value = data.last_name || '';
                        document.querySelector('select[name="sex"]').value = data.sex || '';
                        document.querySelector('select[name="civil_status"]').value = data.civil_status || '';
                        document.getElementById('nationality').value = data.nationality || '';
                        document.getElementById('houseNumber').value = data.house_no || '';
                        document.getElementById('street').value = data.street || '';
                        document.getElementById('subdivision').value = data.subdivision || '';
                        document.getElementById('province').value = data.province || '';
                        document.getElementById('city').value = data.city || '';
                        document.getElementById('barangay').value = data.barangay || '';
                        document.getElementById('zipcode').value = data.zip_code || '';
                    }
                })
                .catch(error => {
                    console.log('Error fetching user data:', error);
                });
        }

        // Call the function to fetch the data when the page loads
        window.onload = fetchUserData;
    </script>

</body>

</html>