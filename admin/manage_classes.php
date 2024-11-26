
<?php
include '../config/db.php';

try {
    // Fetch all created classes from the database
    $classes = $db->query("SELECT * FROM created_class")->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="../assets/css/mc.css">
</head>
<body>
    <div class="container">
        <h2>Manage Created Classes</h2>
        <a href="dashboard.php" class="back-button">
            <span class="back-icon">&#8592;</span> 
        </a>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Section</th>
                        <th>Grade</th>
                        <th>Stream</th>
                        <th>Subjects</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                    <tr>
                        <td data-label="Class Name"><?= htmlspecialchars($class['class_name']) ?></td>
                        <td data-label="Section"><?= htmlspecialchars($class['section']) ?></td>
                        <td data-label="Grade"><?= htmlspecialchars($class['grade']) ?></td>
                        <td data-label="Stream"><?= htmlspecialchars($class['stream_name']) ?></td>
                        <td data-label="Subjects"><?= htmlspecialchars($class['subjects']) ?></td>
                        <td data-label="Actions">
                            <a href="edit_class.php?id=<?= $class['id'] ?>">Edit</a> | 
                            <a href="delete_class.php?id=<?= $class['id'] ?>" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

