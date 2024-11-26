<?php
include '../config/db.php'; // Ensure the correct path to the config file

try {
    // Fetch streams, classes, sections, and subjects from the database
    $streams = $db->query("SELECT * FROM streams")->fetchAll();
    $classes = $db->query("SELECT DISTINCT class_name FROM classes")->fetchAll();
    $sections = $db->query("SELECT DISTINCT section FROM classes")->fetchAll();
    $subjects = $db->query("SELECT id, name, stream_id FROM subjects")->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Class</title>
    <link rel="stylesheet" href="../assets/css/cc.css">
    <style></style>

    <script>
        // Auto-select subjects when a stream is chosen
        function autoSelectSubjects() {
            const streamId = document.getElementById('stream_id').value;
            const subjectOptions = document.querySelectorAll('#subjects option');

            subjectOptions.forEach(option => {
                // If the subject belongs to the selected stream, select it, else unselect
                if (option.getAttribute('data-stream-id') === streamId) {
                    option.selected = true;
                    option.style.display = 'block';
                } else {
                    option.selected = false;
                    option.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>

<div class="head">
<h2>Create a New Class</h2>
<a class="goback" href="dashboard.php"> Go Back</a>

</div>
<form method="POST" action="save_class.php">
    <!-- Class Selection -->
    <label for="class_name">Select Class:</label>
    <select name="class_name" id="class_name" required>
        <option value="">Select Class</option>
        <?php foreach ($classes as $class): ?>
            <option value="<?= $class['class_name'] ?>"><?= $class['class_name'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Section Selection -->
    <label for="section">Select Section:</label>
    <select name="section" id="section" required>
        <option value="">Select Section</option>
        <?php foreach ($sections as $section): ?>
            <option value="<?= $section['section'] ?>"><?= $section['section'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Stream Selection -->
    <!-- Stream Selection -->
<label for="stream_id">Select Stream:</label>
<select id="stream_id" name="stream_id" onchange="autoSelectSubjects()" required>
    <option value="">Select Stream</option>
    <?php foreach ($streams as $stream): ?>
        <option value="<?= $stream['id'] ?>"><?= $stream['name'] ?></option>
    <?php endforeach; ?>
</select><br><br>



    <!-- Subject Selection -->
    <label for="subjects">Select Subjects:</label>
    <select id="subjects" name="subjects[]" multiple required>
        <?php foreach ($subjects as $subject): ?>
            <option data-stream-id="<?= $subject['stream_id'] ?>" value="<?= $subject['id'] ?>">
                <?= $subject['name'] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <input class="ok" type="submit" value="Create Class">
   
</form>

<script>
    // Auto-select subjects when a stream is chosen
    function autoSelectSubjects() {
        const streamId = document.getElementById('stream_id').value;
        const subjectOptions = document.querySelectorAll('#subjects option');

        subjectOptions.forEach(option => {
            // If the subject belongs to the selected stream, display and select it
            if (option.getAttribute('data-stream-id') === streamId) {
                option.style.display = 'block';
                option.selected = true; // Select only relevant subjects
            } else {
                option.style.display = 'none';
                option.selected = false; // Deselect irrelevant subjects
            }
        });
    }
</script>

</body>
</html>

