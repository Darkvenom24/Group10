<?php
/**
 * Practical 12: Connect a PHP script to a MySQL database and perform CRUD operations
 * Group 10 — Government MCA College, Maninagar (GMCA)
 * Web Technology Practicals (WTP) - Course MC01094051
 */

require_once 'config.php';

// Acquire Database Connection via PDO
$pdo = get_pdo_connection();
$message = "";

// 1. CREATE: Insert new Student Record (From Practical 7 Form)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $enrollment   = trim($_POST['enrollment'] ?? '');
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $department   = trim($_POST['department'] ?? '');
    $semester     = trim($_POST['semester'] ?? '');
    $gender       = trim($_POST['gender'] ?? 'Not Specified');
    $dob          = trim($_POST['dob'] ?? null);
    $skills       = isset($_POST['skills']) ? json_encode($_POST['skills']) : '[]';
    $project_title= trim($_POST['project_title'] ?? '');
    $project_desc = trim($_POST['project_desc'] ?? '');
    $address      = trim($_POST['address'] ?? '');

    $sql = "INSERT INTO practical7_students 
            (enrollment, full_name, email, phone, department, semester, gender, dob, skills, project_title, project_desc, address) 
            VALUES (:enrollment, :full_name, :email, :phone, :department, :semester, :gender, :dob, :skills, :project_title, :project_desc, :address)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':enrollment'    => $enrollment,
        ':full_name'     => $full_name,
        ':email'         => $email,
        ':phone'         => $phone,
        ':department'    => $department,
        ':semester'      => $semester,
        ':gender'        => $gender,
        ':dob'           => $dob ?: null,
        ':skills'        => $skills,
        ':project_title' => $project_title,
        ':project_desc'  => $project_desc,
        ':address'       => $address
    ]);
    $message = "Record successfully inserted into MySQL database! (ID: " . $pdo->lastInsertId() . ")";
}

// 2. UPDATE: Edit existing Student Record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id          = (int)$_POST['id'];
    $full_name   = trim($_POST['full_name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $department  = trim($_POST['department'] ?? '');
    $semester    = trim($_POST['semester'] ?? '');
    $project_title = trim($_POST['project_title'] ?? '');

    $sql = "UPDATE practical7_students 
            SET full_name = :full_name, email = :email, department = :department, semester = :semester, project_title = :project_title 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name'     => $full_name,
        ':email'         => $email,
        ':department'    => $department,
        ':semester'      => $semester,
        ':project_title' => $project_title,
        ':id'            => $id
    ]);
    $message = "Record ID #$id successfully updated in MySQL database!";
}

// 3. DELETE: Remove Student Record
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM practical7_students WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $message = "Record ID #$id successfully removed from database.";
}

// 4. READ: Retrieve All Student Records for Display Page
$stmt = $pdo->query("SELECT * FROM practical7_students ORDER BY id DESC");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 12 — MySQL Database CRUD Operations</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #000080; color: white; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Practical 12: Database Connection with PHP (MySQL CRUD Operations)</h2>

    <?php if ($message): ?>
        <p class="msg"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h3>Submitted Practical 7 Records (MySQL Table: `practical7_students`):</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Enrollment</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Project Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="8">No student records found in MySQL database.</td></tr>
            <?php else: ?>
                <?php foreach ($students as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['enrollment']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['department']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td><?php echo htmlspecialchars($row['project_title']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?');" style="color:red;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
