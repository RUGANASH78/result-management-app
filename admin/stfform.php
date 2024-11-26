<?php
include '../config/db.php'; // Database connection

try {
    // Fetch class and section data
    $classes = $db->query("SELECT DISTINCT class_name FROM classes")->fetchAll(PDO::FETCH_ASSOC);
    $sections = $db->query("SELECT DISTINCT section FROM classes")->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch subject data
    $subjects = $db->query("SELECT name FROM subjects")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link rel="stylesheet" href="../assets/css/stf.css"> <!-- External CSS -->
</head>
<body>
    <!-- Back Arrow -->
    <a href="dashboard.php" class="back-button">
            <span class="back-icon">&#8592;</span> 
        </a>

    <div class="form-container">
        <h2>Staff Registration Form</h2>
        <form method="POST" action="save_staff.php">
            <div class="form-group">
                <label for="staff_name">Staff Name:</label>
                <input type="text" id="staff_name" name="staff_name" required>
            </div>

            <div class="form-group">
                <label for="joining_date">Joining Date:</label>
                <input type="date" id="joining_date" name="joining_date" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>

            <div class="form-group">
                <label for="degree">Degree:</label>
                <input type="text" id="degree" name="degree" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="tel" id="mobile_number" name="mobile_number" required>
            </div>

            <div class="form-group">
                <label for="subject_taken">Subject Taken:</label>
                <select id="subject_taken" name="subject_taken" required>
                    <option value="" disabled selected>Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= htmlspecialchars($subject['name']) ?>">
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="class_sec_taken">Class Taken:</label>
                <select id="class_sec_taken" name="class_sec_taken" required>
                    <option value="" disabled selected>Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= htmlspecialchars($class['class_name']) ?>">
                            <?= htmlspecialchars($class['class_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="sec">Section Taken:</label>
                <select id="sec" name="sec" required>
                    <option value="" disabled selected>Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= htmlspecialchars($section['section']) ?>">
                            <?= htmlspecialchars($section['section']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Register Staff</button>
        </form>
    </div>
</body>
</html>
