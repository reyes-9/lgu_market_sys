<script>
    const themeToggleButton = document.getElementById("theme-toggle");
    const body = document.querySelector('.body');
    const navLinks = document.querySelectorAll('.nav-link');
    const navBrand = document.querySelector('.navbar-brand');
    const footer = document.querySelector('footer.light');
    const footerLinks = document.querySelectorAll('.footer-links');

    themeToggleButton.addEventListener("click", () => {

        body.classList.toggle("dark");
        body.classList.toggle("light");
        navbar.classList.toggle('dark');
        navbar.classList.toggle('light');
        navBrand.classList.toggle('dark');
        navBrand.classList.toggle('light');
        footer.classList.toggle('dark');
        footer.classList.toggle('light');

        const isDarkMode = document.body.classList.contains('dark');

        navLinks.forEach(nlink => {
            nlink.classList.toggle('dark', isDarkMode);
            nlink.classList.toggle('light', !isDarkMode);
        });

        footerLinks.forEach(flink => {
            flink.classList.toggle('dark', isDarkMode);
            flink.classList.toggle('light', !isDarkMode);
        });


        const icon = body.classList.contains("dark") ? "bi-sun" : "bi-moon";
        const color = body.classList.contains("dark") ? "style='color: #c9d1d9;'" : ""; // Set color for sun icon
        themeToggleButton.innerHTML = `<i class="bi ${icon}" ${color}></i>`;

    });
</script>