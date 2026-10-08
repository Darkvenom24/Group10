<?php
/**
 * Practical 11.2: Display current date and time and Good Morning / Afternoon / Evening message
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

date_default_timezone_set('Asia/Kolkata');

$currentDateTime = date("Y-m-d H:i:s");
$formattedDate = date("l, d F Y");
$formattedTime = date("h:i:s A");
$hour = (int)date("H"); // 24-hour format (00 to 23)

// Optional simulation override for testing different times
if (isset($_GET['testHour'])) {
    $hour = (int)$_GET['testHour'];
}

// Decision Making using if-elseif-else
$greeting = "";
$badgeClass = "";

if ($hour >= 5 && $hour < 12) {
    $greeting = "Good Morning! 🌅";
    $badgeClass = "morning";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Good Afternoon! ☀️";
    $badgeClass = "afternoon";
} elseif ($hour >= 17 && $hour < 21) {
    $greeting = "Good Evening! 🌇";
    $badgeClass = "evening";
} else {
    $greeting = "Good Night! 🌙";
    $badgeClass = "night";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11.2 — Date, Time & Greeting</title>
</head>
<body>
    <h2>Practical 11.2: Current Date, Time & Dynamic Time Greeting</h2>

    <p><strong>Current Date:</strong> <?php echo $formattedDate; ?></p>
    <p><strong>Current Time:</strong> <?php echo $formattedTime; ?> (24-Hour: <?php echo $hour; ?>:00)</p>
    <p><strong>Server Timestamp:</strong> <?php echo $currentDateTime; ?></p>

    <hr>
    <h2>Greeting Message: <span style="color: #000080;"><?php echo $greeting; ?></span></h2>

    <h3>Test Other Times:</h3>
    <a href="?testHour=9">Simulate 09:00 AM (Morning)</a> | 
    <a href="?testHour=14">Simulate 02:00 PM (Afternoon)</a> | 
    <a href="?testHour=19">Simulate 07:00 PM (Evening)</a> | 
    <a href="?testHour=23">Simulate 11:00 PM (Night)</a>
</body>
</html>
