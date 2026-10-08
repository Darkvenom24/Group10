<?php
/**
 * Practical 9.3: Make 3 different type of pyramid patterns where number of row is entered by user
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$rows = isset($_POST['rows']) ? (int)$_POST['rows'] : 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9.3 — 3 Types of Pyramid Patterns</title>
    <style>
        pre { font-family: 'Courier New', monospace; font-size: 16px; background: #222; color: #00ff66; padding: 15px; border-radius: 6px; }
    </style>
</head>
<body>
    <h2>Practical 9.3: 3 Types of Pyramid Patterns</h2>
    <form method="post" action="">
        <label>Enter Number of Rows (1 to 20): </label>
        <input type="number" name="rows" min="1" max="20" value="<?php echo htmlspecialchars($rows); ?>" required>
        <input type="submit" value="Draw Pyramids">
    </form>

    <?php if ($rows > 0): ?>
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
