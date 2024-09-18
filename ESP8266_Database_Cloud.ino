#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>

// Replace with your network credentials
const char* ssid = "CANALBOX-A6EE-2G";
const char* password = "BzxaYzRCx7";

// Server URL
const char* serverName = "http://192.168.1.80/larvai-monitoring/post_data.php"; // Replace with your local IP

WiFiClient client; // Create a WiFiClient object

void setup() {
  Serial.begin(115200);

  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    // Generate random data within the specified ranges
    float temperature = random(22, 38); // Example range for temperature
    float humidity = random(40, 91);    // Example range for humidity
    float moisture = random(40, 81);    // Example range for moisture
    float light_intensity = random(100, 1000); // Replace with your desired range
    float nitrogen = random(0, 100); // Replace with your desired range
    float phosphorus = random(0, 100); // Replace with your desired range
    float potassium = random(0, 100); // Replace with your desired range

    String health_status = random(0, 2) == 0 ? "healthy" : "unhealthy";

    // Create POST data
    String postData = "temperature=" + String(temperature) +
                      "&humidity=" + String(humidity) +
                      "&moisture=" + String(moisture) +
                      "&light_intensity=" + String(light_intensity) +
                      "&nitrogen=" + String(nitrogen) +
                      "&phosphorus=" + String(phosphorus) +
                      "&potassium=" + String(potassium) +
                      "&health_status=" + health_status;

    // Start connection and send HTTP POST request
    http.begin(client, serverName); // Updated to use WiFiClient with the server URL
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.print("Error on sending POST: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  }

  // Wait for 10 seconds before sending next data
  delay(10000);
}
