    <?php
    session_start();

    if ($_SESSION['user_type'] !== 'Admin') {
        header("Location: /lgu_market_sys/errors/err403.php");
        exit;
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Violations - Public Market Monitoring System</title>
        <link rel="icon" type="image/png" href="../favicon_192.png">
        <link rel="stylesheet" href="../../../assets/css/admin.css">
        <?php include '../../../includes/cdn-resources.php'; ?>
    </head>

    <body class="body light">


        <?php include '../../../includes/nav.php'; ?>

        <!-- Toast -->
        <!-- <div class="toast-container mt-5 p-3 top-0 end-0">
             <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show" data-bs-autohide="false">
                <div class="toast-header text-bg-warning rounded-top">
             <svg class="mx-2" width="25" height="22" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <rect x="0" y="0" width="100" height="100" rx="20" fill="url(#grad1)" />
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style="stop-color:#ff4c4c;stop-opacity:1" />
              <stop offset="100%" style="stop-color:#b30000;stop-opacity:1" />
            </linearGradient>
          </defs>
          <polygon points="50,20 75,75 25,75" fill="white" />
          <rect x="47" y="40" width="6" height="20" fill="#ff4c4c" />
          <circle cx="50" cy="70" r="3" fill="#ff4c4c" />
         </svg>
            <strong class="me-auto">System Alerts</strong>
            <small>11 mins ago</small>
                </div>
                    <div class="toast-body text-light rounded-bottom p-4">
                     New system update available <br>
                Market maintenance scheduled
             </div>
            </div>
        </div> -->

        <div class="text-start m-3 p-3 title d-flex align-items-center">
            <div class="icon-box me-3 shadow title-icon">
                <i class="bi bi-bar-chart-line-fill"></i>
            </div>
            <div>
                <h4 class="m-0">Admin - Violations</h4>
                <p class="text-muted mb-0">Manage and track vendor violations to ensure compliance with market regulations.</p>
            </div>
            <div class="ms-auto me-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="http://localhost/lgu_market_sys/pages/admin/home/">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Violations</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container-fluid w-75 mt-5">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <div class="container">
                    <h4 class="fw-bold">Violation Management</h4>
                    <p class="text-muted">Tracks and manages vendor violations, allowing reporting, searching, editing, and deletion for market regulation compliance.</p>
                </div>
                <div class="container text-end">
                    <!-- Button to Open the Modal -->
                    <button class="btn btn-danger report-button" data-bs-toggle="modal" data-bs-target="#addViolationModal">
                        Create Report
                    </button>
                </div>
            </div>

            <!-- Violations Table -->
            <div class="table-responsive tables mb-5 w-100">
                <div class="text-center mb-4 mt-5">
                    <h4>Violations Table</h4>
                </div>

                <?php
                require_once '../../../includes/config.php';

                try {
                    // Fetch violations with joined tables for user, stall, and violation type
                    $stmt = $pdo->query("
        SELECT 
            v.id, 
            CONCAT(u.first_name, ' ', COALESCE(u.middle_name, ''), ' ', u.last_name) AS vendor_name,
            u.account_id,
            s.stall_number, 
            vt.violation_name, 
            vt.fine_amount,
            vt.criticality,
            v.violation_description, 
            v.evidence_image_path, 
            v.violation_date, 
            v.status, 
            v.created_at,
            v.appeal_text,
            v.appeal_document_path
        FROM violations v
        JOIN users u ON v.user_id = u.id
        JOIN stalls s ON v.stall_id = s.id
        JOIN violation_types vt ON v.violation_type_id = vt.id
        ORDER BY v.created_at DESC
    ");
                    $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Database Error: " . $e->getMessage());
                }
                ?>
                <!-- Filter Buttons -->

                <div class="d-flex flex-wrap justify-content-center gap-5 mb-4 filter-container">
                    <button class="btn filter-button" data-value="">All</button>
                    <button class="btn filter-button" data-value="Resolved">Resolved</button>
                    <button class="btn filter-button" data-value="Pending">Pending</button>
                    <button class="btn filter-button" data-value="Deleted">Deleted</button>
                    <button class="btn filter-button" data-value="Critical">Critical</button>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Stall Number</th>
                            <th>Violation Type</th>
                            <th>Violation Description</th>
                            <th>Evidence Image</th>
                            <th>Violation Date</th>
                            <th>Fine Amount</th>
                            <th>Status</th>
                            <th>ViolationAppeal</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="violationsTable">
                        <?php foreach ($violations as $violation): ?>


                            <tr id="row-<?= $violation['id'] ?>" data-status="<?= $violation['status'] ?>" data-criticality="<?= $violation['criticality'] ?>">
                                <td><?= htmlspecialchars($violation['id']) ?></td>
                                <td><?= htmlspecialchars($violation['vendor_name']) ?></td>
                                <td><?= htmlspecialchars($violation['stall_number']) ?></td>
                                <td><?= htmlspecialchars($violation['violation_name']) ?></td>
                                <td><?= htmlspecialchars($violation['violation_description']) ?></td>
                                <td>
                                    <?php if (!empty($violation['evidence_image_path'])): ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $violation['id'] ?>">
                                            <i class="bi bi-file-image"></i>
                                            View
                                        </button>
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($violation['violation_date']) ?></td>
                                <td><?= htmlspecialchars($violation['fine_amount']) ?></td>
                                <td id="status-<?= $violation['id'] ?>"><?= htmlspecialchars($violation['status']) ?></td>
                                <td>
                                    <?php if (!empty($violation['appeal_text'])): ?>
                                        <button class="btn btn-warning btn-sm w-100" data-bs-toggle="modal" data-bs-target="#appealModal<?= $violation['id'] ?>">
                                            <i class="bi bi-file-earmark-text-fill"></i> View
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($violation['created_at']) ?></td>
                                <td>
                                    <?php if ($violation['status'] !== 'Resolved' && $violation['status'] !== 'Deleted'): ?>
                                        <button class="btn btn-success btn-sm mb-1 w-100" onclick="resolveViolation(<?= $violation['id'] ?>, <?= $violation['account_id'] ?>)">
                                            <i class="bi bi-check-circle-fill"></i> Resolve
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($violation['status'] !== 'Deleted'): ?>
                                        <button class="btn btn-danger btn-sm w-100" onclick="deleteViolation(<?= $violation['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal for Image -->
                            <div class="modal fade" id="modal<?= $violation['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $violation['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel<?= $violation['id'] ?>">Evidence Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="../../../<?= htmlspecialchars($violation['evidence_image_path']) ?>" alt="Evidence" class="img-fluid">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Appeal -->
                            <div class="modal fade" id="appealModal<?= $violation['id'] ?>" tabindex="-1" aria-labelledby="appealModalLabel<?= $violation['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="modal-container">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <h3 class="modal-title" id="modalLabel<?= $violation['id'] ?>">Appeal for Violation</h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <hr>
                                                <h5>Appeal Message: </h5>
                                                <p><?= $violation['appeal_text'] ?></p>
                                                <h5>Appeal Document: </h5>
                                                <?php
                                                $filePath = $violation['appeal_document_path'];
                                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                                // Determine whether to display or provide a link
                                                if (in_array(strtolower($fileExtension), $imageExtensions)): ?>
                                                    <img src="../../../<?= $filePath ?>" alt="Appeal Image" class="img-fluid">
                                                <?php else: ?>
                                                    <a href="../<?= $filePath ?>" target="_blank" class="btn btn-info">
                                                        <i class="bi bi-file-earmark-text"></i> View Document
                                                    </a>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Violation Modal -->
        <div class="modal fade" id="addViolationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addViolationModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-container">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="modal-title fw-bold" id="staticBackdropLabel">Create Report</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <p class="text-muted me-5">Report violations to ensure compliance and maintain order. Provide accurate details to help authorities investigate and take appropriate action.</p>
                            <hr class="mb-4">

                            <form id="violationForm" enctype="multipart/form-data">
                                <h4>Vendor</h4>

                                <div class="d-flex justify-content-between ms-3">
                                    <div class="mb-3">
                                        <label class="form-label">First Name:</label>
                                        <input type="text" class="form-control" name="vendor_first_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Middle Name:</label>
                                        <input type="text" class="form-control" name="vendor_middle_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Name:</label>
                                        <input type="text" class="form-control" name="vendor_last_name" required>
                                    </div>
                                </div>
                                <div class="mb-3 ms-3">
                                    <label class="form-label">Vendor Id:</label>
                                    <input type="text" class="form-control" name="user_id" required>
                                </div>


                                <h4>Stall</h4>
                                <div class="mb-4 ms-3 form-group">
                                    <label for="market">Market: <small class="error-message"></small></label>
                                    <select class="form-select" id="market" name="market" onchange="getStallData()" required>
                                        <option value="" disabled selected>-- Select Market --</option>
                                    </select>
                                    <span id="market_address"></span>
                                </div>

                                <!-- Section and Stall (side by side) -->
                                <div class="row mb-5 ms-3">
                                    <div class="col form-group">
                                        <label for="section">Section: <small class="error-message"></small></label>
                                        <select class="form-select" id="section" name="section" onchange="getStallData()">
                                            <option value="" disabled selected>-- Select Section --</option>
                                        </select>
                                    </div>
                                    <div class="col form-group">
                                        <label for="stall">Stall Number: <small class="error-message"></small></label>
                                        <select class="form-select" id="stall" name="stall" required>
                                            <option value="" disabled selected>-- Select Stall Number --</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="message"></div>

                                <h4 class="text-danger">Violation</h4>
                                <div class="mb-3 ms-3 d-flex flex-column">
                                    <label class="mb-2" for="violation_date">Violation Date:</label>
                                    <input type="date" id="violation_date" name="violation_date" required>
                                </div>


                                <div class="mb-3 ms-3">
                                    <label class="form-label">Violation Type:</label>
                                    <?php
                                    require_once '../../../includes/config.php';

                                    try {
                                        // Fetch violation types from the database
                                        $stmt = $pdo->query("SELECT id, violation_name FROM violation_types ORDER BY violation_name ASC");
                                        $violations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (PDOException $e) {
                                        die("Database Error: " . $e->getMessage());
                                    }
                                    ?>

                                    <select class="form-select" name="violation_type_id" required>
                                        <option value="" selected disabled>Select a violation</option>
                                        <?php foreach ($violations as $violation): ?>
                                            <option value="<?= htmlspecialchars($violation['id']) ?>">
                                                <?= htmlspecialchars($violation['violation_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3 ms-3">
                                    <label class="form-label">Violation Description:</label>
                                    <textarea class="form-control" name="violation_description" rows="3" required></textarea>
                                </div>

                                <div class="mb-3 ms-3">
                                    <label class="form-label">Upload Evidence Image:</label>
                                    <input type="file" class="form-control" name="evidence_image" accept="image/*" required>
                                </div>

                                <div class="mt-3 text-center">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../../includes/footer.php'; ?>
        <?php include '../../../includes/theme.php'; ?>

        <script>
            document.getElementById("violationForm").addEventListener("submit", function(event) {
                event.preventDefault();
                if (!validateViolationForm()) {
                    event.preventDefault();
                    alert("Please fill in all required fields.");
                    return;
                }
                let formData = new FormData(this);

                console.log("Form data entries:");
                for (let [key, value] of formData.entries()) {
                    console.log(key, ": ", value);
                }

                fetch("../../actions/submit_violation_report.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            document.getElementById("violationForm").reset();
                            location.reload(); // Reload page to reflect changes
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });

            document.addEventListener("DOMContentLoaded", function() {
                const filterButtons = document.querySelectorAll(".filter-button");
                const violationsTable = document.getElementById("violationsTable");

                // Attach event listeners to buttons
                filterButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        let filterValue = this.getAttribute("data-value").toLowerCase();
                        filterViolations(filterValue);
                        setActiveButton(this);
                    });
                });

                // Set "All" as the default filter on page load
                filterViolations("");
                document.querySelector('.filter-button[data-value=""]').classList.add("active");

                // Function to filter rows
                function filterViolations(filterValue) {
                    let rows = violationsTable.querySelectorAll("tr");
                    console.log("Filter Value: ", filterValue);
                    rows.forEach(row => {
                        let status = row.getAttribute("data-status")?.toLowerCase();
                        let criticality = row.getAttribute("data-criticality")?.toLowerCase();

                        console.log(status)
                        if (!status) return;

                        if (filterValue === "" || filterValue === "all" || status === filterValue || criticality === filterValue) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                }

                function setActiveButton(clickedButton) {
                    filterButtons.forEach(button => {
                        button.classList.remove("active");
                    });
                    clickedButton.classList.add("active");
                }
            });


            function resolveViolation(violationId, account_id) {
                if (!confirm("Are you sure you want to resolve this violation?")) return;

                fetch('../../actions/resolve_violation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: violationId,
                            account_id: account_id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`status-${violationId}`).innerText = "Resolved";
                            location.reload();
                            alert("Violation resolved successfully.");
                        } else {
                            alert("Failed to resolve violation: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

            function deleteViolation(violationId) {
                if (!confirm("Are you sure you want to delete this violation? This action cannot be undone.")) return;

                fetch('../../actions/delete_violation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: violationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`row-${violationId}`).remove();
                            location.reload();
                            alert("Violation deleted successfully.");
                        } else {
                            alert("Failed to delete violation: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }


            function validateViolationForm() {
                let isValid = true;
                let requiredFields = document.querySelectorAll("#violationForm [required]");

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add("is-invalid"); // Highlight invalid fields
                    } else {
                        field.classList.remove("is-invalid");
                    }
                });

                // Validate document upload
                let fileInput = document.getElementById("violationDocument");
                if (fileInput) {
                    let file = fileInput.files[0];

                    if (!file) {
                        isValid = false;
                        fileInput.classList.add("is-invalid");
                        alert("Please upload a document.");
                    } else {
                        fileInput.classList.remove("is-invalid");

                        // Allowed file types
                        let allowedTypes = ["application/pdf", "image/jpeg", "image/png"];
                        if (!allowedTypes.includes(file.type)) {
                            isValid = false;
                            alert("Invalid file type. Only PDF, JPG, and PNG are allowed.");
                        }

                        // Max file size (5MB)
                        let maxSize = 5 * 1024 * 1024; // 5MB in bytes
                        if (file.size > maxSize) {
                            isValid = false;
                            alert("File size exceeds 5MB.");
                        }
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
                fetch('../../actions/get_stalls.php?market_id=' + marketId + '&section_id=' + sectionId)
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
                fetch('../../actions/get_market.php')
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
                        console.log('Failed to load market locations. Please try again later.');
                    });
            }

            function getSections() {
                fetch('../../actions/get_section.php')
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
                        console.log('Failed to load market locations. Please try again later.');
                    });
            }
        </script>
    </body>

    </html>