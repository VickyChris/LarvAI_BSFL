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

// Fetch historical data from the "environmental_data" table
$sql = "SELECT timestamp, temperature, humidity, moisture FROM environmental_data ORDER BY timestamp ASC";
$result = $conn->query($sql);

$timestamps = [];
$temperatures = [];
$humidities = [];
$moistures = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timestamps[] = $row['timestamp'];
        $temperatures[] = $row['temperature'];
        $humidities[] = $row['humidity'];
        $moistures[] = $row['moisture'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - LarvAI Monitoring Platform</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Add Chart.js -->
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
    <div class="container main-content">
        <h2>Historical Data</h2>
        
        <!-- Chart Section -->
        <section class="charts">
            <h3>Main Environmental Parameters Over Time | Temperature | Humidity | Moisture </h3>
            <div class="chart-container">
                <canvas id="temperatureChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="humidityChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="moistureChart"></canvas>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 LarvAI Monitoring Platform. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                </ul>
            </nav>
        </div>
    </footer>

    <!-- Chart.js Script -->
    <script>
        const ctxTemperature = document.getElementById('temperatureChart').getContext('2d');
        const ctxHumidity = document.getElementById('humidityChart').getContext('2d');
        const ctxMoisture = document.getElementById('moistureChart').getContext('2d');

        const temperatureChart = new Chart(ctxTemperature, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($timestamps); ?>,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: <?php echo json_encode($temperatures); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temperature (°C)'
                        }
                    }
                }
            }
        });

        const humidityChart = new Chart(ctxHumidity, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($timestamps); ?>,
                datasets: [{
                    label: 'Humidity (%)',
                    data: <?php echo json_encode($humidities); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Humidity (%)'
                        }
                    }
                }
            }
        });

        const moistureChart = new Chart(ctxMoisture, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($timestamps); ?>,
                datasets: [{
                    label: 'Moisture (%)',
                    data: <?php echo json_encode($moistures); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Timestamp'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Moisture (%)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
