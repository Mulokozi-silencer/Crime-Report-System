<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crime_reporting";

// Create a persistent connection
$conn = new mysqli("p:$servername", $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$crime_type = filter_input(INPUT_POST, 'crime_type', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);

$message = "";

if ($name && $email && $crime_type && $description && $location) {
    $stmt = $conn->prepare("INSERT INTO reports (name, email, crime_type, description, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $crime_type, $description, $location);

    if ($stmt->execute()) {
        $message = "Dear <b>$name,</b><br><br>
        We understand that experiencing a $crime_type can be a deeply unsettling and stressful event. We commend your courage in taking the vital step of reporting the incident to the authorities.<br><br>
        Please take comfort in knowing that your report has been received, and the police are actively working to respond as quickly as possible. <b>Help is on the way.</b><br><br>
        During this challenging time, it's important to <b>remain calm</b> and seek a <b>safe place</b> if you haven't already done so. Remember, the authorities are equipped to handle the situation and will arrive shortly to assist you.<br><br>
        Your safety is our top priority. If you need immediate assistance or further support, don't hesitate to reach out to emergency services or a trusted friend or family member.<br><br>
        Thank you for your bravery and cooperation. Together, we can ensure a safer community for everyone.<br><br>
        <b>STAY STRONG</b>, <b>HELP IS ON THE WAY.</b><br><br>
        Sincerely,<br>
        Your Police Department in <b>$location</b>.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $message = "Please fill all the fields correctly.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Status</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: url('image/2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }
        .message-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <?php echo $message; ?>
    </div>
</body>
</html>
