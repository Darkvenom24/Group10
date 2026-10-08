<?php
/**
 * Practical 11.4: PHP String Functions
 * 11.4.1 print your name.
 * 11.4.2 print the size of a string. Pass string as an argument.
 * 11.4.3 concat two strings.
 * 11.4.4 convert case of string.
 * 11.4.5 find one string from another.
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

// 11.4.1 Function to print your name
function printName($name) {
    return "Student Name: " . htmlspecialchars($name);
}

// 11.4.2 Function to print size of string (string passed as argument)
function getStringSize($str) {
    return strlen($str);
}

// 11.4.3 Function to concat two strings
function concatenateStrings($str1, $str2, $delimiter = " ") {
    return $str1 . $delimiter . $str2;
}

// 11.4.4 Function to convert case of string
function convertStringCases($str) {
    return [
        'uppercase' => strtoupper($str),
        'lowercase' => strtolower($str),
        'titlecase' => ucwords(strtolower($str))
    ];
}

// 11.4.5 Function to find one string from another (search substring in text)
function findSubstring($haystack, $needle) {
    $pos = strpos($haystack, $needle);
    if ($pos !== false) {
        return "Substring '" . htmlspecialchars($needle) . "' was FOUND at position / index: " . $pos;
    } else {
        return "Substring '" . htmlspecialchars($needle) . "' was NOT FOUND in the main text.";
    }
}

// Inputs
$name = isset($_POST['name']) ? $_POST['name'] : "Vaibhav Senjaliya";
$sizeArg = isset($_POST['sizeArg']) ? $_POST['sizeArg'] : "Government MCA College";
$concat1 = isset($_POST['concat1']) ? $_POST['concat1'] : "Web Technology";
$concat2 = isset($_POST['concat2']) ? $_POST['concat2'] : "Practical Portal";
$caseStr = isset($_POST['caseStr']) ? $_POST['caseStr'] : "Master of Computer Applications";
$haystack = isset($_POST['haystack']) ? $_POST['haystack'] : "Gujarat Technological University Ahmedabad";
$needle = isset($_POST['needle']) ? $_POST['needle'] : "University";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11.4 — PHP String Operations</title>
</head>
<body>
    <h2>Practical 11.4: PHP String Functions</h2>

    <form method="post" action="">
        <p><strong>11.4.1 Name:</strong> <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"></p>
        <p><strong>11.4.2 String Size Argument:</strong> <input type="text" name="sizeArg" value="<?php echo htmlspecialchars($sizeArg); ?>" size="35"></p>
        <p><strong>11.4.3 Concat String 1 & 2:</strong> 
            <input type="text" name="concat1" value="<?php echo htmlspecialchars($concat1); ?>"> + 
            <input type="text" name="concat2" value="<?php echo htmlspecialchars($concat2); ?>">
        </p>
        <p><strong>11.4.4 Convert Case String:</strong> <input type="text" name="caseStr" value="<?php echo htmlspecialchars($caseStr); ?>" size="35"></p>
        <p><strong>11.4.5 Find Substring:</strong> 
            Main Text: <input type="text" name="haystack" value="<?php echo htmlspecialchars($haystack); ?>" size="40">
            Search For: <input type="text" name="needle" value="<?php echo htmlspecialchars($needle); ?>">
        </p>
        <input type="submit" value="Execute String Functions">
    </form>

    <hr>
    <h3>Results:</h3>
    <p><strong>11.4.1 Name:</strong> <?php echo printName($name); ?></p>
    <p><strong>11.4.2 Size of Argument:</strong> String "<em><?php echo htmlspecialchars($sizeArg); ?></em>" has length: <strong><?php echo getStringSize($sizeArg); ?></strong></p>
    <p><strong>11.4.3 Concatenated String:</strong> "<strong><?php echo htmlspecialchars(concatenateStrings($concat1, $concat2)); ?></strong>"</p>
    <p><strong>11.4.4 Case Conversions:</strong></p>
    <ul>
        <?php $cases = convertStringCases($caseStr); ?>
        <li>UPPERCASE: <code><?php echo htmlspecialchars($cases['uppercase']); ?></code></li>
        <li>lowercase: <code><?php echo htmlspecialchars($cases['lowercase']); ?></code></li>
        <li>Title Case: <code><?php echo htmlspecialchars($cases['titlecase']); ?></code></li>
    </ul>
    <p><strong>11.4.5 Substring Search:</strong> <?php echo findSubstring($haystack, $needle); ?></p>
</body>
</html>
