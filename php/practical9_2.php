<?php
/**
 * Practical 9.2: Print 1 to N numbers, where N is entered by user
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$n = "";
$output = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['n']) || trim($_POST['n']) === '') {
        $error = "Validation Error: Please enter a value for N.";
    } else {
        $n = (int)$_POST['n'];
        if ($n <= 0) {
            $error = "Runtime Error (ValueError): Parameter N must be a positive integer greater than 0. Received invalid value ($n). A loop sequence from 1 to N cannot iterate backwards for positive number generation.";
        } else {
            for ($i = 1; $i <= $n; $i++) {
                $output[] = $i;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 9.2 — Print 1 to N Numbers</title>
    <style>
        body { font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background: #F8FAFC; color: #1E293B; }
        .error-banner { background: #FEF2F2; border: 1.5px solid #F87171; color: #991B1B; padding: 12px 16px; border-radius: 6px; margin: 15px 0; font-size: 14px; }
        .success-box { background: #F0FDF4; border: 1.5px solid #86EFAC; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 15px 0; }
        input[type="number"] { padding: 8px 12px; font-size: 14px; border: 1.5px solid #CBD5E1; border-radius: 4px; }
        input[type="submit"] { padding: 8px 16px; font-size: 14px; background: #000080; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Practical 9.2: Print 1 to N Numbers</h2>
    <form method="post" action="">
        <label>Enter Value of N (Positive Integer): </label>
        <input type="number" name="n" min="1" max="1000" value="<?php echo htmlspecialchars($n); ?>" required>
        <input type="submit" value="Generate Numbers">
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-banner">
            <strong>❌ PHP Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($output)): ?>
        <div class="success-box">
            <h3>Numbers from 1 to <?php echo $n; ?>:</h3>
            <p><?php echo implode(", ", $output); ?></p>
            <p><strong>Total count:</strong> <?php echo count($output); ?> numbers</p>
        </div>
    <?php endif; ?>
</body>
</html>
