<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application - Public Market Monitoring System</title>
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/track_app.css">
    <?php include '../../includes/cdn-resources.php'; ?>
</head>

<body class="body light">

    <?php include '../../includes/nav.php'; ?>
    <div class="content-wrapper">
        <div class="container m-5">
            <div class="container mt-3 p-0">
                <a href="../portal/" class="btn btn-outline btn-return mb-3">
                    <i class="bi bi-arrow-left"></i> Profile
                </a>
            </div>
            <h2>Your Applications</h2>

            <div class="container my-4">
                <div class="row g-5">
                </div>

                <nav class="mt-4">
                    <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>
        </div>

        <!-- Response Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content text-center">
                    <i class="bi bi-check-circle-fill icon-animation"></i>
                    <div class="modal-body" id="responseModalBody">
                        <!-- Message content will go here -->
                    </div>
                    <div class="text-center text-secondary">
                        <p id="reloadCounter"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        // Fetch initial data when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const initialPage = 1; // Set initial page number
            fetchData(initialPage);
        });

        document.addEventListener("DOMContentLoaded", function() {
            attachWithdrawListeners(); // Attach listeners on initial load
        });

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('page-link')) {
                const urlParams = new URLSearchParams(e.target.href.split('?')[1]);
                const page = parseInt(urlParams.get('page'));

                e.preventDefault();
                fetchData(page);
                console.log(page);
            }
        });


        function capitalizeWords(str) {
            return str.replace(/\b\w/g, (char) => char.toUpperCase());
        }

        function generateCards(data) {
            const container = document.querySelector('.row.g-5');
            container.innerHTML = ''; // Clear existing cards

            data.forEach((app, index) => {
                const delay = index * 0.1;
                const card = `
            <div class="col-md-4 slide-up">
                <div class="card fade-in-card fade-in-card-delay border-0" style="--delay: ${delay}s;">
                <span class="card-status ${
                    app.status === 'Draft' ? 'status-draft' :
                    app.status === 'Submitted' ? 'status-submitted' :
                    app.status === 'Under Review' ? 'status-under-review' : 
                    app.status === 'Approved' ? 'status-approved' :
                    app.status === 'Rejected' ? 'status-rejected' :
                    app.status === 'Withdrawn' ? 'status-withdrawn' : ''
                    }">
                    ${app.status}
                </span>
                <h4 class="my-4"><strong>${app.application_type} Application</strong></h4>
                <table class="table">
                    <tr>
                        <th>Application Id</th>
                        <td>${app.id}</td>
                    </tr>

                    ${app.application_number !== null ? `
                    <tr>
                        <th>Application Number</th>
                        <td>${app.application_number}</td>
                    </tr>
                    ` : ""}

                     ${app.ext_duration !== null ? `
                    <tr>
                         <th>Extension Duration</th>
                         <td>${app.ext_duration} Month/s</td>
                    </tr>
                     ` : ""}

                     ${app.full_name !== null ? `<tr>
                        <th>Helper Name</th>
                        <td>${app.full_name}</td>
                    </tr>` : ""}
                    
                    <tr>
                        <th>Stall Number</th>
                        <td>${app.stall_number}</td>
                    </tr>
                    <tr>
                        <th>Section</th>
                        <td>${app.section_name}</td>
                 </tr>
                    <tr>
                        <th>Market</th>
                        <td>${app.market_name}</td>
                    </tr>
                    <tr>
                        <th>Submitted On</th>
                        <td>${app.created_at}</td>
                    </tr>
                </table>
                <button type="button" id="progressModal" class="btn btn-warning w-100 mt-3" data-bs-toggle="modal" data-bs-target="#viewProgressModal${app.id}">
                    View Progress
                </button>
                <button type="button" class="btn btn-outline-secondary w-100 mt-3 withdraw-btn" data-bs-toggle="modal" data-bs-target="#withdrawModal${app.id}">Withdraw Application</button>
                </div>
            </div>

            <!-- Progress Modal  -->
            <div class="modal fade" id="viewProgressModal${app.id}" tabindex="-1" aria-labelledby="viewProgressModalLabel${app.id}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="viewProgressModalLabel${app.id}">Application Progress</h5>
                        </div>
                        <div class="modal-body">
                            <div class="progress-tracker-modern my-5 d-flex flex-column flex-md-row align-items-center justify-content-between" data-status="${app.status}">
                                ${generateProgressSteps(app.status)}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Withdraw Modal  -->
            <div class="modal fade withdrawModal" id="withdrawModal${app.id}" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title" id="withdrawModalLabel">Confirm Withdrawal</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <div class="modal-body">
                           Are you sure you want to withdraw this application?
                       </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirmWithdraw" data-id="${app.id}" data-app_name="${app.application_type}">Withdraw</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
                container.innerHTML += card;
            });
        }

        function generateProgressSteps(status) {
            const steps = [{
                    id: 1,
                    label: 'Submitted',
                    icon: ''
                },
                {
                    id: 2,
                    label: 'Under Review',
                    icon: ''
                },
                {
                    id: 3,
                    label: 'Scheduled for Inspection',
                    icon: ''
                },
                {
                    id: 4,
                    label: 'Inspection Completed',
                    icon: ''
                },
                {
                    id: 5,
                    label: 'Final Decision',
                    icon: ''
                }
            ];

            let statusIndex = steps.findIndex(step => step.label === status);

            return steps.map((step, index) => {
                let stepClass = 'pending'; // Default class for pending

                if (index < statusIndex) {
                    stepClass = 'completed';
                    step.icon = 'bi-check-circle-fill';
                } else if (index === statusIndex) {
                    stepClass = 'ongoing';
                    step.icon = 'bi bi-hourglass-split';
                }

                if (status === 'Submitted' && index === statusIndex) {
                    stepClass = 'completed';
                    step.icon = 'bi-check-circle-fill';
                }

                return `<div class="step-modern ${stepClass}">
                <div class="circle">
                    ${step.icon ? `<i class="bi ${step.icon}"></i>` : `<span>${step.id}</span>`}
                </div>
                <div class="label mt-2">${step.label}</div>
                <small class="timestamp">${stepClass === 'completed' ? 'Completed' : stepClass === 'ongoing' ? 'Ongoing' : 'Pending'}</small>
            </div>`
            }).join('');
        }

        function generatePagination(totalPages, currentPage) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = ''; // Clear existing pagination

            // Previous Button
            pagination.innerHTML +=
                `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="?page=${currentPage - 1}">Previous</a>
            </li>
            `;

            // Add logic for pages with ellipsis
            const pageRange = 3; // Number of pages to show around the current page
            const startPage = Math.max(1, currentPage - pageRange);
            const endPage = Math.min(totalPages, currentPage + pageRange);

            if (startPage > 1) {
                pagination.innerHTML +=
                    `<li class="page-item">
                <a class="page-link" href="?page=1">1</a>
            </li>
             `;
                if (startPage > 2) {
                    pagination.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
                }
            }

            // Display pages within the range
            for (let i = startPage; i <= endPage; i++) {
                pagination.innerHTML +=
                    `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="?page=${i}">${i}</a>
            </li>
            `;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination.innerHTML +=
                        `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            `;
                }
                pagination.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="?page=${totalPages}">${totalPages}</a>
            </li>
            `;
            }

            // Next Button
            pagination.innerHTML +=
                `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="?page=${currentPage + 1}">Next</a>
            </li>`;
        }

        function fetchData(page) {
            fetch(`../actions/track_action.php?page=${page}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(json => {
                    const data = json.data.map(item => ({
                        ...item,
                        application_type: capitalizeWords(item.application_type)
                    }));
                    const totalPages = json.pagination.total_pages;
                    generateCards(data);
                    generatePagination(totalPages, page);
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                });
        }



        function attachWithdrawListeners() {
            document.querySelector(".row.g-5").addEventListener("click", function(event) {
                if (event.target.classList.contains("confirmWithdraw")) {
                    let appId = event.target.dataset.id;
                    let appName = event.target.dataset.app_name;
                    console.log("Application Name:", appName);
                    console.log("Withdrawing application ID:", appId);

                    fetch("../actions/track_action.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id: appId,
                                name: appName
                            })
                        })
                        .then(response => response.json())
                        .then(data => {

                            console.log(data);

                            if (data.success) {
                                document.getElementById('responseModalBody').innerHTML = data.message.join('<br>');
                                document.getElementById('responseModalBody').classList.remove('text-danger');
                                document.getElementById('responseModalBody').classList.add('text-success');
                            } else {
                                document.getElementById('responseModalBody').innerHTML = data.message.join('<br>');
                                document.getElementById('responseModalBody').classList.remove('text-success');
                                document.getElementById('responseModalBody').classList.add('text-danger');
                            }


                            document.querySelectorAll('.withdrawModal').forEach(modal => {
                                const instance = bootstrap.Modal.getInstance(modal);
                                if (instance) instance.hide();
                            });

                            // Show the modal
                            const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
                            responseModal.show();

                            let count = 4;
                            let countdownInterval = setInterval(function() {
                                count--;
                                document.getElementById("reloadCounter").innerText = "This page will reload in " + count + " seconds";

                                if (count <= 0) {
                                    clearInterval(countdownInterval);
                                    location.reload();
                                }
                            }, 1000);
                            // setTimeout(() => location.reload(), 4000);

                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        }
    </script>
</body>

</html>