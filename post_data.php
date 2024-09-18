<?php
// Database credentials
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP is an empty string
$dbname = "larvai_monitoring"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the necessary data is received
if (
    isset($_POST['temperature']) && 
    isset($_POST['humidity']) && 
    isset($_POST['moisture']) && 
    isset($_POST['light_intensity']) && 
    isset($_POST['nitrogen']) && 
    isset($_POST['phosphorus']) && 
    isset($_POST['potassium']) && 
    isset($_POST['health_status'])
) {
    // Sanitize and retrieve the POST data
    $temperature = $conn->real_escape_string($_POST['temperature']);
    $humidity = $conn->real_escape_string($_POST['humidity']);
    $moisture = $conn->real_escape_string($_POST['moisture']);
    $light_intensity = $conn->real_escape_string($_POST['light_intensity']);
    $nitrogen = $conn->real_escape_string($_POST['nitrogen']);
    $phosphorus = $conn->real_escape_string($_POST['phosphorus']);
    $potassium = $conn->real_escape_string($_POST['potassium']);
    $health_status = $conn->real_escape_string($_POST['health_status']);

    // Insert into bsf_health_status table
    $sql_health = "INSERT INTO bsf_health_status (health_status)
                   VALUES ('$health_status')";

    // Insert into environmental_data table
    $sql_environment = "INSERT INTO environmental_data (temperature, humidity, moisture, light_intensity, nitrogen, phosphorus, potassium)
                        VALUES ('$temperature', '$humidity', '$moisture', '$light_intensity', '$nitrogen', '$phosphorus', '$potassium')";

    // Execute the queries
    if ($conn->query($sql_health) === TRUE && $conn->query($sql_environment) === TRUE) {
        echo "Data successfully inserted into both tables";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid data received";
}

// Close the connection
$conn->close();
?>
