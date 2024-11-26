<?php
include '../config/db.php'; // Ensure correct path to DB config

// Initialize $searchQuery to avoid "undefined variable" warning
$searchQuery = "";

// Delete student
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$delete_id]);
    header('Location: manage_students.php');
    exit;
}

// Fetch students to display in the table
if (isset($_GET['search']) && trim($_GET['search']) !== "") {
    $searchQuery = trim($_GET['search']); // Get and trim the search query
    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM students WHERE student_name LIKE :search ORDER BY id ASC");
        $stmt->execute([':search' => "%$searchQuery%"]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    try {
        // Fetch all students if no search is performed
        $query = $db->query("SELECT * FROM students ORDER BY id ASC");
        $students = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="../assets/css/ms.css">
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="dashboard.php" class="back-button">
            <span class="back-icon">&#8592;</span> Go Back
        </a>

        <h1>Manage Students</h1>
        <a href="stform.php" class="btn add">Add New Student</a>

         <!-- Search Form -->
         <form action="manage_students.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by student name" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
            <?php if ($searchQuery): ?>
                <a href="manage_students.php" class="clear-button">Clear Search</a>
            <?php endif; ?>
        </form>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Stream</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td data-label="Student ID"><?= htmlspecialchars($student['student_id']) ?></td>
                            <td data-label="Name"><?= htmlspecialchars($student['student_name']) ?></td>
                            <td data-label="DOB"><?= htmlspecialchars($student['dob']) ?></td>
                            <td data-label="Class"><?= htmlspecialchars($student['class_name']) ?></td>
                            <td data-label="Section"><?= htmlspecialchars($student['section']) ?></td>
                            <td data-label="Stream"><?= htmlspecialchars($student['stream']) ?></td>
                            <td data-label="Actions">
                                <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn edit">Edit</a>
                                <a href="manage_students.php?delete_id=<?= $student['student_id'] ?>" 
                                   class="btn delete" 
                                   onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
