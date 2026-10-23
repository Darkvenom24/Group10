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

$error = "";

// Optional simulation override for testing different times
if (isset($_GET['testHour'])) {
    $rawHour = $_GET['testHour'];
    if (!is_numeric($rawHour)) {
        $error = "Validation Error: testHour parameter must be numeric.";
    } else {
        $hour = (int)$rawHour;
        if ($hour < 0 || $hour > 23) {
            $error = "Runtime Error (RangeError): In 24-hour timekeeping, hours must be between 00 and 23. Received invalid hour ($hour). Negative hours or hours > 23 are logically and astronomically invalid.";
        }
    }
}

// Decision Making using if-elseif-else
$greeting = "";
$badgeClass = "";

if (empty($error)) {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11.2 — Date, Time & Greeting</title>
    <style>
        body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background: #F8FAFC; color: #1E293B; }
        .error-banner { background: #FEF2F2; border: 1.5px solid #F87171; color: #991B1B; padding: 12px 16px; border-radius: 6px; margin: 15px 0; font-size: 14px; }
        .greeting-card { background: #FFFFFF; border: 2px solid #000080; border-radius: 8px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <h2>Practical 11.2: Current Date, Time & Dynamic Time Greeting</h2>

    <?php if (!empty($error)): ?>
        <div class="error-banner">
            <strong>❌ PHP Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <div class="greeting-card">
            <p><strong>Current Date:</strong> <?php echo $formattedDate; ?></p>
            <p><strong>Current Time:</strong> <?php echo $formattedTime; ?> (24-Hour: <?php echo $hour; ?>:00)</p>
            <p><strong>Server Timestamp:</strong> <?php echo $currentDateTime; ?></p>

            <hr>
            <h2>Greeting Message: <span style="color: #000080;"><?php echo $greeting; ?></span></h2>
        </div>
    <?php endif; ?>

    <h3>Test Other Times:</h3>
    <a href="?testHour=9">Simulate 09:00 AM (Morning)</a> | 
    <a href="?testHour=14">Simulate 02:00 PM (Afternoon)</a> | 
    <a href="?testHour=19">Simulate 07:00 PM (Evening)</a> | 
    <a href="?testHour=23">Simulate 11:00 PM (Night)</a> |
    <a href="?testHour=-5" style="color: #DC2626;">Test Negative Hour (-5)</a>
</body>
</html>
