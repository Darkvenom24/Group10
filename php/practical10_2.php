<?php
/**
 * Practical 10.2:
 * 10.2.1 Print your name.
 * 10.2.2 Print the size of a string. Pass string as an argument.
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$name_input = isset($_POST['name']) ? $_POST['name'] : "Vaibhav Senjaliya";
$str_input = isset($_POST['testString']) ? $_POST['testString'] : "Government MCA College, Maninagar";

// 10.2.1 Function to print name
function printMyName($name) {
    return "Candidate Name: " . htmlspecialchars($name);
}

// 10.2.2 Function to print the size of a string (Passed as argument)
function getStringSize($stringArgument) {
    // strlen() returns the length of string in bytes/characters
    return strlen($stringArgument);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 10.2 — String Size & Name</title>
</head>
<body>
    <h2>Practical 10.2: Print Name & String Size Argument</h2>
    <form method="post" action="">
        <label>Enter Name (10.2.1): </label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name_input); ?>" required><br><br>

        <label>Enter Test String Argument (10.2.2): </label>
        <input type="text" name="testString" value="<?php echo htmlspecialchars($str_input); ?>" size="40" required><br><br>

        <input type="submit" value="Run String Functions">
    </form>

    <hr>

    <h3>10.2.1 Print Name:</h3>
    <p><strong><?php echo printMyName($name_input); ?></strong></p>

    <h3>10.2.2 Print Size of String (Passed as Argument):</h3>
    <p>Passed Argument: "<em><?php echo htmlspecialchars($str_input); ?></em>"</p>
    <p>String Size (Length): <strong><?php echo getStringSize($str_input); ?></strong> characters</p>
</body>
</html>
