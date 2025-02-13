<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violations - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/violation.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

    <?php include '../../includes/nav.php'; ?>

    <div class="container mt-5 vh-100">
        <div class="container content">
            <div class="container mt-3 p-0">
                <a href="../portal/" class="btn btn-outline btn-return mb-3">
                    <i class="bi bi-arrow-left"></i> Profile
                </a>
            </div>
            <h2 class="mb-5">Your Violations</h2>
            <div class="mb-3">
                <div class="d-flex align-items-center">
                    <p class="mb-0 me-2">Filter: </p> <!-- 'me-2' adds margin to the right -->
                    <div class="btn-group">
                        <button id="filterButton" type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            All
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item filter-option disabled" href="#" data-value="">All</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-value="Resolved">Resolved</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-value="Pending">Pending</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-value="Rejected">Rejected</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-value="Critical">Critical</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table class="table table-borderless table-hover custom-table">
                    <thead>
                        <tr>
                            <th>Violation</th>
                            <th>Date Issued <br> (YYYY-MM-DD)</th>
                            <th>Fine Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="violationsTableBody">

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="violationDetailsModal" tabindex="-1" aria-labelledby="violationDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="violationDetailsLabel">Violation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Violation:</strong> <span id="modalViolation"></span></p>
                    <p><strong>Date Issued:</strong> <span id="modalDate"></span></p>
                    <p><strong>Fine Amount:</strong> <span id="modalFine"></span></p>
                    <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus" class="badge"></span> <span id="modalCriticality" class="badge"></span></p>
                </div>

            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            fetchViolations();
            addFilterToTable();

            document.getElementById("violationsTableBody").addEventListener("click", function(event) {
                if (event.target.classList.contains("view-details-btn")) {
                    updateViolationModal(event.target);
                }
            });
        });

        function updateViolationModal(button) {
            let modalViolation = document.getElementById('modalViolation');
            let modalDate = document.getElementById('modalDate');
            let modalFine = document.getElementById('modalFine');
            let modalDescription = document.getElementById('modalDescription');
            let modalStatus = document.getElementById('modalStatus');
            let modalCriticality = document.getElementById('modalCriticality');

            if (!modalViolation || !modalDate || !modalFine || !modalDescription || !modalStatus || !modalCriticality) {
                console.error("One or more modal elements not found.");
                return;
            }

            modalViolation.textContent = button.getAttribute('data-violation');
            modalDate.textContent = button.getAttribute('data-date');
            modalFine.textContent = button.getAttribute('data-fine');
            modalDescription.textContent = button.getAttribute('data-description');

            // Update status badge color
            let status = button.getAttribute('data-status');
            modalStatus.textContent = status;
            modalStatus.className = "badge " +
                (status === "Rejected" ? "bg-dark" :
                    status === "Pending" ? "bg-warning" :
                    status === "Resolved" ? "bg-success" : "");

            let criticality = button.getAttribute('data-criticality');
            modalCriticality.textContent = (criticality === "Critical" ? criticality : "");
            modalCriticality.className = "badge " + (criticality === "Critical" ? "bg-danger" : "");
        }

        function fetchViolations(filter) {
            fetch('../actions/violation_action.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById("violationsTableBody");
                    tableBody.innerHTML = "";
                    console.log(data);

                    data.data
                        .filter(row =>
                            !filter ||
                            (filter === "Critical" && row.criticality === "Critical") ||
                            (filter === row.status)
                        )
                        .forEach(row => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                        <td>${row.violation_name}</td>
                        <td>${row.violation_date.split(" ")[0]}</td>
                        <td> ₱ ${row.fine_amount}</td>
                        <td>
                            <span class="badge ${
                                row.status === 'Pending' ? 'bg-warning' :
                                row.status === 'Resolved' ? 'bg-success' : 
                                row.status === 'Rejected' ? 'bg-dark' : ''
                            }">${row.status}</span> 
                            ${row.criticality === 'Critical' ? `<span class="badge bg-danger">Critical</span>` : ''}
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info view-details-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#violationDetailsModal"
                                data-violation="${row.violation_name}"
                                data-date="${row.violation_date}"
                                data-fine=" ₱ ${row.fine_amount}"
                                data-description="${row.remarks}" 
                                data-status="${row.status}"
                                data-criticality="${row.criticality}">
                                View Details
                            </a>
                        </td>
                    `;
                            tableBody.appendChild(tr);
                        });
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function addFilterToTable() {
            const filterButton = document.getElementById("filterButton");
            const filterOptions = document.querySelectorAll(".filter-option");


            if (!filterButton || filterOptions.length === 0) {
                console.error("Filter button or options not found.");
                return;
            }

            filterOptions.forEach(option => {
                option.addEventListener("click", function(event) {
                    event.preventDefault();

                    const selectedValue = this.dataset.value;

                    console.log(selectedValue);

                    filterOptions.forEach(opt => opt.classList.remove('disabled'));
                    this.classList.add('disabled');
                    filterButton.textContent = this.textContent;

                    fetchViolations(selectedValue);
                });
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById('violationDetailsModal');
            var modalViolation = document.getElementById('modalViolation');
            var modalDate = document.getElementById('modalDate');
            var modalFine = document.getElementById('modalFine');
            var modalDescription = document.getElementById('modalDescription');
            var modalStatus = document.getElementById('modalStatus');

            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    modalViolation.textContent = this.getAttribute('data-violation');
                    modalDate.textContent = this.getAttribute('data-date');
                    modalFine.textContent = this.getAttribute('data-fine');
                    modalDescription.textContent = this.getAttribute('data-description');

                    // Update status badge color
                    var status = this.getAttribute('data-status');
                    modalStatus.textContent = status;
                    modalStatus.className = "badge " + (status === "Unresolved" ? "bg-danger" : "bg-success");
                });
            });
        });
    </script>
</body>

</html>