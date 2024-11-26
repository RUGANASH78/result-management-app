<?php
// save_marks.php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

$staff_id = $_SESSION['username'];

// Validate POST data
if (!isset($_POST['class_name'], $_POST['section'], $_POST['subject'])) {
    header("Location: update_marks.php?error=Missing required data");
    exit;
}

$class_name = $_POST['class_name'];
$section = $_POST['section'];
$subject = $_POST['subject'];
$marks = $_POST['marks'] ?? []; // Default to an empty array if not provided
$feedbacks = $_POST['feedback'] ?? []; // Default to an empty array if not provided

// Determine table based on class and stream
$table = '';
if (in_array($class_name, ['6', '7', '8', '9', '10'])) {
    $table = 'marks_class6_10';
} elseif (in_array($class_name, ['11', '12'])) {
    $stream = $_POST['stream'] ?? 'general';
    $table = "marks_class11_12_" . strtolower($stream);
} else {
    header("Location: update_marks.php?error=Invalid class name");
    exit;
}

// Insert or update marks for students
if (!empty($marks)) {
    foreach ($marks as $student_id => $subjects) {
        $feedback = $feedbacks[$student_id] ?? ''; // Handle missing feedback gracefully

        // Check if the current subject is present for the student
        if (isset($subjects[$subject])) {
            $student_mark = $subjects[$subject];

            // Insert or update the marks and feedback
            $stmt = $db->prepare("INSERT INTO $table (student_id, $subject, feedback, updated_by)
                                  VALUES (:student_id, :marks, :feedback, :updated_by)
                                  ON DUPLICATE KEY UPDATE $subject = :marks, feedback = :feedback, updated_by = :updated_by");

            $stmt->execute([
                ':student_id' => $student_id,
                ':marks' => $student_mark,
                ':feedback' => $feedback,
                ':updated_by' => $staff_id
            ]);
        }
    }

    // Redirect to staff_home.php after successful update
    header("Location: staff_home.php?success=Marks updated successfully");
    exit;
} else {
    // Redirect back to update_marks.php with an error if no marks are provided
    header("Location: update_marks.php?error=No data provided for marks");
    exit;
}
?>
