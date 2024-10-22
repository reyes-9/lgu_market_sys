<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/feedback.css">
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <title>Feedback Services - Public Market Monitoring System</title>
</head>

<body class="body light">
    <?php include '../../includes/nav.php'; ?>

    <div class="container faq-section">
        <h1 class="text-center pb-5">Frequently Asked Questions</h1>
        <div class="row">

            <!-- Left Column: Header, Paragraph, and CTA Button -->
            <div class="col-md-4">
                <h2 class="faq-header">Have More Questions?</h2>
                <p class="faq-description">If you don't find the answers you're looking for, feel free to contact us. Our team is here to help!</p>
                <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Contact Us</button>
                <div id="supportFormResponseMessage" class="mt-3"></div> <!-- Response message placeholder -->
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Contact Us</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <form id="supportForm" method="POST" action="feedback_action.php">
                                <input type="hidden" name="type" value="support">
                                <div class="form-group">
                                    <label for="supportMessage">Message</label>
                                    <textarea id="supportMessage" name="message" class="form-control" rows="5" placeholder="Type your support request here..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning mt-3">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: FAQ Accordion -->
            <div class="col-md-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is the return policy?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can return any item within 30 days of purchase as long as it’s in its original condition and packaging.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How do I track my order?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Once your order is shipped, you will receive a tracking link via email to monitor the status of your package.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Can I change my shipping address after placing an order?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, you can change your shipping address within 24 hours of placing your order by contacting our customer service.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    




    <div class="container feedback-section shadow rounded mb-4">
    <div class="row d-flex justify-content-center align-items-stretch border rounded-4">

        <!-- Left Column: Information About feedback -->
        <div class="col-md-4 details d-flex flex-column border rounded-start-3">
            <h1 class="feedback-header">Concerns Submission</h1>
            <p class="feedback-description">Have any concerns or feedback? We're here to listen! Please fill out the form to let us know your thoughts.</p>
        </div>

        <!-- Right Column: Submit Concerns Form -->
        <div class="col-md-8 input d-flex flex-column border rounded-end-3">
            <h1 class="feedback-header">Submit Your Concern</h1>
            <p class="feedback-description">Provide a brief description of your concern below.</p>

            <textarea type="text" class="form-control feedback-input" placeholder="Enter your feedback here" required></textarea>
            <button class="btn btn-warning submit-button">Submit</button>
        </div>

    </div>
</div>








    <!-- Contact Customer Support Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-secondary">Contact Customer Support</h3>

                    <form id="supportForm" method="POST" action="feedback_action.php">
                        <input type="hidden" name="type" value="support">
                        <div class="form-group">
                            <label for="supportMessage">Message</label>
                            <textarea id="supportMessage" name="message" class="form-control" rows="5" placeholder="Type your support request here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit Support Request</button>
                    </form>

                    <div id="supportFormResponseMessage" class="mt-3"></div> <!-- Response message placeholder -->
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h3 class="text-secondary">Chatbot / Chat with System Admin</h3>
                    <p class="text-muted">Currently under development...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Services Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-secondary">Feedback Services</h3>
                    <p>We provide comprehensive feedback services to help you understand your customers better.</p>

                    <h4>Services Offered</h4>
                    <ul>
                        <li>Customer Satisfaction Surveys</li>
                        <li>Product Feedback Collection</li>
                        <li>Employee Engagement Surveys</li>
                        <li>Market Research</li>
                    </ul>

                    <h4>Benefits</h4>
                    <p>Utilize our feedback services to enhance decision-making and improve customer retention.</p>

                    <h4>Our Process</h4>
                    <p>From consultation to delivery of findings, we ensure a smooth experience.</p>

                    <h4>Testimonials</h4>
                    <p>"Their feedback services transformed our customer approach!" - Happy Client</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Submission Form Section -->
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-secondary">Feedback Submission</h3>

                    <form id="feedbackForm" method="POST" action="feedback_action.php">
                        <input type="hidden" name="type" value="feedback">

                        <div class="form-group">
                            <label for="feedbackMessage">Message</label>
                            <textarea id="feedbackMessage" name="message" class="form-control" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Submit Feedback</button>
                    </form>

                    <div id="feedbackFormResponseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to handle form submissions for both feedback and support forms
        function handleFormSubmit(formId) {
            const form = document.getElementById(formId);
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);

                fetch('../actions/feedback_action.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Parse JSON response
                    .then(data => {

                        // Handle success or error response
                        const responseMessage = document.getElementById(`${formId}ResponseMessage`);
                        if (data.status === 'success') {
                            responseMessage.innerHTML = `<p style="color: green;">${data.message}</p>`;
                        } else {
                            responseMessage.innerHTML = `<p style="color: red;">${data.message}</p>`;
                        }
                    })
                    .catch(error => {
                        // Handle network error
                        const responseMessage = document.getElementById(`${formId}ResponseMessage`);
                        responseMessage.innerHTML = `<p style="color: red;">  error: ${error.message}</p>`;
                    });
            });
        }

        handleFormSubmit('feedbackForm'); // For feedback form
        handleFormSubmit('supportForm'); // For support form


        // FAQ toggle functionality using Bootstrap collapse
        $('#faqAccordion .collapse').on('show.bs.collapse', function() {
            $(this).siblings('.card-header').addClass('active');
        }).on('hide.bs.collapse', function() {
            $(this).siblings('.card-header').removeClass('active');
        });
    </script>
</body>

</html>