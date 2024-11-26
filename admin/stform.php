<?php
include '../config/db.php'; // Ensure the correct path to the config file

try {
    // Fetch streams, classes, sections, and subjects from the database
    $streams = $db->query("SELECT * FROM streams")->fetchAll();
    $classes = $db->query("SELECT DISTINCT class_name FROM classes")->fetchAll();
    $sections = $db->query("SELECT DISTINCT section FROM classes")->fetchAll();
    $subjects = $db->query("SELECT id, name, stream_id FROM subjects")->fetchAll();
    $query = "
    SELECT students.*, streams.name 
    FROM students 
    JOIN streams ON students.stream= streams.name 
    ORDER BY student_name ASC
";
$students = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Student Details</title>
    <link rel="stylesheet" href="../assets/css/sf.css">
</head>
<body>

<div class="form-page">
    <!-- Back Button -->
    <a href="dashboard.php" class="back-button">&#8592; Back</a>

    <!-- Centered Heading -->
    <h2 class="form-heading">Enter Student Details</h2>

    <!-- Form Container -->
    <div class="form-container">
        <form method="POST" action="save_student.php">
            <div class="form-group">
                <label for="joining_date">Joining Date:</label>
                <input type="date" id="joining_date" name="joining_date" required>

                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>

                <label for="father_name">Father's Name:</label>
                <input type="text" id="father_name" name="father_name" required>

                <label for="father_occupation">Father's Occupation:</label>
                <input type="text" id="father_occupation" name="father_occupation">

                <label for="mother_name">Mother's Name:</label>
                <input type="text" id="mother_name" name="mother_name" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>

                <label for="mobile_number">Mobile Number:</label>
                <input type="text" id="mobile_number" name="mobile_number" required>

                <label for="class_name">Class:</label>
                <select name="class_name" id="class_name" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['class_name'] ?>"><?= $class['class_name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="section">Section:</label>
                <select name="section" id="section" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= $section['section'] ?>"><?= $section['section'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="stream_name">Select Stream:</label>
                <select id="stream_name" name="stream_name" required>
                    <option value="">Select Stream</option>
                    <?php foreach ($streams as $stream): ?>
                        <option value="<?= $stream['name'] ?>"><?= $stream['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Register Student</button>
        </form>
    </div>
</div>

</body>
</html>

