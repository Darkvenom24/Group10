<?php
/**
 * Practical 11.3: User profile web page with PHP form submission display
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

$isSubmitted = false;
$userData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isSubmitted = true;
    $userData = [
        'fullName'   => htmlspecialchars(trim($_POST['fullName'] ?? '')),
        'enrollment' => htmlspecialchars(trim($_POST['enrollment'] ?? '')),
        'email'      => htmlspecialchars(trim($_POST['email'] ?? '')),
        'phone'      => htmlspecialchars(trim($_POST['phone'] ?? '')),
        'gender'     => htmlspecialchars(trim($_POST['gender'] ?? 'Not Specified')),
        'degree'     => htmlspecialchars(trim($_POST['degree'] ?? '')),
        'semester'   => htmlspecialchars(trim($_POST['semester'] ?? '')),
        'bio'        => htmlspecialchars(trim($_POST['bio'] ?? '')),
        'skills'     => isset($_POST['skills']) ? (array)$_POST['skills'] : []
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11.3 — User Profile Form Handling</title>
</head>
<body>
    <h2>Practical 11.3: User Profile & PHP Form Submission</h2>

    <?php if (!$isSubmitted): ?>
        <form method="post" action="">
            <h3>Create User Profile</h3>
            <label>Full Name: </label><br>
            <input type="text" name="fullName" required value="Vaibhav Senjaliya"><br><br>

            <label>Enrollment No: </label><br>
            <input type="text" name="enrollment" required value="26GMCA52"><br><br>

            <label>Email: </label><br>
            <input type="email" name="email" required value="vaibhav@gmca.ac.in"><br><br>

            <label>Phone: </label><br>
            <input type="tel" name="phone" required value="9876543210"><br><br>

            <label>Gender: </label>
            <input type="radio" name="gender" value="Male" checked> Male
            <input type="radio" name="gender" value="Female"> Female<br><br>

            <label>Course / Degree: </label>
            <select name="degree">
                <option value="Master of Computer Applications (MCA)">MCA</option>
                <option value="Bachelor of Computer Applications (BCA)">BCA</option>
                <option value="MSc Information Technology">MSc IT</option>
            </select><br><br>

            <label>Semester: </label>
            <select name="semester">
                <option value="Semester 1">Semester 1</option>
                <option value="Semester 2" selected>Semester 2</option>
                <option value="Semester 3">Semester 3</option>
                <option value="Semester 4">Semester 4</option>
            </select><br><br>

            <label>Technical Skills: </label><br>
            <input type="checkbox" name="skills[]" value="PHP" checked> PHP
            <input type="checkbox" name="skills[]" value="MySQL" checked> MySQL
            <input type="checkbox" name="skills[]" value="HTML5/CSS3" checked> HTML5/CSS3
            <input type="checkbox" name="skills[]" value="JavaScript" checked> JavaScript<br><br>

            <label>Profile Bio: </label><br>
            <textarea name="bio" rows="3" cols="40">MCA student at Government MCA College, Maninagar. Passionate about web development and cloud technologies.</textarea><br><br>

            <input type="submit" value="Submit Profile via PHP">
        </form>

    <?php else: ?>
        <div style="border: 2px solid #000080; padding: 20px; border-radius: 8px; max-width: 600px;">
            <h2 style="color: #000080;">Submitted Profile Details (Processed via PHP $_POST)</h2>
            <hr>
            <p><strong>Full Name:</strong> <?php echo $userData['fullName']; ?></p>
            <p><strong>Enrollment Number:</strong> <?php echo $userData['enrollment']; ?></p>
            <p><strong>Email Address:</strong> <?php echo $userData['email']; ?></p>
            <p><strong>Contact Phone:</strong> <?php echo $userData['phone']; ?></p>
            <p><strong>Gender:</strong> <?php echo $userData['gender']; ?></p>
            <p><strong>Academic Program:</strong> <?php echo $userData['degree']; ?> (<?php echo $userData['semester']; ?>)</p>
            <p><strong>Skills:</strong> <?php echo implode(", ", $userData['skills']); ?></p>
            <p><strong>About / Bio:</strong> <?php echo nl2br($userData['bio']); ?></p>
            <br>
            <a href="">← Submit Another Profile</a>
        </div>
    <?php endif; ?>
</body>
</html>
