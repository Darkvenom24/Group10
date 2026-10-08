<?php
/**
 * Practical 9.2: Print 1 to N numbers, where N is entered by user
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$n = "";
$output = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n = isset($_POST['n']) ? (int)$_POST['n'] : 0;
    if ($n > 0) {
        for ($i = 1; $i <= $n; $i++) {
            $output[] = $i;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9.2 — Print 1 to N Numbers</title>
</head>
<body>
    <h2>Practical 9.2: Print 1 to N Numbers</h2>
    <form method="post" action="">
        <label>Enter Value of N (Positive Integer): </label>
        <input type="number" name="n" min="1" max="1000" value="<?php echo htmlspecialchars($n); ?>" required>
        <input type="submit" value="Generate Numbers">
    </form>

    <?php if (!empty($output)): ?>
        <h3>Numbers from 1 to <?php echo $n; ?>:</h3>
        <p><?php echo implode(", ", $output); ?></p>
        <p><strong>Total count:</strong> <?php echo count($output); ?> numbers</p>
    <?php endif; ?>
</body>
</html>
