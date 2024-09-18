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

// Fetch the latest data from the "environmental_data" table
$sql = "SELECT temperature, humidity, moisture, light_intensity, nitrogen, phosphorus, potassium FROM environmental_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

// Initialize variables for displaying
$temperature = $humidity = $moisture = $lightIntensity = $nitrogen = $phosphorus = $potassium = "N/A";
$recommendations = "No data available to provide recommendations.";
$bsfHealthStatus = "No data available";

// Fetch data if available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $temperature = $row['temperature'];
    $humidity = $row['humidity'];
    $moisture = $row['moisture'];
    $lightIntensity = $row['light_intensity'];
    $nitrogen = $row['nitrogen'];
    $phosphorus = $row['phosphorus'];
    $potassium = $row['potassium'];

    // Generate AI-driven recommendations based on the conditions
    $tempCondition = "";
    $humCondition = "";
    $moistCondition = "";

    // Temperature Conditions
    if ($temperature >= 27 && $temperature <= 32) {
        $tempCondition = "Optimal";
    } elseif (($temperature >= 22 && $temperature <= 26) || ($temperature >= 33 && $temperature <= 37)) {
        $tempCondition = "Suboptimal";
    } else {
        $tempCondition = "Critical";
    }

    // Humidity Conditions
    if ($humidity >= 60 && $humidity <= 80) {
        $humCondition = "Optimal";
    } elseif (($humidity >= 40 && $humidity <= 59) || ($humidity >= 81 && $humidity <= 90)) {
        $humCondition = "Suboptimal";
    } else {
        $humCondition = "Critical";
    }

    // Moisture Conditions
    if ($moisture >= 60 && $moisture <= 70) {
        $moistCondition = "Optimal";
    } elseif (($moisture >= 40 && $moisture <= 59) || ($moisture >= 71 && $moisture <= 80)) {
        $moistCondition = "Suboptimal";
    } else {
        $moistCondition = "Critical";
    }

    // Determine recommendations based on conditions
    if ($tempCondition === "Optimal" && $humCondition === "Optimal" && $moistCondition === "Optimal") {
        $recommendations = "All environmental conditions are optimal. No action required.";
    } else {
        $recommendations = "Recommendations based on current conditions:<br>";

        // Temperature Recommendations
        if ($tempCondition === "Suboptimal") {
            $recommendations .= "- Temperature is suboptimal: <strong>Consider adjusting ventilation or heating as needed. Optimal range: 27-32°C.</strong><br>";
        } elseif ($tempCondition === "Critical") {
            $recommendations .= "- Temperature is critical: <strong>Immediate action required: adjust ventilation or start a warming process. Optimal range: 27-32°C.</strong><br>";
        }

        // Humidity Recommendations
        if ($humCondition === "Suboptimal") {
            $recommendations .= "- Humidity is suboptimal: <strong>Adjust humidity controls if available. Optimal range: 60-80%.</strong><br>";
        } elseif ($humCondition === "Critical") {
            $recommendations .= "- Humidity is critical: <strong>Immediate action required: adjust humidifiers or dehumidifiers. Optimal range: 60-80%.</strong><br>";
        }

        // Moisture Recommendations
        if ($moistCondition === "Suboptimal") {
            $recommendations .= "- Moisture is suboptimal: <strong>Monitor and adjust as needed to reach optimal levels. Optimal range: 60-70%.</strong><br>";
        } elseif ($moistCondition === "Critical") {
            $recommendations .= "- Moisture is critical: <strong>Immediate action required: adjust moisture levels. Optimal range: 60-70%.</strong><br>";
        }
    }
}

// Fetch the latest BSF health status
$sql_bsf_health = "SELECT health_status FROM bsf_health_status ORDER BY timestamp DESC LIMIT 1";
$result_bsf_health = $conn->query($sql_bsf_health);

// Fetch BSF health status if available
if ($result_bsf_health->num_rows > 0) {
    $row_bsf_health = $result_bsf_health->fetch_assoc();
    $bsfHealthStatus = $row_bsf_health['health_status'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LarvAI Monitoring Platform</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <h2>Welcome to Our Platform</h2>
        <p>We provide real-time data monitoring and analysis for Black Soldier Fly (BSF) larvae rearing spaces. Utilize our intuitive tools and AI-driven recommendations to optimize BSF production and improve your rearing outcomes.</p>
        
        <!-- Current Status Section -->
        <section class="status">
            <h3>Current Status</h3>
            <div class="status-grid">
                <!-- First Line Parameters -->
                <div class="status-item">
                    <i class="fas fa-thermometer-half"></i>
                    <h4>Temperature</h4>
                    <p id="temperature"><?php echo $temperature; ?> °C</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-tint"></i>
                    <h4>Humidity</h4>
                    <p id="humidity"><?php echo $humidity; ?> %</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-water"></i>
                    <h4>Moisture</h4>
                    <p id="moisture"><?php echo $moisture; ?> %</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Light Intensity</h4>
                    <p id="light-intensity"><?php echo $lightIntensity; ?> lux</p>
                </div>
                <!-- Second Line Parameters -->
                <div class="status-item">
                    <i class="fas fa-leaf"></i>
                    <h4>Nitrogen</h4>
                    <p id="nitrogen"><?php echo $nitrogen; ?> ppm</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-flask"></i>
                    <h4>Phosphorus</h4>
                    <p id="phosphorus"><?php echo $phosphorus; ?> ppm</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-gem"></i>
                    <h4>Potassium</h4>
                    <p id="potassium"><?php echo $potassium; ?> ppm</p>
                </div>
                <div class="status-item">
                    <i class="fas fa-heartbeat"></i>
                    <h4>BSF Health Status</h4>
                    <p id="bsf-health"><?php echo $bsfHealthStatus; ?></p>
                </div>
            </div>
        </section>

        <!-- AI-Driven Recommendations Section -->
        <section class="recommendations">
            <h3>AI-Driven Recommendations</h3>
            <p id="recommendations"><?php echo $recommendations; ?></p>
        </section>
        
        <!-- Educational Resources Section -->
        <section class="resources">
            <h3>Educational Resources</h3>
            <ul>
                <li><a href="bsf-rearing-guide.php" target="_blank">BSF Rearing Guide</a></li>
                <li><a href="organic-fertilizer-production.pdf" target="_blank">Organic Fertilizer Production</a></li>
                <li><a href="sustainable-practices.pdf" target="_blank">Sustainable Practices in Agriculture</a></li>
            </ul>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 LarvAI Monitoring Platform. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
