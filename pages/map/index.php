<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/map.css">
    <!-- maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <title>Vendor Mapping - Public Market Monitoring System</title>
</head>

<body class="body light">
    <?php include '../../includes/nav.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row m-4 p-5 shadow rounded-3 mapping">
                <h2 class="text-center m-5 p-3 mt-0"><strong>Vendor Mapping</strong></h2>
                <div class="container">
                    <div class="row">
                        <!-- Left Section: Map and Select Market -->
                        <div class="col-md-6 p-5">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="market">Market:</label>
                                    <select class="form-select" id="market" name="market" required>
                                        <option value="">-- Select Market --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="info-box my-3">
                                <div class="header">Address</div>
                                <div class="content" id="market_address"></div>
                                <div class="header">Market Info</div>
                                <div class="content">Stall Count: <span id="stall_count"></span></div>
                                <div class="content">Vacant: <span id="stall_vacant"></span></div>
                                <div class="content">Occupied: <span id="stall_occupied"></span></div>
                            </div>
                            <div id="responseContainer"></div>
                            <div>
                                <button class="btn btn-warning mb-3" id="viewStallsBtn" onclick=showStallMap() disabled>View Stalls</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="ratio ratio-16x9 mb-3">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241.20947734052658!2d121.0301259460395!3d14.69269586486612!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b1296d715513%3A0xf046d7e9b662dc43!2sSauyo%20Dry%20and%20Wet%20Market%2C%20Don%20Julio%20Gregorio%2C%20Novaliches%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1738467641276!5m2!1sen!2sph"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-5 p-0 map-section" id="map_section">
                <div class="container text-center my-5">
                    <h2>Market Stalls Map</h2>

                    <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                        <!-- 1x7 Red Stall (Single Row of 7 Squares) -->
                        <g fill="#ff4d4d" stroke="#000" stroke-width="1">
                            <rect x="10" y="10" width="20" height="20" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="30" y="10" width="20" height="20" class="stall" />
                            <rect x="50" y="10" width="20" height="20" class="stall" />
                            <rect x="70" y="10" width="20" height="20" class="stall" />
                            <rect x="90" y="10" width="20" height="20" class="stall" />
                            <rect x="110" y="10" width="20" height="20" class="stall" />
                            <rect x="130" y="10" width="20" height="20" class="stall" />
                        </g>
                    </svg>

                    <svg width="1000" height="1000" xmlns="http://www.w3.org/2000/svg">
                        <!-- 1x7 Red Stall (Single Row of 7 Squares) -->
                        <g fill="#0362fc" stroke="#000" stroke-width="1">
                            <rect x="10" y="10" width="20" height="20" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="10" y="30" width="20" height="20" class="stall" />
                            <rect x="10" y="50" width="20" height="20" class="stall" />
                            <rect x="10" y="70" width="20" height="20" class="stall" />
                            <rect x="10" y="90" width="20" height="20" class="stall" />
                            <rect x="10" y="110" width="20" height="10" class="stall" />
                            <rect x="10" y="120" width="20" height="10" class="stall" />
                            <rect x="10" y="130" width="20" height="20" class="stall" />
                            <rect x="10" y="150" width="20" height="20" class="stall" />
                        </g>
                        <g fill="#84dbe3" stroke="#000" stroke-width="1">
                            <rect x="10" y="255" width="20" height="20" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="10" y="290" width="20" height="10" class="stall" />
                            <rect x="10" y="300" width="20" height="10" class="stall" />
                            <rect x="10" y="330" width="20" height="35" class="stall" />
                            <rect x="10" y="390" width="20" height="20" class="stall" />
                            <rect x="10" y="410" width="20" height="20" class="stall" />
                            <rect x="10" y="430" width="20" height="20" class="stall" />
                            <rect x="10" y="450" width="20" height="20" class="stall" />
                        </g>
                        <g fill="#ebb691" stroke="#000" stroke-width="1">
                            <rect x="10" y="190" width="80" height="50" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="30" y="220" width="60" height="40" class="stall" />
                            <rect x="30" y="260" width="50" height="25" class="stall" />
                            <rect x="30" y="285" width="50" height="25" class="stall" />
                            <rect x="10" y="310" width="80" height="23" class="stall" />
                            <rect x="30" y="333" width="60" height="27" class="stall" />
                            <rect x="10" y="360" width="80" height="30" class="stall" />
                            <rect x="30" y="390" width="60" height="29" class="stall" />
                            <rect x="30" y="418" width="60" height="29" class="stall" />
                            <rect x="30" y="447" width="60" height="29" class="stall" />
                        </g>
                        <g fill="#f57040" stroke="#000" stroke-width="1">
                            <rect x="150" y="175" width="50" height="50" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="200" y="175" width="50" height="50" class="stall" />
                            <rect x="250" y="175" width="40" height="50" class="stall" />
                            <rect x="290" y="175" width="40" height="50" class="stall" />
                            <rect x="330" y="175" width="35" height="50" class="stall" />
                            <rect x="365" y="175" width="35" height="50" class="stall" />
                            <rect x="400" y="175" width="35" height="50" class="stall" />
                            <rect x="435" y="175" width="35" height="50" class="stall" />
                            <rect x="470" y="175" width="40" height="50" class="stall" />
                            <rect x="510" y="175" width="35" height="50" class="stall" />
                            <rect x="545" y="175" width="35" height="50" class="stall" />
                            <rect x="580" y="175" width="47" height="50" class="stall" />
                        </g>
                        <g fill="#f75e72" stroke="#000" stroke-width="1">
                            <rect x="125" y="270" width="30" height="30" class="stall" />
                            <rect x="125" y="300" width="30" height="30" class="stall" />
                            <rect x="125" y="330" width="30" height="30" class="stall" />
                            <rect x="125" y="360" width="30" height="30" class="stall" />
                            <rect x="125" y="390" width="30" height="30" class="stall" />
                            <rect x="125" y="420" width="30" height="30" class="stall" />
                        </g>
                        <g fill="#f75e72" stroke="#000" stroke-width="1">
                            <rect x="155" y="270" width="30" height="30" class="stall" />
                            <rect x="155" y="300" width="30" height="30" class="stall" />
                            <rect x="155" y="330" width="30" height="30" class="stall" />
                            <rect x="155" y="360" width="30" height="30" class="stall" />
                            <rect x="155" y="390" width="30" height="30" class="stall" />
                            <rect x="155" y="420" width="30" height="30" class="stall" />
                        </g>
                        <g fill="#dcf5f0" stroke="#000" stroke-width="1">
                            <rect x="215" y="245" width="30" height="40" class="stall" />
                            <rect x="215" y="285" width="30" height="25" class="stall" />
                            <rect x="215" y="310" width="30" height="30" class="stall" />
                            <rect x="215" y="340" width="30" height="30" class="stall" />
                            <rect x="215" y="370" width="30" height="30" class="stall" />
                            <rect x="215" y="400" width="30" height="30" class="stall" />
                            <rect x="215" y="430" width="30" height="30" class="stall" />
                        </g>
                        <g fill="#d7dedd" stroke="#000" stroke-width="1">
                            <rect x="260" y="245" width="40" height="20" class="stall" />
                            <rect x="260" y="265" width="40" height="20" class="stall" />
                            <rect x="260" y="285" width="40" height="20" class="stall" />
                            <rect x="260" y="305" width="40" height="20" class="stall" />
                            <rect x="260" y="325" width="40" height="20" class="stall" />
                            <rect x="260" y="345" width="40" height="20" class="stall" />
                            <rect x="260" y="365" width="40" height="20" class="stall" />
                            <rect x="260" y="385" width="40" height="20" class="stall" />
                            <rect x="260" y="405" width="40" height="20" class="stall" />
                            <rect x="260" y="425" width="40" height="20" class="stall" />
                            <rect x="260" y="445" width="40" height="20" class="stall" />
                            <rect x="260" y="465" width="40" height="20" class="stall" />
                        </g>
                        <g fill="#f5faf9" stroke="#000" stroke-width="1">
                            <rect x="340" y="245" width="35" height="45" class="stall" />
                            <rect x="340" y="290" width="35" height="45" class="stall" />
                            <rect x="340" y="335" width="35" height="45" class="stall" />
                            <rect x="340" y="380" width="35" height="45" class="stall" />
                            <rect x="340" y="425" width="35" height="45" class="stall" />
                        </g>
                        <g fill="#a1b3b0" stroke="#000" stroke-width="1">
                            <rect x="375" y="245" width="35" height="45" class="stall" />
                            <rect x="375" y="290" width="35" height="45" class="stall" />
                            <rect x="375" y="335" width="35" height="45" class="stall" />
                            <rect x="375" y="380" width="35" height="45" class="stall" />
                            <rect x="375" y="425" width="35" height="45" class="stall" />
                        </g>
                        <g fill="#bf1d1d" stroke="#000" stroke-width="1">
                            <rect x="450" y="245" width="20" height="20" class="stall" />
                            <rect x="450" y="265" width="20" height="20" class="stall" />
                            <rect x="450" y="285" width="20" height="20" class="stall" />
                            <rect x="450" y="305" width="20" height="20" class="stall" />
                            <rect x="450" y="325" width="20" height="20" class="stall" />
                            <rect x="450" y="345" width="20" height="20" class="stall" />
                            <rect x="450" y="365" width="20" height="20" class="stall" />
                            <rect x="450" y="385" width="20" height="20" class="stall" />
                            <rect x="450" y="405" width="20" height="20" class="stall" />
                            <rect x="450" y="425" width="20" height="20" class="stall" />
                            <rect x="450" y="445" width="20" height="20" class="stall" />
                            <rect x="450" y="465" width="20" height="20" class="stall" />
                        </g>
                        <g fill="#58e862" stroke="#000" stroke-width="1">
                            <rect x="490" y="245" width="35" height="45" class="stall" />
                            <rect x="490" y="290" width="35" height="35" class="stall" />
                            <rect x="490" y="325" width="35" height="50" class="stall" />
                            <rect x="490" y="375" width="35" height="55" class="stall" />
                            <rect x="490" y="430" width="35" height="55" class="stall" />
                        </g>
                        <g fill="#dcf5f0" stroke="#000" stroke-width="1">
                            <rect x="550" y="245" width="40" height="40" class="stall" />
                            <rect x="550" y="285" width="32" height="55" class="stall" />
                            <rect x="550" y="310" width="32" height="65" class="stall" />
                            <rect x="550" y="340" width="32" height="35" class="stall" />
                            <rect x="550" y="375" width="32" height="20" class="stall" />
                            <rect x="550" y="395" width="32" height="40" class="stall" />
                            <rect x="550" y="435" width="32" height="50" class="stall" />
                        </g>
                        <g fill="#d4c555" stroke="#000" stroke-width="1">
                            <rect x="590" y="245" width="40" height="40" class="stall" />
                            <rect x="605" y="295" width="25" height="35" class="stall" />
                            <rect x="605" y="330" width="25" height="35" class="stall" />
                            <rect x="605" y="365" width="25" height="37" class="stall" />
                            <rect x="605" y="400" width="25" height="40" class="stall" />
                            <rect x="605" y="440" width="25" height="45" class="stall" />
                        </g>
                        <g fill="#88b7fc" stroke="#000" stroke-width="1">
                            <rect x="650" y="175" width="60" height="60" class="stall" />
                            <rect x="650" y="235" width="60" height="50" class="stall" />
                        </g>
                        <g fill="#d4c555" stroke="#000" stroke-width="1">
                            <rect x="655" y="295" width="20" height="20" class="stall" data-stall-id="1 Aling Nenas's Stall" />
                            <rect x="655" y="315" width="20" height="20" class="stall" />
                            <rect x="655" y="335" width="20" height="20" class="stall" />
                            <rect x="655" y="355" width="20" height="20" class="stall" />
                            <rect x="655" y="375" width="20" height="20" class="stall" />
                            <rect x="655" y="395" width="20" height="20" class="stall" />
                            <rect x="655" y="415" width="20" height="20" class="stall" />
                            <rect x="655" y="435" width="20" height="20" class="stall" />
                            <rect x="655" y="455" width="20" height="20" class="stall" />
                            <rect x="655" y="475" width="20" height="20" class="stall" />
                            <rect x="655" y="495" width="20" height="20" class="stall" />
                        </g>
                        <g fill="#7f6bb5" stroke="#000" stroke-width="1">
                            <rect x="700" y="285" width="40" height="40" class="stall" />
                            <rect x="700" y="325" width="40" height="55" class="stall" />
                            <rect x="700" y="380" width="40" height="25" class="stall" />
                            <rect x="700" y="405" width="40" height="25" class="stall" />
                            <rect x="700" y="430" width="40" height="35" class="stall" />
                        </g>
                        <g fill="#c1b3e8" stroke="#000" stroke-width="1">
                            <rect x="125" y="580" width="40" height="40" class="stall" />
                            <rect x="125" y="540" width="40" height="40" class="stall" />
                            <rect x="165" y="580" width="40" height="40" class="stall" />
                            <rect x="165" y="540" width="40" height="40" class="stall" />
                            <rect x="205" y="580" width="40" height="40" class="stall" />
                            <rect x="205" y="540" width="40" height="40" class="stall" fill="#2e0636" />
                        </g>
                        <g fill="#c1b3e8" stroke="#000" stroke-width="1">
                            <rect x="265" y="580" width="40" height="40" class="stall" />
                            <rect x="265" y="540" width="40" height="40" class="stall" />
                            <rect x="305" y="540" width="40" height="40" class="stall" />
                        </g>
                        <g fill="#c1b3e8" stroke="#000" stroke-width="1">
                            <rect x="355" y="540" width="30" height="40" class="stall" />
                            <rect x="385" y="540" width="30" height="60" class="stall" />
                            <rect x="415" y="540" width="30" height="40" class="stall" />
                            <rect x="445" y="540" width="30" height="40" class="stall" />
                        </g>
                        <g fill="#c1b3e8" stroke="#000" stroke-width="1">
                            <rect x="490" y="540" width="50" height="40" class="stall" />
                            <rect x="540" y="540" width="50" height="40" class="stall" />
                            <rect x="590" y="540" width="40" height="40" class="stall" />
                            <rect x="490" y="580" width="50" height="40" class="stall" />
                            <rect x="540" y="580" width="50" height="40" class="stall" />
                            <rect x="490" y="620" width="50" height="30" class="stall" />
                            <rect x="540" y="620" width="50" height="30" class="stall" />
                            <rect x="590" y="580" width="40" height="70" class="stall" fill="transparent" />
                            <rect x="490" y="650" width="100" height="40" class="stall" fill="transparent" />
                            <rect x="590" y="650" width="40" height="60" class="stall" fill="transparent" />
                            <rect x="490" y="690" width="50" height="60" class="stall" />
                            <rect x="490" y="690" width="30" height="30" class="stall" />
                            <rect x="540" y="690" width="60" height="60" class="stall" />
                            <rect x="590" y="710" width="40" height="30" class="stall" fill="transparent" />
                        </g>
                        <g fill="#c1b3e8" stroke="#000" stroke-width="1">
                            <rect x="320" y="620" width="40" height="50" class="stall" />
                            <rect x="360" y="620" width="50" height="50" class="stall" />
                            <rect x="410" y="605" width="20" height="40" class="stall" />
                            <rect x="410" y="645" width="20" height="40" class="stall" />
                            <rect x="320" y="610" width="90" height="10" class="stall" fill="transparent" />
                        </g>
                        <g fill="#c9d4f5" stroke="#000" stroke-width="1">
                            <rect x="700" y="540" width="25" height="40" class="stall" fill="#ba6765" />
                            <rect x="700" y="580" width="25" height="40" class="stall" fill="#ba6765" />
                            <rect x="700" y="620" width="25" height="40" class="stall" />
                            <rect x="700" y="660" width="25" height="40" class="stall" />
                            <rect x="625" y="720" width="100" height="40" class="stall" />
                            <rect x="700" y="700" width="25" height="40" class="stall" />
                        </g>
                    </svg>

                    <!-- Modal Structure -->
                    <div class="modal fade" id="stallModal" tabindex="-1" aria-labelledby="stallModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="stallModalLabel">Stall 102</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Vendor:</th>
                                                <td>Nelson Reyes</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">Market Section:</th>
                                                <td>Vegetables</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Stall No.:</th>
                                                <td>102</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Stall Size:</th>
                                                <td>108 sq/m</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Stall Rent:</th>
                                                <td>₱400.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
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

        }

        // Add event listener to the market select element
        document.getElementById('market').addEventListener('change', function() {
            loadMarketInfo(this);
        });



        // // Initialize the map and set its view
        // const map = L.map('map').setView([14.676, 121.043], 15); // Set initial center and zoom level
        // // Load and display tile layers (from OpenStreetMap)
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19,
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(map);
        // // Add a marker to the map
        // L.marker([14.676, 121.043]).addTo(map)

        function showStallMap() {
            document.getElementById("map_section").style.display = "block";
        }


        function loadMarketInfo(marketSelect) {
            document.getElementById('viewStallsBtn').removeAttribute('disabled');

            const selectedOption = marketSelect.options[marketSelect.selectedIndex];
            const selectedId = selectedOption.value;

            // Fetch location data if it exists
            const selectedLocation = locationsData?.find(location => location.id == selectedId);
            document.getElementById('market_address').innerText = selectedLocation ? selectedLocation.market_address : '';

            // Send selectedId to the server using fetch
            fetch('../actions/map_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: selectedId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Update the stall count if available, or fallback
                    console.log(data);
                    document.getElementById('stall_count').textContent = data?.s_count ?? '';
                    document.getElementById('stall_vacant').textContent = data?.s_vacant ?? '';
                    document.getElementById('stall_occupied').textContent = data?.s_occupied ?? '';

                    document.getElementById('responseContainer').innerText = data.message || '';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script>
        // Select all stalls and add click event listeners
        document.querySelectorAll('.stall').forEach(stall => {
            stall.addEventListener('click', function() {
                const stallId = this.getAttribute('data-stall-id');

                // Set modal content
                // document.getElementById('modal-stall-title').textContent = `Stall: ${stallId}`;
                // document.getElementById('modal-stall-content').textContent = `Details about ${stallId}. (You can add more specific information here.)`;

                // Show the modal
                const stallModal = new bootstrap.Modal(document.getElementById('stallModal'));
                stallModal.show();
            });
        });
    </script>
</body>

</html>