<?php
/**
 * Practical 9.1: Find max value from 3 values entered by user
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$max = null;
$num1 = $num2 = $num3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = isset($_POST['num1']) ? (float)$_POST['num1'] : 0;
    $num2 = isset($_POST['num2']) ? (float)$_POST['num2'] : 0;
    $num3 = isset($_POST['num3']) ? (float)$_POST['num3'] : 0;

    // Logic: Conditional Comparison
    if ($num1 >= $num2 && $num1 >= $num3) {
        $max = $num1;
    } elseif ($num2 >= $num1 && $num2 >= $num3) {
        $max = $num2;
    } else {
        $max = $num3;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9.1 — Maximum of 3 Numbers</title>
</head>
<body>
    <h2>Practical 9.1: Find Maximum Value of 3 Numbers</h2>
    <form method="post" action="">
        <label>Enter First Number: </label>
        <input type="number" step="any" name="num1" value="<?php echo htmlspecialchars($num1); ?>" required><br><br>

        <label>Enter Second Number: </label>
        <input type="number" step="any" name="num2" value="<?php echo htmlspecialchars($num2); ?>" required><br><br>

        <label>Enter Third Number: </label>
        <input type="number" step="any" name="num3" value="<?php echo htmlspecialchars($num3); ?>" required><br><br>

        <input type="submit" value="Find Maximum">
    </form>

    <?php if ($max !== null): ?>
        <h3>Result:</h3>
        <p>The maximum value among <strong><?php echo "$num1, $num2, $num3"; ?></strong> is: <strong><?php echo $max; ?></strong></p>
    <?php endif; ?>
</body>
</html>
