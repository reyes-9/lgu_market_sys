<?php
require_once '../../../includes/session.php';

if ($_SESSION['user_type'] !== 'Admin' && $_SESSION['user_type'] !== 'Inspector') {
    echo '<script>
    alert("Please log in to continue.");
    window.location.href = "/lgu_market_sys/pages/login/index.php";
   </script>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspections - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../logo.png">
    <link rel="stylesheet" href="../../../assets/css/admin.css">
    <?php include '../../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">


    <?php include '../../../includes/nav.php'; ?>

    <div class="text-start m-3 p-3 title d-flex align-items-center">
        <div class="icon-box me-3 shadow title-icon">
            <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div>
            <h4 class="m-0">Inspector - Inspections</h4>
            <p class="text-muted mb-0">Manage assigned inspections, update statuses, and mark them as completed or canceled.</p>
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
                <h4 class="fw-bold">Inepection Management</h4>
                <p class="text-muted">Tracks and manage assigned inspections, update statuses, and mark them as completed or canceled.</p>
            </div>
        </div>

        <!-- Violations Table -->
        <div class="table-responsive tables mb-5 w-100">
            <div class="text-center mb-4 mt-5">
                <h4>Inspection Table</h4>
            </div>

            <?php
            require_once '../../../includes/config.php';

            try {

                $stmt = $pdo->query("
                SELECT
                    a.id, 
                    a.application_number,
                    CONCAT(u.first_name, ' ', COALESCE(u.middle_name, ''), ' ', u.last_name) AS vendor_name,
                    u.account_id,
                    s.stall_number,
                    m.market_name, 
                    sc.section_name,
                    a.inspection_status,
                    a.inspection_date,
                    a.created_at,
                    a.market_id,
                    a.section_id
                FROM applications a
                JOIN users u ON a.account_id = u.account_id
                JOIN stalls s ON a.stall_id = s.id
                JOIN market_locations m ON a.market_id = m.id 
                JOIN sections sc ON a.section_id = sc.id 
                ORDER BY a.created_at DESC;

                ");
                $inspections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database Error: " . $e->getMessage());
            }
            ?>
            <!-- Filter Buttons -->

            <div class="d-flex flex-wrap justify-content-center gap-5 mb-4 filter-container">
                <button class="btn filter-button" data-value="">All</button>
                <button class="btn filter-button" data-value="Pending">Pending</button>
                <button class="btn filter-button" data-value="Scheduled">Scheduled</button>
                <button class="btn filter-button" data-value="Approved">Approved</button>
                <button class="btn filter-button" data-value="Rejected">Rejected</button>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Market Name</th>
                        <th>Section Name</th>
                        <th>Stall Number</th>
                        <th>Inspection Status</th>
                        <th>Inspection Date</th>
                        <th>Created At</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody id="inspectionTable">
                    <?php foreach ($inspections as $inspection): ?>

                        <tr id="row-<?= $inspection['id'] ?>"
                            data-status="<?= $inspection['inspection_status'] ?>"
                            data-id="<?= htmlspecialchars($inspection['id']) ?>"
                            data-vendor-name="<?= htmlspecialchars($inspection['vendor_name']) ?>"
                            data-market-name="<?= htmlspecialchars($inspection['market_name']) ?>"
                            data-section-name="<?= htmlspecialchars($inspection['section_name']) ?>"
                            data-stall-number="<?= htmlspecialchars($inspection['stall_number']) ?>"
                            data-inspection-status="<?= htmlspecialchars($inspection['inspection_status']) ?>"
                            data-inspection-date="<?= htmlspecialchars($inspection['inspection_date']) ?>"
                            data-created-at="<?= htmlspecialchars($inspection['created_at']) ?>">

                            <td><?= htmlspecialchars($inspection['id']) ?></td>
                            <td><?= htmlspecialchars($inspection['vendor_name']) ?></td>
                            <td><?= htmlspecialchars($inspection['market_name']) ?></td>
                            <td><?= htmlspecialchars($inspection['section_name']) ?></td>
                            <td><?= htmlspecialchars($inspection['stall_number']) ?></td>
                            <td><?= htmlspecialchars($inspection['inspection_status']) ?></td>
                            <td><?= htmlspecialchars($inspection['inspection_date']) ?></td>
                            <td><?= htmlspecialchars($inspection['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="inspectionModal" tabindex="-1" aria-labelledby="inspectionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-container">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="modal-title fw-bold" id="staticBackdropLabel">Manage Inspection</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <table class="table table-borderless mt-5">
                                <tbody>
                                    <tr>
                                        <th>ID : </th>
                                        <td id="modal-id"></td>
                                    </tr>
                                    <tr>
                                        <th>Vendor Name : </th>
                                        <td id="modal-vendor-name"></td>
                                    </tr>
                                    <tr>
                                        <th>Stall Number :</th>
                                        <td id="modal-stall-number"></td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Status :</th>
                                        <td id="modal-inspection-status"></td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Date :</th>
                                        <td id="modal-inspection-date"></td>
                                    </tr>
                                    <tr>
                                        <th>Created At :</th>
                                        <td id="modal-created-at"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-5 text-start">
                                <button type="button" class="btn btn-success mx-2" id="approve-btn">Approve</button>
                                <button type="button" class="btn btn-danger mx-2" id="reject-btn">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../../includes/footer.php'; ?>
    <?php include '../../../includes/theme.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".filter-button");
            const inspectionTable = document.getElementById("inspectionTable");
            const defaultFilter = "pending"; // Default filter is "pending"
            const modal = new bootstrap.Modal(document.getElementById("inspectionModal"));

            const approveBtn = document.getElementById("approve-btn");
            const rejectBtn = document.getElementById("reject-btn");

            approveBtn.addEventListener("click", () => updateInspectionStatus("Approved"));
            rejectBtn.addEventListener("click", () => updateInspectionStatus("Rejected"));

            // Attach event listener to the table rows
            inspectionTable.addEventListener("click", function(event) {
                let row = event.target.closest("tr");
                if (row) showModal(row);
            });

            // Attach event listeners to filter buttons
            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    let filterValue = this.getAttribute("data-value").toLowerCase();
                    filterInspection(filterValue);
                    setActiveFilterButton(this);
                    updateButtonState();
                });
            });

            // Initialize buttons and table on page load
            setActiveFilterButton(document.querySelector(`.filter-button[data-value="${defaultFilter}"]`));
            filterInspection(defaultFilter); // Start by filtering "pending"
            updateButtonState();

            function updateInspectionStatus(status) {
                const application_id = document.getElementById("modal-id").textContent;

                if (!application_id || !status) {
                    alert("No inspection selected!");
                    return;
                }

                if (!confirm(`Are you sure you want this inspection ${status}?`)) return;

                fetch("../../actions/update_inspection_status.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${application_id}&status=${status}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Inspection ${status}d successfully!`);
                            location.reload(); // Refresh to update status
                        } else {
                            alert("Failed to update inspection status.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

            function setActiveFilterButton(clickedButton) {
                filterButtons.forEach(button => button.classList.remove("active"));
                clickedButton.classList.add("active");
            }

            function getActiveFilter() {
                const activeButton = document.querySelector(".filter-button.active");
                return activeButton ? activeButton.getAttribute("data-value").toLowerCase() : defaultFilter;
            }

            function updateButtonState() {
                let filterValue = getActiveFilter();
                let isScheduled = filterValue === "scheduled";
                approveBtn.disabled = !isScheduled;
                rejectBtn.disabled = !isScheduled;
            }

            function filterInspection(filterValue) {
                let rows = inspectionTable.querySelectorAll("tr");
                rows.forEach(row => {
                    let status = row.getAttribute("data-status")?.toLowerCase();
                    if (filterValue === "all" || filterValue === "" || filterValue === "all") {
                        row.style.display = "";
                    } else {
                        row.style.display = status === filterValue ? "" : "none";
                    }
                });
            }

            function showModal(row) {
                if (!row) return;

                document.getElementById("modal-id").textContent = row.dataset.id || "N/A";
                document.getElementById("modal-vendor-name").textContent = row.dataset.vendorName || "N/A";
                document.getElementById("modal-stall-number").textContent = row.dataset.stallNumber || "N/A";
                document.getElementById("modal-inspection-status").textContent = row.dataset.inspectionStatus || "N/A";
                document.getElementById("modal-inspection-date").textContent = row.dataset.inspectionDate || "N/A";
                document.getElementById("modal-created-at").textContent = row.dataset.createdAt || "N/A";

                modal.show();
            }
        });
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

                });
        }
    </script>
</body>

</html>