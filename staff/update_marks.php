<?php
// update_marks.php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$staff_id = $_SESSION['username'];
$class_name = $_POST['class_name'];
$section = $_POST['section'];
$stream_id = $_POST['stream_id'];

// Fetch staff details
$stmt = $db->prepare("SELECT staff_name, subject_taken FROM staff WHERE login_id = ?");
$stmt->execute([$staff_id]);
$staff_info = $stmt->fetch(PDO::FETCH_ASSOC);

$staff_name = $staff_info['staff_name'];
$subject_taken = $staff_info['subject_taken'];

// Fetch students for the selected class and section
$stmt = $db->prepare("SELECT * FROM students WHERE class_name = ? AND section = ?");
$stmt->execute([$class_name, $section]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subjects for the selected stream
$stmt = $db->prepare("SELECT id, name FROM subjects WHERE stream_id = ?");
$stmt->execute([$stream_id]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Marks</title>
    <link rel="stylesheet" href="../assets/css/stffupmf.css">
    <script>
        // Function to display alert messages
        function showAlert(message, type) {
            alert(`${type.toUpperCase()}: ${message}`);
        }
    </script>
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <img src="../assets/images/profile.jpg" alt="Profile Picture">
        </div>
        <div class="navbar-right">
            <div class="dropdown">
                <button class="dropbtn">Menu</button>
                <div class="dropdown-content">
                    <a href="staff_home.php">Home</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Show alerts based on GET parameters
    if (isset($_GET['success'])) {
        echo "<script>showAlert('Marks updated successfully.', 'success');</script>";
    } elseif (isset($_GET['error'])) {
        echo "<script>showAlert('Error: " . htmlspecialchars($_GET['error']) . "', 'error');</script>";
    }
    ?>

    <div class="form-container">
        <h2>Mark Entry for Class <?= htmlspecialchars($class_name) ?> - Section <?= htmlspecialchars($section) ?></h2>
        <p>Staff: <?= htmlspecialchars($staff_name) ?> | Subject: <?= htmlspecialchars($subject_taken) ?></p>

        <form method="POST" action="save_marks.php">
            <input type="hidden" name="class_name" value="<?= htmlspecialchars($class_name) ?>">
            <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
            <input type="hidden" name="subject" value="<?= htmlspecialchars($subject_taken) ?>">

            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <?php foreach ($subjects as $subject): ?>
                            <th><?= htmlspecialchars($subject['name']) ?></th>
                        <?php endforeach; ?>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                            <td><?= htmlspecialchars($student['student_name']) ?></td>
                            <?php foreach ($subjects as $subject): ?>
                                <td>
                                    <?php if ($subject['name'] === $subject_taken): ?>
                                        <input type="number" name="marks[<?= $student['id'] ?>][<?= $subject['name'] ?>]" min="0" max="100">
                                    <?php else: ?>
                                        <span>Not Editable</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <textarea name="feedback[<?= $student['id'] ?>]" rows="2" cols="20" placeholder="Enter feedback"></textarea>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Submit Marks</button>
        </form>
    </div>
</body>
</html>
