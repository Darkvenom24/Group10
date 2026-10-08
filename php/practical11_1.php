<?php
/**
 * Practical 11.1: Find out maximum and minimum number using if-else / decision making
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$numA = isset($_POST['numA']) ? (float)$_POST['numA'] : 45;
$numB = isset($_POST['numB']) ? (float)$_POST['numB'] : 18;
$numC = isset($_POST['numC']) ? (float)$_POST['numC'] : 92;

$max = $numA;
$min = $numA;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decision Making using if-else structures
    // Maximum
    if ($numA >= $numB && $numA >= $numC) {
        $max = $numA;
    } elseif ($numB >= $numA && $numB >= $numC) {
        $max = $numB;
    } else {
        $max = $numC;
    }

    // Minimum
    if ($numA <= $numB && $numA <= $numC) {
        $min = $numA;
    } elseif ($numB <= $numA && $numB <= $numC) {
        $min = $numB;
    } else {
        $min = $numC;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11.1 — Maximum & Minimum Numbers</title>
</head>
<body>
    <h2>Practical 11.1: Find Maximum and Minimum Number</h2>
    <form method="post" action="">
        <label>Number 1: </label>
        <input type="number" step="any" name="numA" value="<?php echo $numA; ?>" required><br><br>

        <label>Number 2: </label>
        <input type="number" step="any" name="numB" value="<?php echo $numB; ?>" required><br><br>

        <label>Number 3: </label>
        <input type="number" step="any" name="numC" value="<?php echo $numC; ?>" required><br><br>

        <input type="submit" value="Find Max & Min">
    </form>

    <hr>
    <h3>Decision Making Results:</h3>
    <p>Given Numbers: <strong><?php echo "$numA, $numB, $numC"; ?></strong></p>
    <p>Maximum Number: <strong style="color: green;"><?php echo $max; ?></strong></p>
    <p>Minimum Number: <strong style="color: red;"><?php echo $min; ?></strong></p>
</body>
</html>
