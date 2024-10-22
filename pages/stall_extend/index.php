<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stall Extension Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../iamges/favicon_192.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/transfer_stall.css">
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

        <div class="container-fluid px-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6">
                    <div class="container shadow rounded-3 p-5 application light">
                        <h2 class="text-center mb-4">Stall Extension Application</h2>


                        <form id="application_form" action="../actions/stall_application_action.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                            <!-- Market Dropdown -->
                            <div class="mb-3">
                                <label for="market">Market:</label>
                                <select class="form-select" id="market" name="market" onchange="loadStallsWithSection()" required>
                                    <option value="">-- Select Market --</option>
                                </select>
                            </div>

                            <!-- Section and Stall (side by side) -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="section">Section:</label>
                                    <select class="form-select" id="section" name="section" onchange="loadStallsWithSection()">
                                        <option value="">-- Select Section --</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="stall">Stall Number:</label>
                                    <select class="form-select" id="stall" name="stall" required>
                                        <option value="">-- Select Stall Number --</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="duration">Duration:</label>
                                    <select class="form-select" id="duration" name="duration" required>
                                        <option value="">-- Select Extension Duration --</option>
                                        <option value=""> 1 Month</option>
                                        <option value=""> 3 Months</option>
                                        <option value=""> 6 Months</option>
                                        <option value=""> 1 Year</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Stall Info -->
                            <div id="stallInfo" class="stall-info mb-3">
                                Select a stall number to view information.
                            </div>
                            <br>
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-warning">Submit Application</button>
                            </div>
                        </form>

                        <div id="responseMessage" class="alert mt-3" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        let locationsData; // Store the fetched data for later use

        // checks the stall number option
        document.addEventListener('DOMContentLoaded', function() {
            const stallSelect = document.getElementById('stall');
            const stallInfo = document.getElementById('stallInfo');
            stallSelect.addEventListener('change', showStallInfo);
        });

        // checks the section option
        document.addEventListener('DOMContentLoaded', function() {
            const sectionSelect = document.getElementById('section');
            const stallInfo = document.getElementById('stallInfo');

            sectionSelect.addEventListener('change', function() {
                stallInfo.innerHTML = 'Select a stall number to view information.';
            });
        });

        document.getElementById('application_form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Create FormData object

            fetch('../actions/stall_application_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const responseMessage = document.getElementById('responseMessage');
                    if (data.success) {
                        responseMessage.innerHTML = `<div class="alert alert-success">${data.messages.join('<br>')}</div>`;
                        responseMessage.style.display = 'block';
                    } else {
                        responseMessage.innerHTML = `<div class="alert alert-danger">${data.messages.join('<br>')}</div>`;
                        responseMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('responseMessage').innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                });
        });

        window.onload = function() {
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

            // Load market sections on page load
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
        };

        // Display address based on selected market location
        document.getElementById('market').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedId = selectedOption.value;
            const selectedLocation = locationsData.find(location => location.id == selectedId);
            document.getElementById('market_address').innerText = selectedLocation ? selectedLocation.market_address : '';
        });

        // Function to load stalls based on selected market and section
        function loadStallsWithSection() {
            const marketId = document.getElementById('market').value;
            const sectionId = document.getElementById('section').value;

            // Only make the request if both market and section are selected
            if (marketId && sectionId) {
                loadStalls(marketId, sectionId);
            }
        }

        function loadStalls(marketId, sectionId) {
            // Fetch stalls based on market_id and section_id
            fetch('../actions/get_stalls.php?market_id=' + marketId + '&section_id=' + sectionId)
                .then(response => response.json())
                .then(data => {
                    let stallSelect = document.getElementById('stall');
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
            <table class="table table-striped table-borderless table-hover custom-table dark">
            <thead>
                <tr>
                    <th>Rental Fee</th>
                    <th>Stall Size</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${rentalFee}</td>
                    <td>${stallSize}</td>
                </tr>
            </tbody>
            </table>
            `;
            document.getElementById('stallInfo').innerHTML = stallInfo;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>