<?php
/**
 * Practical 9.3: Make 3 different type of pyramid patterns where number of row is entered by user
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$rows = 5;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['rows']) || trim($_POST['rows']) === '') {
        $error = "Validation Error: Please enter the number of rows.";
    } else {
        $rows = (int)$_POST['rows'];
        if ($rows <= 0) {
            $error = "Runtime Error (ValueError): Number of rows must be a positive integer greater than or equal to 1. Received invalid value ($rows). Negative row dimensions cannot form geometric pyramid patterns, and str_repeat() requires a non-negative multiplier.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9.3 — 3 Types of Pyramid Patterns</title>
    <style>
        body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background: #F8FAFC; color: #1E293B; }
        pre { font-family: 'Courier New', monospace; font-size: 16px; background: #0F172A; color: #38BDF8; padding: 15px; border-radius: 6px; overflow-x: auto; }
        .error-banner { background: #FEF2F2; border: 1.5px solid #F87171; color: #991B1B; padding: 12px 16px; border-radius: 6px; margin: 15px 0; font-size: 14px; }
        input[type="number"] { padding: 8px 12px; font-size: 14px; border: 1.5px solid #CBD5E1; border-radius: 4px; }
        input[type="submit"] { padding: 8px 16px; font-size: 14px; background: #000080; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Practical 9.3: 3 Types of Pyramid Patterns</h2>
    <form method="post" action="">
        <label>Enter Number of Rows (1 to 20): </label>
        <input type="number" name="rows" min="1" max="20" value="<?php echo htmlspecialchars($rows); ?>" required>
        <input type="submit" value="Draw Pyramids">
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-banner">
            <strong>❌ PHP Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($error) && $rows > 0): ?>
        <h3>Pattern 1: Right-Angled Half Pyramid</h3>
        <pre><?php
        for ($i = 1; $i <= $rows; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo "* ";
            }
            echo "\n";
        }
        ?></pre>

        <h3>Pattern 2: Centered Equilateral Pyramid</h3>
        <pre><?php
        for ($i = 1; $i <= $rows; $i++) {
            // Leading spaces
            echo str_repeat("  ", $rows - $i);
            // Stars
            for ($k = 1; $k <= (2 * $i - 1); $k++) {
                echo "* ";
            }
            echo "\n";
        }
        ?></pre>

        <h3>Pattern 3: Inverted Half Pyramid</h3>
        <pre><?php
        for ($i = $rows; $i >= 1; $i--) {
            for ($j = 1; $j <= $i; $j++) {
                echo "* ";
            }
            echo "\n";
        }
        ?></pre>
    <?php endif; ?>
</body>
</html>
