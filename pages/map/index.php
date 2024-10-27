<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/map.css">
    <link rel="icon" type="image/png" href="../../images/favicon_192.png">
    <title>Vendor Mapping - Public Market Monitoring System</title>
</head>

<body class="body light">
    <?php include '../../includes/nav.php'; ?>\

    <dic class="container">
        <h1>Market Vicinity Map</h1>
        <svg width="500" height="500" viewBox="0 0 500 500">
            <!-- Roads -->
            <rect x="0" y="220" width="500" height="40" fill="#aaa" />
            <rect x="220" y="0" width="40" height="500" fill="#aaa" />

            <!-- Market -->
            <rect x="200" y="200" width="100" height="100" fill="#76c7c0" class="hover-effect" onclick="showInfo(event, 'This is the main market.')" />
            <text x="250" y="250" font-size="16" text-anchor="middle" fill="#000">Market</text>

            <!-- Shops -->
            <rect x="50" y="50" width="80" height="40" fill="#ffcc66" class="hover-effect" onclick="showInfo(event, 'Shop 1: Fresh produce.')" />
            <text x="90" y="75" font-size="12" text-anchor="middle" fill="#000">Shop 1</text>

            <rect x="350" y="50" width="80" height="40" fill="#ffcc66" class="hover-effect" onclick="showInfo(event, 'Shop 2: Local crafts.')" />
            <text x="390" y="75" font-size="12" text-anchor="middle" fill="#000">Shop 2</text>

            <rect x="50" y="350" width="80" height="40" fill="#ffcc66" class="hover-effect" onclick="showInfo(event, 'Shop 3: Organic goods.')" />
            <text x="90" y="375" font-size="12" text-anchor="middle" fill="#000">Shop 3</text>

            <rect x="350" y="350" width="80" height="40" fill="#ffcc66" class="hover-effect" onclick="showInfo(event, 'Shop 4: Handmade items.')" />
            <text x="390" y="375" font-size="12" text-anchor="middle" fill="#000">Shop 4</text>

            <!-- Park -->
            <rect x="100" y="100" width="300" height="100" fill="#a4d65e" class="hover-effect" onclick="showInfo(event, 'Park: Relax and enjoy nature.')" />
            <text x="250" y="150" font-size="16" text-anchor="middle" fill="#000">Park</text>

            <!-- Community Center -->
            <rect x="150" y="400" width="200" height="50" fill="#ff9f9f" class="hover-effect" onclick="showInfo(event, 'Community Center: Events and activities.')" />
            <text x="250" y="425" font-size="12" text-anchor="middle" fill="#000">Community Center</text>
        </svg>

        <div id="info" class="info"></div>
    </dic>



    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/theme.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showInfo(event, message) {
            const infoBox = document.getElementById('info');
            infoBox.innerText = message;
            infoBox.style.left = event.pageX + 'px';
            infoBox.style.top = event.pageY + 'px';
            infoBox.style.display = 'block';
        }

        document.addEventListener('click', function(event) {
            const infoBox = document.getElementById('info');
            if (!infoBox.contains(event.target)) {
                infoBox.style.display = 'none';
            }
        });
    </script>

</body>

</html>