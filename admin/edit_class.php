<?php
include '../config/db.php'; // Ensure the correct path to the config file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission for editing
    $classId = $_POST['class_id'];
    $className = $_POST['class_name'];
    $section = $_POST['section'];
    $grade = $_POST['grade'];
    $streamName = $_POST['stream_name'];
    $subjects = implode(',', $_POST['subjects']); // Assuming subjects are sent as an array

    try {
        $updateQuery = $db->prepare("UPDATE created_class SET class_name=?, section=?, grade=?, stream_name=?, subjects=? WHERE id=?");
        $updateQuery->execute([$className, $section, $grade, $streamName, $subjects, $classId]);
        echo "Class updated successfully.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Fetch current class details to pre-fill the form
    $classId = $_GET['id'];
    $classQuery = $db->prepare("SELECT * FROM created_class WHERE id = ?");
    $classQuery->execute([$classId]);
    $class = $classQuery->fetch();
    // Fetch subjects, streams, etc. as needed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
    <link rel="stylesheet" href="../assets/css/edstf.css">
</head>
<body>
<div class="container">
<h2>Edit Class</h2>

<form method="POST" action="">
    <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
    
    <!-- Add fields for editing class details -->
    <label for="class_name">Class Name:</label>
    <input type="text" name="class_name" value="<?= htmlspecialchars($class['class_name']) ?>" required><br><br>

    <label for="section">Section:</label>
    <input type="text" name="section" value="<?= htmlspecialchars($class['section']) ?>" required><br><br>

    <label for="grade">Grade:</label>
    <input type="number" name="grade" value="<?= htmlspecialchars($class['grade']) ?>" required><br><br>

    <label for="stream_name">Stream:</label>
    <input type="text" name="stream_name" value="<?= htmlspecialchars($class['stream_name']) ?>" required><br><br>

    <label for="subjects">Subjects:</label>
    <input type="text" name="subjects[]" value="<?= htmlspecialchars($class['subjects']) ?>" required><br><br>

    <input type="submit" value="Update Class">
</form>
</div>
</body>
</html>
