<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <link rel="stylesheet" href="../../assets/css/feedback.css">
    <title>Feedback Services - Public Market Monitoring System</title>
    <?php include '../../includes/cdn-resources.php' ?>
</head>
<?php
require_once '../../includes/session.php';
?>

<body class="body light">

    <?php include '../../includes/nav.php'; ?>

    <!-- Toast Container -->
    <div class="position-fixed top-10 end-0 p-3" style="z-index: 1050">
        <div id="responseMsg" class="toast align-items-center  border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Feedback submitted successfully!
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="container-wrapper">
        <div class="full-width-section">
            <div class="container feedback-section scroll-section">
                <div class="row d-flex justify-content-center align-items-stretch my-5 feedback-border">

                    <div class="col-md-4 details d-flex flex-column ">
                        <h1 class="header">Feedback Submission</h1>
                        <p class="description">Have any feedback? We're here to listen! Please fill out the form to let us know your thoughts.</p>
                    </div>

                    <div class="col-md-8 input d-flex flex-column">
                        <h1 class="header">What are your thoughts?</h1>

                        <form id="feedbackForm" method="POST" action="../actions/feedback_action.php">
                            <input type="hidden" name="type" value="feedback">

                            <div class="form-group">

                                <textarea id="feedbackMessage" name="message" class="form-control" rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-warning btn-block mt-3">Submit Feedback</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="full-width-section">
            <div class="container faq-section scroll-section">
                <div class="row">

                    <!-- Left Column: Header, Paragraph, and CTA Button -->
                    <div class="col-md-4">
                        <h2 class="header">Have More Questions?</h2>
                        <p class="description">If you don't find the questions you're looking for, feel free to submit one. Our team is here to help!</p>
                        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Submit Questions</button>
                        <div id="faqFormResponseMessage" class="mt-3"></div> <!-- Response message placeholder -->
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

                                    <form id="supportFormModal" method="POST" action="../actions/feedback_action.php">
                                        <input type="hidden" name="type" value="support">
                                        <div class="form-group">

                                            <textarea id="supportMessage" name="message" class="form-control" rows="5" placeholder="Type your question here..." required></textarea>
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
        </div>

        <div class="full-width-section">
            <div class="container concern-section scroll-section">
                <div class="row d-flex justify-content-center align-items-stretch mb-4 feedback-border">

                    <!-- Left Column: Information About feedback -->
                    <div class="col-md-4 details d-flex flex-column">
                        <h1 class="header">Concerns Submission</h1>
                        <p class="description">Have any concerns or feedback? We're here to listen! Please fill out the form to let us know your thoughts.</p>
                    </div>

                    <!-- Right Column: Submit Concerns Form -->
                    <div class="col-md-8 input d-flex flex-column">
                        <h1 class="header">Have an issue?</h1>

                        <form id="supportForm" method="POST" action="../actions/feedback_action.php">
                            <input type="hidden" name="type" value="support">
                            <div class="form-group">

                                <textarea id="supportMessage" name="message" class="form-control" rows="5" placeholder="Type your support request here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block mt-3">Submit Support Request</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>

    <script>
        let sections = document.querySelectorAll(".scroll-section");
        let currentIndex = 0;
        let isScrolling = false;

        document.addEventListener("wheel", (event) => {
            if (isScrolling) return; // Prevent rapid scrolling
            isScrolling = true;

            event.preventDefault();

            let direction = event.deltaY > 0 ? 1 : -1; // Determine scroll direction
            currentIndex = Math.min(Math.max(currentIndex + direction, 0), sections.length - 1);

            sections[currentIndex].scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

            setTimeout(() => {
                isScrolling = false;
            }, 900);
        }, {
            passive: false
        });

        // FAQ toggle 
        $('#faqAccordion .accordion-button').on('click', function() {
            $(this).toggleClass('active');
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("feedbackForm").addEventListener("submit", function(event) {
                event.preventDefault();
                handleFormSubmit("feedbackForm");
            });

            document.getElementById("supportForm").addEventListener("submit", function(event) {
                event.preventDefault();
                handleFormSubmit("supportForm");
            });
        });

        async function handleFormSubmit(formId) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);

            const message = sanitizeInput(formData.get("message"));
            const submitButton = form.querySelector("button[type='submit']");

            // Extract the form type (feedback/support)
            const formType = formData.get("type"); // Ensure your form has an input named "type"
            const originalButtonText = submitButton.innerHTML;

            try {

                submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;
                submitButton.disabled = true;

                let sentiment = "undefined"; // Default sentiment if not analyzed

                if (formType === "feedback") {
                    sentiment = await analyzeSentiment(message);
                    console.log("Detected sentiment:", sentiment);
                    formData.append("sentiment", sentiment);
                }

                const response = await fetch('../actions/feedback_action.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                switch (data.type) {
                    case 'feedback':
                    case 'support':
                        showToast(data.message, data.status);
                        form.reset();
                        break;
                    default:
                        showToast("Unexpected response type.", "error");
                        form.reset();
                }
            } catch (error) {
                showToast(`Error: ${error.message}`, "error");
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
                    redirect: "follow",
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
        const sanitizeInput = (input) => {
            const temp = document.createElement("div");
            temp.textContent = input; // Escapes HTML characters
            return temp.innerHTML;
        };

        function showToast(message, status) {
            var toastEl = document.getElementById("responseMsg");
            toastEl.querySelector(".toast-body").innerText = message;
            var toast = new bootstrap.Toast(toastEl);
            toast.show();

            toastEl.classList.remove("text-bg-success", "text-bg-danger");
            if (status === "success") toastEl.classList.add("text-bg-success");
            else if (status === "error") toastEl.classList.add("text-bg-danger");
        }
    </script>
</body>

</html>