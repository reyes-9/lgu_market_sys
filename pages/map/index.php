    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../assets/css/map.css">
        <link rel="icon" type="image/png" href="../../images/favicon_192.png">
        <?php include '../../includes/cdn-resources.php'; ?>
        <title>Vendor Mapping - Public Market Monitoring System</title>
    </head>

    <body class="body light">
        <?php include '../../includes/nav.php'; ?>

        <div class="content-wrapper">
            <div class="container-fluid market-section">
                <div class="row">

                    <div class="container mt-5">
                        <h3 class="text-center m-4"><strong>Vendor Mapping</strong></h3>
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
                                        class=""
                                        id="google-maps"
                                        src=""
                                        width="400"
                                        height="300"
                                        style="border:0;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                    <p id="maps-error" class="d-none text-center" style="color: red;">No map available.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-5 p-0 d-none" id="map_section">
                    <hr>

                    <div class="container text-center my-5">
                        <div class="container mt-4">
                            <h3 class="mb-3">Market Stalls Map</h3>
                            <p class="text-muted">Use the search bar below to find a stall by entering its stall number.</p>

                            <div class="input-group mb-3 m-auto searchbar">
                                <input type="text" id="stallSearchInput" class="form-control" placeholder="Search by Stall Number..." onkeyup="">
                                <button class="btn btn-search" id="stallSearchBtn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>

                            <div id="searchResult" class="alert alert-info d-none"></div>
                        </div>

                        <div class="market-map-container"></div>

                        <div class="container mt-5 py-3 legends">
                            <h4 class="text-center">Legends</h4>
                            <!-- <h5 class="text-start mb-3">Sections:</h5> -->
                            <div class="table-responsive border">
                                <table class="table legend-table">
                                    <thead>
                                        <th>
                                            Section
                                        </th>
                                        <th>
                                            Total Stall Count
                                        </th>
                                        <th>
                                            Section
                                        </th>
                                        <th>
                                            Total Stall Count
                                        </th>
                                        <th>
                                            Section
                                        </th>
                                        <th>
                                            Total Stall Count
                                        </th>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="legend-item"><span class="legend-box carinderia"></span> Carinderia</div>
                                            </td>
                                            <td>
                                                21
                                            </td>
                                            <td>
                                                <div class="legend-item"><span class="legend-box meat"></span> Meat</div>
                                            </td>
                                            <td>
                                                21
                                            </td>
                                            <td>
                                                <div class="legend-item"><span class="legend-box dry-goods"></span> Dry Goods</div>
                                            </td>
                                            <td>
                                                22
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="legend-item"><span class="legend-box vegetables"></span> Vegetables</div>
                                            </td>
                                            <td>
                                                22
                                            </td>
                                            <td>
                                                <div class="legend-item"><span class="legend-box grocery"></span> Grocery</div>
                                            </td>
                                            <td>
                                                22
                                            </td>
                                            <td>
                                                <div class="legend-item"><span class="legend-box fish"></span> Fish</div>
                                            </td>
                                            <td>
                                                22
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5>Total No. of Stalls: <span id="totalStallCount"></span></h5>
                            </div>
                        </div>

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

                <hr>

            </div>
        </div>
        <!-- Stall Feedback & Rating section -->
        <div class="container review-page d-none" id="reviewPage">

            <h2 class="fw-bold">Stall Reviews</h2>
            <hr>
            <!-- Stall Info (Styled to Match Review UI) -->
            <div class="card border-0 py-4 rounded-4 mt-3 bg-transparent">
                <h4 class="fw-semibold">Stall- <span id="titleStallNumber"></span> - <span id="titleSection"></span> </h4>
                <div class="d-flex align-items-center gap-3">
                    <p class="mb-1"><i class="bi bi-geo-alt-fill text-danger"></i> <strong>Market: </strong> <span id="subTitleMarket"></span> </p>
                    <p class="mb-1"><i class="bi bi-shop text-success"></i> <strong>Section: </strong> <span id="subTitleSection"></span> </p>
                    <p class="mb-1"><i class="bi bi-card-list"></i> <strong>Stall Number: </strong> <span id="subTitleStallNumber"></span> </p>
                </div>
            </div>

            <!-- Overall Rating -->
            <div class="row mt-4">

                <!-- Rating Summary -->
                <div class="col-md-2 d-flex flex-column align-items-center stall-ratings">
                    <h1 class="fw-bold rating-average" id="ratingAverage"></h1>
                    <div class="text-center">
                        <span class="text-warning fs-5 d-block" id="ratingStars"></span>
                        <p class="text-muted small mb-0" id="totalRatingsCount"></p>
                    </div>
                </div>

                <!-- Ratings Breakdown -->
                <div class="col-md-8 ms-4 d-flex flex-column justify-content-center rating-progress" id="ratingsBreakdownContainer">

                </div>

            </div>


            <!-- Category Ratings -->
            <div class="mt-4 d-flex flex-wrap gap-2" id="ratingTags">
                <span class="badge bg-light text-dark p-2 fw-semibold">Cleanliness</span>
                <span class="badge bg-light text-dark p-2 fw-semibold">Safety & Security</span>
                <span class="badge bg-light text-dark p-2 fw-semibold">Staff</span>
                <span class="badge bg-light text-dark p-2 fw-semibold">Amenities</span>
                <span class="badge bg-light text-dark p-2 fw-semibold">Location</span>
            </div>

            <!-- User Reviews -->
            <div class="mt-4" id="commentDiv">
            </div>

            <!-- Read More -->
            <div class="mt-3">
                <a href="#" class="text-primary fw-bold">Read all reviews →</a>
            </div>


            <!-- Feedback Submission Form (Matching Review Style) -->
            <hr>
            <div class="container mt-5 border-1">
                <div class="review-form border-0 rounded-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square text-success"></i> Leave us a review.</h5>

                    <!-- Star Rating Selection -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div id="starRating" class="fs-4 text-warning">
                            <i class="bi bi-star icon" data-value="1"></i>
                            <i class="bi bi-star icon" data-value="2"></i>
                            <i class="bi bi-star icon" data-value="3"></i>
                            <i class="bi bi-star icon" data-value="4"></i>
                            <i class="bi bi-star icon" data-value="5"></i>
                        </div>
                        <span id="ratingText" class="fw-semibold text-dark">Select a rating</span>
                    </div>



                    <!-- Rating Tags -->
                    <div class="my-3 d-flex flex-wrap gap-2" id="ratingTags">
                        <button class="badge text-dark p-2 fw-semibold" onclick="addTag('Cleanliness')">Cleanliness</button>
                        <button class="badge text-dark p-2 fw-semibold" onclick="addTag('Safety & Security')">Safety & Security</button>
                        <button class="badge text-dark p-2 fw-semibold" onclick="addTag('Staff')">Staff</button>
                        <button class="badge text-dark p-2 fw-semibold" onclick="addTag('Amenities')">Amenities</button>
                        <button class="badge text-dark p-2 fw-semibold" onclick="addTag('Location')">Location</button>
                    </div>

                    <!-- Comment Box (contentEditable for inline tags) -->
                    <div id="commentBox" class="form-control p-2" contenteditable="true" style="min-height: 80px; position: relative;">
                        <span class="text-body-tertiary" id="placeholderText">Write your feedback...</span>
                    </div>


                    <!-- Submit Button -->
                    <button class="btn btn-success rounded-pill shadow-sm py-2 mt-3 fw-semibold" id="submitReviewButton" onclick="submitFeedback()">Submit Review</button>
                </div>
            </div>

        </div>


        <!-- Stall Info Card -->
        <div id="stall-info" class="stall-info d-none p-3 bg-light shadow rounded">
            <button id="close-stall-info" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close"></button>

            <h4><strong>Stall Details</strong></h4>
            <table class="table table-borderless stall-info-table">
                <tbody>
                    <tr>
                        <th>Stall Number: </th>
                        <td id="stall-number"></td>
                    </tr>
                    <tr>
                        <th>Rental Fee: </th>
                        <td id="rental-fee"></td>
                    </tr>
                    <tr>
                        <th>Stall Size: </th>
                        <td id="stall-size"></td>
                    </tr>
                    <tr>
                        <th>Status: </th>
                        <td id="stall-status"></td>
                    </tr>
                    <tr>
                        <th>Section: </th>
                        <td id="stall-section"></td>
                    </tr>
                    <tr>
                        <th>Owner: </th>
                        <td id="stall-owner"></td>
                    </tr>
                </tbody>
            </table>

            <div class="btn-group mt-2 d-flex justify-content-between w-100">
                <button class="btn btn-sm me-3 py-1 rounded-2" id="applyStall" href="#"
                    data-stall-number=""
                    data-section=""
                    data-market="">
                    Apply
                </button>
                <button class="btn btn-sm py-1 rounded-2 bg-success-subtle" id="reviewBtn"
                    onclick="openReviewPage()"
                    data-stall-number=""
                    data-stall-id=""
                    data-section=""
                    data-market=""><i class="bi bi-hand-thumbs-up-fill"></i> Leave us a review </button>
            </div>
        </div>


        <?php include '../../includes/footer.php'; ?>
        <?php include '../../includes/theme.php'; ?>

        <script>
            let marketId;
            let stallsData = []; // Declare globally
            let previouslySelectedStall = null;
            let applyButton = document.getElementById("applyStall");
            let reviewButton = document.getElementById("reviewBtn");

            window.onload = function() {
                fetchMarketLocations();
            };

            // Add event listener to the apply stall button
            document.getElementById("applyStall").addEventListener("click", function() {

                const stallNumber = applyButton.getAttribute("data-stall-number");
                const section = applyButton.getAttribute("data-section");
                const market = applyButton.getAttribute("data-market");

                if (!stallNumber || !section || !market) {
                    alert("Please select a stall before applying!");
                    return;
                }

                console.log("Values for Redirect: ", stallNumber, section, market);
                redirectToStallApplication(stallNumber, section, market);
            });

            // Add event listener to the market select element
            document.getElementById('market').addEventListener('change', function() {
                const market_select_element = this;
                const selected_market_name = this.options[this.selectedIndex].innerText;
                const selected_market_id = this.value;
                let formattedMarketName;

                loadMarketInfo(market_select_element);
                loadMarketStalls(selected_market_id);
                formnattedMarketUrl = transformToUrl(selected_market_name);
                // Setting up the apply button
                applyButton.setAttribute("data-market", selected_market_name);
                reviewButton.setAttribute("data-market", selected_market_name);

                fetchMarketMap(formnattedMarketUrl);
                document.getElementById("map_section").classList.add("d-none");
            });

            document.getElementById("stallSearchBtn").addEventListener("click", searchStall);


            function fetchComments(stall_id) {
                fetch(`../actions/get_comments.php?stall_id=${stall_id}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("COMMENTS: ", data);

                        const commentContainer = document.getElementById("commentDiv");
                        commentContainer.innerHTML = ""; // Clear previous content

                        if (data.success && data.comments.length > 0) {
                            data.comments.forEach(comment => {
                                const commentHTML = `

                    <div class="d-flex align-items-start mb-3 comment-div" id="commentDiv">
                        <div class="w-100">
                            <h6 class="fw-bold mb-1 d-flex justify-content-between" id="comment-header-1">
                                <span id="commentAuthor">${comment.author}
                                
                                    <span class="text-muted small ms-2" id="commentDate">${timeSince(comment.created_at)}</span>
                                </span>
                                <span class="text-warning d-inline-flex align-items-center" id="commentStars">
                                    <span class="fw-bold me-1" id="commentRating"  style="color: #003366;">${comment.rating.toFixed(1)}</span>
                                    ${generateStarRating(comment.rating)}
                                </span>
                            </h6>
                            <p class="mb-1" id="commentText">${comment.comment}</p>
                        </div>
                    </div>
                    
                    `;

                                commentContainer.innerHTML += commentHTML;
                            });
                        } else {
                            commentContainer.innerHTML = `<p class="text-muted">No comments available.</p>`;
                        }
                    })
                    .catch(error => console.error("Error fetching comments:", error));
            }

            function timeSince(dateString) {
                // Ensure correct date format (MySQL format "YYYY-MM-DD HH:MM:SS" is not always parsed correctly)
                const date = new Date(dateString.replace(/-/g, "/")); // Fix parsing issue

                const seconds = Math.floor((new Date() - date) / 1000);
                const intervals = [{
                        label: "year",
                        seconds: 31536000
                    },
                    {
                        label: "month",
                        seconds: 2592000
                    },
                    {
                        label: "week",
                        seconds: 604800
                    },
                    {
                        label: "day",
                        seconds: 86400
                    },
                    {
                        label: "hour",
                        seconds: 3600
                    },
                    {
                        label: "minute",
                        seconds: 60
                    },
                    {
                        label: "second",
                        seconds: 1
                    }
                ];

                for (const interval of intervals) {
                    const count = Math.floor(seconds / interval.seconds);
                    if (count >= 1) {
                        return `${count} ${interval.label}${count !== 1 ? "s" : ""} ago`;
                    }
                }
                return "just now";
            }

            function generateStarRating(rating) {
                let starsHTML = "";
                const roundedRating = Math.round(rating); // Round to the nearest whole number

                for (let i = 1; i <= 5; i++) {
                    starsHTML += `<i class="bi ${i <= roundedRating ? 'bi-star-fill' : 'bi-star'}"></i> `;
                }

                return starsHTML;
            }
            async function submitFeedback() {
                const placeholderText = document.getElementById("placeholderText"); // Ensure it's defined
                // Get review details
                let rating = document.querySelector("#starRating .bi-star-fill") ? document.querySelectorAll("#starRating .bi-star-fill").length : 0;
                let commentBox = document.getElementById("commentBox");
                let feedback = commentBox ? commentBox.innerText.trim() : "";
                const stall_id = reviewButton.getAttribute("data-stall-id");
                const submitButton = document.getElementById("submitReviewButton"); // Ensure your button has this ID

                // Validate if feedback is empty
                if (!rating) {
                    alert("Please select a rating.");
                    return;
                }

                if (!feedback) {
                    alert("Please enter your feedback.");
                    return;
                }

                // Store original button text
                const originalButtonText = submitButton.innerHTML;

                try {
                    // Show Bootstrap spinner & disable button
                    submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;
                    submitButton.disabled = true;

                    // Analyze sentiment
                    const sentiment = await analyzeSentiment(feedback);
                    console.log("Detected sentiment:", sentiment);

                    // Prepare form data
                    let formData = new FormData();
                    formData.append("stall_id", stall_id);
                    formData.append("rating", rating);
                    formData.append("comment", feedback);
                    formData.append("sentiment", sentiment); // Append analyzed sentiment

                    // 🔹 Log form data before submission
                    console.log(" Form Data Before Sending:");
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}:`, value);
                    }

                    // Send data to server
                    const response = await fetch("../actions/submit_review.php", {
                        method: "POST",
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert("Feedback submitted successfully!");

                        // Clear the comment box properly
                        commentBox.innerHTML = "";

                        // Reset the rating
                        resetStarRating();

                        // Remove all existing tags
                        document.querySelectorAll("#commentBox .badge").forEach(tag => tag.remove());

                        // Ensure placeholder text is shown again
                        if (placeholderText) {
                            placeholderText.classList.remove("d-none");
                        }

                        // Allow adding new tags again
                        commentBox.contentEditable = "true"; // Ensure it's still editable
                        commentBox.focus(); // Move cursor to input area

                        // Rebind event listeners for clicking on tags (Fix for disappearing functionality)
                        document.querySelectorAll("#ratingTags button").forEach(button => {
                            button.onclick = function() {
                                addTag(this.innerText); // Reattach click event for adding tags
                            };
                        });
                    } else {
                        alert("Failed to submit feedback. Please try again.");
                    }
                } catch (error) {
                    console.error("Error submitting feedback:", error);
                    alert("An error occurred while submitting feedback.");
                } finally {
                    // Restore button text & enable button
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                }
            }


            async function analyzeSentiment(message) {
                if (message.trim() === "") {
                    alert("Please enter feedback before analyzing.");
                    return;
                }

                console.log("Sending feedback:", message);

                try {
                    const response = await fetch("../actions/sentiment.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            feedback: message
                        })
                    });

                    const data = await response.json();
                    console.log("API Response:", data);

                    return data.sentiment;
                } catch (error) {
                    console.error("Error:", error);
                    return "undefined";
                }
            }

            function setReviewSection(stall_number, section, market, stall_id) {
                let title_stall_number = document.getElementById("titleStallNumber");
                let title_section = document.getElementById("titleSection");
                let sub_title_market = document.getElementById("subTitleMarket");
                let sub_title_section = document.getElementById("subTitleSection");
                let sub_title_stall_number = document.getElementById("subTitleStallNumber");

                // Assign the values to the respective elements
                if (title_stall_number) title_stall_number.textContent = stall_number || "N/A";
                if (title_section) title_section.textContent = section || "N/A";
                if (sub_title_market) sub_title_market.textContent = market || "N/A";
                if (sub_title_section) sub_title_section.textContent = section || "N/A";
                if (sub_title_stall_number) sub_title_stall_number.textContent = stall_number || "N/A";


                handlePlaceholder();
                initializeStarRating();
                fetchComments(stall_id);
                fetchRatingsData(stall_id);
                resetRatings();

            }

            function fetchRatingsData(stall_id) {
                // Fetch the ratings data from the server (AJAX request)
                fetch(`../actions/get_ratings.php?stall_id=${stall_id}`)
                    .then(response => response.json())
                    .then(data => {

                        // Clear any existing ratings breakdown content
                        ratingsBreakdownContainer.innerHTML = "";

                        // Set the ratings and create the progress bars
                        setRatings(data.averageRating, data.totalReviews, data.averageRating, data.threeStarPercentage);
                        console.log("PERCENTAGE DATA: ", data.fiveStarPercentage, data.fourStarPercentage)
                        createRatingProgressBar(ratingsBreakdownContainer, "5.0", data.fiveStarPercentage, `${data.fiveStarCount} reviews`);
                        createRatingProgressBar(ratingsBreakdownContainer, "4.0", data.fourStarPercentage, `${data.fourStarCount} reviews`);
                        createRatingProgressBar(ratingsBreakdownContainer, "3.0", data.threeStarPercentage, `${data.threeStarCount} reviews`);

                        // Display the average rating
                        document.getElementById("averageRating").textContent = `Average Rating: ${data.averageRating} / 5`;

                    })
                    .catch(error => console.error('Error fetching ratings data:', error));
            }

            function resetRatings() {
                const average_element = document.getElementById("ratingAverage");
                const total_rating_element = document.getElementById("totalRatingsCount");
                const ratingStarsElement = document.getElementById("ratingStars");

                if (average_element) {
                    average_element.textContent = "N/A"; // Reset to "N/A" if no data
                }
                if (total_rating_element) {
                    total_rating_element.textContent = "0 ratings"; // Reset to "0 ratings"
                }
                if (ratingStarsElement) {
                    // Clear the stars (reset them)
                    ratingStarsElement.innerHTML = '';
                }

                // Reset any other rating-related elements if necessary
                ratingsBreakdownContainer.innerHTML = ""; // Clear the progress bars or other elements
            }

            function setRatings(rating_average, total_ratings_count, average_rating) {
                const average_element = document.getElementById("ratingAverage");
                const total_rating_element = document.getElementById("totalRatingsCount");
                const ratingStarsElement = document.getElementById("ratingStars");

                // Check if the elements exist before setting textContent
                if (average_element) {
                    average_element.textContent = rating_average;
                } else {
                    console.error("Element with id 'ratingAverage' not found.");
                }

                if (total_rating_element) {
                    total_rating_element.textContent = total_ratings_count + " ratings";
                } else {
                    console.error("Element with id 'totalRatingsCount' not found.");
                }

                // Log the values for debugging
                console.log("Rating average:", rating_average, "Total ratings count:", total_ratings_count);

                // Handle the stars display based on the average rating
                if (ratingStarsElement) {
                    // Clear the existing stars
                    ratingStarsElement.innerHTML = '';

                    // Round the average rating to the nearest integer (up to 5 stars)
                    const roundedRating = Math.round(rating_average);

                    // Loop through and create star elements
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement("i");
                        star.classList.add("bi", i <= roundedRating ? "bi-star-fill" : "bi-star");
                        ratingStarsElement.appendChild(star);
                    }
                } else {
                    console.error("Element with id 'ratingStars' not found.");
                }
            }

            function createRatingProgressBar(container, rating, percentage, reviewCount) {
                const ratingDiv = document.createElement("div");
                ratingDiv.classList.add("d-flex", "align-items-center", "mb-2");

                const ratingText = document.createElement("div");
                ratingText.classList.add("text-muted", "small");
                ratingText.textContent = rating;

                const progressContainer = document.createElement("div");
                progressContainer.classList.add("progress", "flex-grow-1", "mx-2");

                console.log("PERCENTAGE: ", percentage);
                if (percentage === undefined) {
                    console.log("NULL: ", percentage);
                } else {
                    console.log("NOT NULL: ", percentage);
                }
                const progressBar = document.createElement("div");
                progressBar.classList.add("progress-bar", "bg-warning");
                progressBar.style.width = `${percentage}%`;

                const reviewCountDiv = document.createElement("div");
                reviewCountDiv.classList.add("text-muted", "small");
                reviewCountDiv.textContent = reviewCount;

                progressContainer.appendChild(progressBar);
                ratingDiv.appendChild(ratingText);
                ratingDiv.appendChild(progressContainer);
                ratingDiv.appendChild(reviewCountDiv);

                container.appendChild(ratingDiv);
            }

            function openReviewPage() {
                review_page = document.getElementById("reviewPage");
                if (reviewPage) {

                    const stall_number = reviewButton.getAttribute("data-stall-number");
                    const section = reviewButton.getAttribute("data-section");
                    const market = reviewButton.getAttribute("data-market");
                    const stall_id = reviewButton.getAttribute("data-stall-id");

                    console.log("Passed to set the review page: ", stall_number, section, market);

                    setReviewSection(stall_number, section, market, stall_id);

                    reviewPage.classList.remove("d-none");
                    reviewPage.scrollIntoView({
                        behavior: "smooth"
                    });
                }


            }

            function handlePlaceholder() {
                const commentBox = document.getElementById("commentBox");
                const placeholderText = document.getElementById("placeholderText");

                commentBox.addEventListener("focus", function() {
                    placeholderText.classList.add("d-none"); // Hide placeholder when focused
                });

                commentBox.addEventListener("blur", function() {
                    if (commentBox.innerText.trim() === "") {
                        placeholderText.classList.remove("d-none"); // Show placeholder if empty
                    }
                });
            }

            function isDivEmpty(divElement) {
                return divElement.innerHTML.trim() === ""; // Checks if content is empty after trimming whitespace
            }

            function addTag(tag) {
                const commentBox = document.getElementById("commentBox");
                const placeholder_text = document.getElementById("placeholderText"); // Ensure this exists

                // 🔹 Ensure commentBox exists
                if (!commentBox) {
                    console.error("Error: commentBox not found!");
                    return;
                }

                // 🔹 Hide placeholder if commentBox is not empty
                if (placeholder_text) {
                    placeholder_text.classList.add("d-none");
                }

                // 🔹 Check if the tag already exists inside the comment box
                if (document.getElementById(`tag-${tag}`)) return;

                // 🔹 Create tag span
                const tagSpan = document.createElement("span");
                tagSpan.classList.add("badge", "bg-success-subtle", "text-dark", "rounded-pill", "me-2", "p-2");
                tagSpan.id = `tag-${tag}`;
                tagSpan.contentEditable = "false"; // Prevent user from editing the tag itself

                // 🔹 Create remove button
                const removeBtn = document.createElement("button");
                removeBtn.classList.add("btn-close", "btn-close-dark", "ms-1");
                removeBtn.style.fontSize = "10px";
                removeBtn.onclick = function() {
                    tagSpan.remove(); // Remove the tag on click

                    // 🔹 Show placeholder text again if comment box is empty
                    if (placeholder_text && isDivEmpty(commentBox)) {
                        placeholder_text.classList.remove("d-none");
                    }
                };

                // 🔹 Append text and button inside the span
                tagSpan.innerText = tag + " ";
                tagSpan.appendChild(removeBtn);

                // 🔹 Insert tag at the beginning of the comment box
                commentBox.insertBefore(tagSpan, commentBox.firstChild);

                // 🔹 Add space after the tag for better formatting
                commentBox.insertBefore(document.createTextNode(" "), commentBox.firstChild.nextSibling);

                // 🔹 Move the cursor to the end of the comment box
                commentBox.focus();

                // 🔹 Ensure the cursor stays at the end of the comment box
                const range = document.createRange();
                const selection = window.getSelection();
                range.selectNodeContents(commentBox);
                range.collapse(false); // Move to the end of the content
                selection.removeAllRanges();
                selection.addRange(range);
            }

            function resetStarRating() {
                const stars = document.querySelectorAll("#starRating i");
                const ratingText = document.getElementById("ratingText");

                // Reset all stars to empty (unfilled)
                stars.forEach(star => {
                    star.classList.remove("bi-star-fill");
                    star.classList.add("bi-star");
                });

                // Reset the rating text
                ratingText.innerText = "You rated 0 star(s)";
            }

            function initializeStarRating() {
                const stars = document.querySelectorAll("#starRating i");
                const ratingText = document.getElementById("ratingText");
                let selectedRating = 0;

                stars.forEach(star => {
                    star.addEventListener("mouseenter", function() {
                        highlightStars(this.dataset.value);
                    });

                    star.addEventListener("mouseleave", function() {
                        highlightStars(selectedRating); // Reset to selected rating on mouse leave
                    });

                    star.addEventListener("click", function() {
                        selectedRating = this.dataset.value;
                        ratingText.innerText = `You rated ${selectedRating} star(s)`;
                    });
                });

                function highlightStars(rating) {
                    stars.forEach(star => {
                        star.classList.remove("bi-star-fill");
                        star.classList.add("bi-star");
                    });

                    for (let i = 0; i < rating; i++) {
                        stars[i].classList.remove("bi-star");
                        stars[i].classList.add("bi-star-fill");
                    }
                }
            }

            function redirectToStallApplication(stallNumber, section, market) {
                console.log("Stall Number:", stallNumber);
                console.log("Section:", section);
                console.log("Market:", market);

                if (!applyButton) {
                    console.warn("Apply button not found!");
                    return;
                }

                const stall_app_url = `http://localhost/lgu_market_sys/pages/stall_app/index.php?stall_number=${encodeURIComponent(stallNumber)}&section=${encodeURIComponent(section)}&market=${encodeURIComponent(market)}&from=mapping`;
                window.location.href = stall_app_url;
            }

            function findStallElement(stallNumber) {
                return document.getElementById(`${stallNumber.toLowerCase()}`);
            }

            function searchStall() {
                const stallInput = document.getElementById("stallSearchInput").value.trim().toUpperCase();

                if (stallInput) {
                    const stallElement = findStallElement(stallInput);

                    if (stallElement) {
                        setStallDetails(stallInput, stallElement);
                    } else {
                        console.warn(`Stall ${stallInput} not found.`);
                        alert(`Stall ${stallInput} not found.`);
                    }
                } else {
                    alert("Please enter a stall number.");
                }
            }

            function fetchMarketLocations() {
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

            function fetchMarketMap(url) {
                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector(".market-map-container").innerHTML = data;
                        attachEventListenersToStalls();
                    });
            }

            function transformToUrl(text) {
                return "../../maps/" +
                    text
                    .toLowerCase() // Convert to lowercase
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters except spaces and hyphens
                    .replace(/[-\s]+/g, '_') // Replace spaces and hyphens with underscores
                    +
                    ".svg"; // Append .svg extension
            }

            function attachEventListenersToStalls() {
                const stalls = document.querySelectorAll(".stall");
                stalls.forEach(stall => {
                    stall.addEventListener("click", function() {
                        // alert("Stall clicked: " + stall.id);
                        setStallDetails(this.id.toUpperCase(), this);
                    });
                });
                const stallInfoContainer = document.getElementById("stall-info");
                const closeButton = document.getElementById("close-stall-info");

                closeButton.addEventListener("click", function() {
                    stallInfoContainer.classList.add("d-none"); // Hide the stall info
                    if (previouslySelectedStall) {
                        previouslySelectedStall.classList.remove("selected");
                        previouslySelectedStall = null;
                    }
                });
            }

            function setStallIndicators() {
                stallsData.forEach(stall => {
                    const indicatorId = `${stall.stall_number.toLowerCase()}_indicator`;
                    const indicatorElement = document.getElementById(indicatorId);

                    if (indicatorElement) {
                        if (stall.status === "occupied") {
                            indicatorElement.style.fill = "red"; // Set occupied stalls to red
                        } else {
                            indicatorElement.style.fill = "green"; // Set vacant stalls to green
                        }
                    } else {
                        console.warn(`Indicator not found for stall: ${stall.stall_number}`);
                    }
                });
            }

            function setStallDetails(stallNumber, stallElement) {

                console.log("Stall Number: ", stallNumber);
                console.log("Stall Element: ", stallElement);
                console.log("Stalls Data:", stallsData);

                const stall = stallsData.find(s => s.stall_number == stallNumber);
                let apply_btn = document.getElementById("applyStall");

                console.log("Stall: ", stall);

                if (!stall) {
                    console.warn("Stall not found in local data.");
                    return;
                }

                const stallInfoContainer = document.getElementById("stall-info");
                stallInfoContainer.classList.remove("d-none");

                // Setting up the apply button
                applyButton.setAttribute("data-stall-number", stall.stall_number);
                applyButton.setAttribute("data-section", stall.section_name);
                reviewButton.setAttribute("data-stall-number", stall.stall_number);
                reviewButton.setAttribute("data-section", stall.section_name);
                reviewButton.setAttribute("data-stall-id", stall.id);

                // Update only the text content instead of overwriting the whole div
                document.getElementById("stall-number").textContent = stall.stall_number;
                document.getElementById("rental-fee").textContent = stall.rental_fee;
                document.getElementById("stall-size").textContent = stall.stall_size;
                document.getElementById("stall-status").textContent = stall.status;
                document.getElementById("stall-owner").textContent = stall.user_name || "Not Assigned";
                document.getElementById("stall-section").textContent = stall.section_name || "N/A";

                if (stall.status === "occupied") {
                    apply_btn.disabled = true;
                } else {
                    apply_btn.disabled = false;
                }

                // Show stall info container
                stallInfoContainer.classList.remove("d-none");

                // Remove selection from previous stall
                if (previouslySelectedStall) {
                    previouslySelectedStall.classList.remove("selected");
                }

                // Apply "selected" style only if the info container is visible
                if (!stallInfoContainer.classList.contains("d-none")) {
                    stallElement.classList.add("selected");
                    previouslySelectedStall = stallElement;
                } else {
                    previouslySelectedStall = null;
                }

                // Position the info container
                positionStallInfoContainer(stallElement);
            }

            function positionStallInfoContainer(stallElement) {
                const stallInfoContainer = document.getElementById("stall-info");

                // Ensure the info box is visible before measuring
                stallInfoContainer.classList.remove("d-none");

                // Get the stall's position
                const stallRect = stallElement.getBoundingClientRect();
                const containerWidth = stallInfoContainer.offsetWidth;
                const containerHeight = stallInfoContainer.offsetHeight;

                // Get screen width and height
                const screenWidth = window.innerWidth;
                const screenHeight = window.innerHeight;

                // Default position (to the right of the stall)
                let left = stallRect.right + 10;
                let top = stallRect.top + window.scrollY - 20;

                // If the info box overflows the right side, place it on the left
                if (left + containerWidth > screenWidth) {
                    left = stallRect.left - containerWidth - 10; // Move to the left
                }

                // If the info box overflows the bottom, adjust upwards
                if (top + containerHeight > screenHeight + window.scrollY) {
                    top = stallRect.bottom + window.scrollY - containerHeight; // Move above the stall
                }

                // Apply calculated position
                stallInfoContainer.style.position = "absolute";
                stallInfoContainer.style.left = `${left}px`;
                stallInfoContainer.style.top = `${top}px`;

                // Ensure the box is visible
                stallInfoContainer.classList.remove("d-none");
            }

            function showStallMap() {
                let mapSection = document.getElementById("map_section");

                if (mapSection) {
                    mapSection.classList.remove("d-none"); // Show the map section

                    // Scroll into view smoothly
                    mapSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start" // Align to the top of the viewport
                    });
                }
            }

            function setMaps(link) {
                let g_maps = document.getElementById("google-maps");
                let error_msg = document.getElementById("maps-error");

                if (!g_maps) {
                    console.error("Element with ID 'google-maps' not found.");
                    return;
                }

                if (!error_msg) {
                    console.error("Element with ID 'maps-error' not found.");
                    return;
                }

                if (!link || link.trim() === "") {
                    g_maps.classList.add("d-none"); // Hide the map
                    error_msg.textContent = "No map available."; // Show error message
                    error_msg.classList.remove("d-none"); // Make error message visible
                } else {
                    g_maps.src = link;
                    g_maps.classList.remove("d-none"); // Show the map
                    error_msg.classList.add("d-none"); // Hide error message
                }
            }
        </script>

        <script>
            function loadMarketStalls(selectedMarketId) {
                stallsData = []; // Reset previous stalls

                fetch("../actions/get_stalls_info.php", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: selectedMarketId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            stallsData = data.stalls;
                            setTimeout(setStallIndicators, 500);
                        } else {
                            console.error("Failed to load stalls:", data.message);
                        }
                    })
                    .catch(error => console.error("Error fetching stalls:", error));
            }

            function loadMarketInfo(marketSelect) {
                document.getElementById('viewStallsBtn').removeAttribute('disabled');

                const selectedOption = marketSelect.options[marketSelect.selectedIndex];
                const selectedId = selectedOption.value;

                // Fetch location data if it exists
                const selectedLocation = locationsData?.find(location => location.id == selectedId);
                document.getElementById('market_address').innerText = selectedLocation ? selectedLocation.market_address : '';
                marketId = selectedId;

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

                        document.getElementById('stall_count').textContent = data?.s_count ?? '';
                        document.getElementById('stall_vacant').textContent = data?.s_vacant ?? '';
                        document.getElementById('stall_occupied').textContent = data?.s_occupied ?? '';

                        document.getElementById('responseContainer').innerText = data.message || '';

                        document.getElementById("totalStallCount").innerText = data?.s_count ?? '';
                        setMaps(data.gmap_link);
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>
    </body>

    </html>