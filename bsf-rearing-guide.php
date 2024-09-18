<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "larvai_monitoring";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch historical data for temperature, humidity, moisture, and NPK
$sql = "SELECT timestamp, temperature, humidity, moisture, nitrogen, phosphorus, potassium FROM environmental_data ORDER BY timestamp DESC";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Prepare data for JavaScript
$timestamps = array_column($data, 'timestamp');
$temperatures = array_column($data, 'temperature');
$humidities = array_column($data, 'humidity');
$moistures = array_column($data, 'moisture');
$nitrogens = array_column($data, 'nitrogen');
$phosphorus = array_column($data, 'phosphorus');
$potassium = array_column($data, 'potassium');

// Convert PHP arrays to JSON for use in JavaScript
$timestamps_json = json_encode($timestamps);
$temperatures_json = json_encode($temperatures);
$humidities_json = json_encode($humidities);
$moistures_json = json_encode($moistures);
$nitrogens_json = json_encode($nitrogens);
$phosphorus_json = json_encode($phosphorus);
$potassium_json = json_encode($potassium);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSF Rearing Guide</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <h1>LarvAI Monitoring Platform</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="features.php">Features</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="history.php">History</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <div class="container">
        <main>
            <section class="intro">
                <h1>Overview</h1>
                <h3>Welcome to the BSF Rearing Guide. This guide provides insights into best practices for rearing Black Soldier Fly (BSF) larvae based on historical data and expert recommendations.</h3>
            </section>

            <section class="data-evaluation">
                <h2>Data Evaluation</h2>
                <canvas id="dataChart" width="800" height="400"></canvas>
                <p id="recommendation"></p>
            </section>

            <section class="rearing-practices">
                <h3>Good Rearing Practices</h3>
                <ul>
                    <li>Maintain optimal temperature: 27-30°C for larvae.</li>
                    <li>Ensure adequate humidity: 60-70% relative humidity.</li>
                    <li>Monitor moisture levels: Keep waste material moist but not overly wet.</li>
                    <li>Provide proper ventilation: Ensure good airflow in the rearing environment.</li>
                    <li>Regularly clean and sanitize rearing areas to prevent contamination.</li>
                </ul>
            </section>
        </main>
        
        <!-- Footer -->
        <footer>
            <div class="container-fluid">
                <p>&copy; 2024 LarvAI Monitoring Platform. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Data from PHP
        const timestamps = <?php echo $timestamps_json; ?>;
        const temperatures = <?php echo $temperatures_json; ?>;
        const humidities = <?php echo $humidities_json; ?>;
        const moistures = <?php echo $moistures_json; ?>;
        const nitrogens = <?php echo $nitrogens_json; ?>;
        const phosphorus = <?php echo $phosphorus_json; ?>;
        const potassium = <?php echo $potassium_json; ?>;

        // Chart.js setup
        const ctx = document.getElementById('dataChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: temperatures,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: false
                    },
                    {
                        label: 'Humidity (%)',
                        data: humidities,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        fill: false
                    },
                    {
                        label: 'Moisture (%)',
                        data: moistures,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        fill: false
                    },
                    {
                        label: 'Nitrogen (N)',
                        data: nitrogens,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: false
                    },
                    {
                        label: 'Phosphorus (P)',
                        data: phosphorus,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: false
                    },
                    {
                        label: 'Potassium (K)',
                        data: potassium,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // Example recommendation (replace with actual logic based on data analysis)
        const recommendation = 'Based on historical data, ensure the temperature is maintained between 27-30°C, humidity is between 60-70%, moisture levels are monitored closely, and the NPK ratios are optimized for better larvae performance.';
        document.getElementById('recommendation').textContent = recommendation;
    </script>
</body>
</html>
