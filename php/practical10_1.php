<?php
/**
 * Practical 10.1: PHP Array Operations
 * 10.1.1 Print the values of array.
 * 10.1.2 Reverse an array.
 * 10.1.3 Merge two arrays in sorted manner.
 * 10.1.4 Add values of all elements of an array.
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$arr1_input = isset($_POST['arr1']) ? $_POST['arr1'] : "14, 8, 35, 2, 19";
$arr2_input = isset($_POST['arr2']) ? $_POST['arr2'] : "5, 42, 11, 23";

// Helper to convert comma separated string into numeric array
function parseArray($str) {
    $items = explode(",", $str);
    $arr = [];
    foreach ($items as $item) {
        $trimmed = trim($item);
        if ($trimmed !== "") {
            $arr[] = is_numeric($trimmed) ? (float)$trimmed : $trimmed;
        }
    }
    return $arr;
}

$arr1 = parseArray($arr1_input);
$arr2 = parseArray($arr2_input);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 10.1 — PHP Array Operations</title>
</head>
<body>
    <h2>Practical 10.1: Operations on Arrays</h2>
    <form method="post" action="">
        <label>Enter Array 1 (comma-separated): </label><br>
        <input type="text" name="arr1" value="<?php echo htmlspecialchars($arr1_input); ?>" style="width:300px;"><br><br>

        <label>Enter Array 2 for Merge (comma-separated): </label><br>
        <input type="text" name="arr2" value="<?php echo htmlspecialchars($arr2_input); ?>" style="width:300px;"><br><br>

        <input type="submit" value="Run Array Operations">
    </form>

    <hr>

    <!-- 10.1.1 Print the values of array -->
    <h3>10.1.1 Print Values of Array:</h3>
    <p><strong>Loop Traversal:</strong>
    <?php
    foreach ($arr1 as $index => $value) {
        echo "Index [$index] => $value; ";
    }
    ?>
    </p>
    <pre>print_r output: <?php print_r($arr1); ?></pre>

    <!-- 10.1.2 Reverse an array -->
    <h3>10.1.2 Reverse Array:</h3>
    <?php
    $reversed = array_reverse($arr1);
    ?>
    <pre><?php print_r($reversed); ?></pre>

    <!-- 10.1.3 Merge two arrays in sorted manner -->
    <h3>10.1.3 Merge Two Arrays in Sorted Manner:</h3>
    <?php
    $merged = array_merge($arr1, $arr2);
    sort($merged); // Sort in ascending order
    ?>
    <pre><?php print_r($merged); ?></pre>

    <!-- 10.1.4 Add values of all elements of an array -->
    <h3>10.1.4 Sum of Array Elements:</h3>
    <?php
    $sum = array_sum($arr1);
    ?>
    <p>Total Sum of elements in Array 1 = <strong><?php echo $sum; ?></strong></p>
</body>
</html>
