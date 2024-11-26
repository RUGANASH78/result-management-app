<?php
// Include database connection and start the session
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['username'];

// Fetch student's details to determine class and stream
$sql = "SELECT student_name, class_name, section, stream FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    echo "Student details not found.";
    exit;
}

$subjects = [];
$marks_table = '';

// Determine the marks table and subjects based on class and stream
if ($student['class_name'] >= 6 && $student['class_name'] <= 10) {
    $marks_table = 'marks_class6_10';
    $stream_id = 6; // Assuming 'SSLC' is for classes 6-10 in the `streams` table
} elseif ($student['class_name'] == 11 || $student['class_name'] == 12) {
    switch ($student['stream']) {
        case 'Science':
            $marks_table = 'marks_class11_12_science';
            $stream_id = 1;
            break;
        case 'Commerce':
            $marks_table = 'marks_class11_12_commerce';
            $stream_id = 2;
            break;
        case 'Humanities':
            $marks_table = 'marks_class11_12_humanities';
            $stream_id = 3;
            break;
        case 'Biotechnology':
            $marks_table = 'marks_class11_12_biotech';
            $stream_id = 4;
            break;
        case 'Information Technology':
            $marks_table = 'marks_class11_12_it';
            $stream_id = 5;
            break;
        default:
            echo "Stream not found.";
            exit;
    }
} else {
    echo "Class not supported.";
    exit;
}

// Fetch subjects for the student's stream
$sql = "SELECT name FROM subjects WHERE stream_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stream_id);
$stmt->execute();
$subject_result = $stmt->get_result();
while ($subject = $subject_result->fetch_assoc()) {
    $subjects[] = $subject['name'];
}
$stmt->close();

// Fetch student's marks for these subjects
$sql = "SELECT " . implode(", ", array_map(function($subject) { return "`$subject`"; }, $subjects)) . ", feedback FROM $marks_table WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$marks_result = $stmt->get_result();
$marks = $marks_result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Result</title>
    <link rel="stylesheet" href="../assets/css/res.css">
</head>
<body>
    <div class="container">
    <div class="result-page">
        <h2>Result for <?php echo htmlspecialchars($student['student_name']); ?></h2>
        <p><strong>Class & Section:</strong> <?php echo htmlspecialchars($student['class_name']) . " " . htmlspecialchars($student['section']); ?></p>
        <p><strong>Stream:</strong> <?php echo htmlspecialchars($student['stream']); ?></p>

        <table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>
            <?php if ($marks): ?>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subject); ?></td>
                        <td><?php echo htmlspecialchars($marks[$subject] ?? 'Pending'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Feedback</strong></td>
                    <td><?php echo htmlspecialchars($marks['feedback'] ?? 'No feedback'); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="2">No marks available for this student.</td>
                </tr>
            <?php endif; ?>
        </table>

        <a href="student_home.php" class="btn">Back to Home</a>
    </div>
    </div>
</body>
</html>
