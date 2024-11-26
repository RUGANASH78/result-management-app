<?php
include '../config/db.php'; // Database connection

// Fetch all staff records from the database
try {
    $query = $db->query("SELECT * FROM staff ORDER BY id ASC");
    $staffList = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
$searchQuery = ''; // Initialize search query variable
$staffList = []; // Initialize staff list

// Check if a search request is made
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']); // Get and trim the search query
    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM staff WHERE staff_name LIKE :search ORDER BY id ASC");
        $stmt->execute([':search' => "%$searchQuery%"]);
        $staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Fetch all staff records if no search is performed
    try {
        $query = $db->query("SELECT * FROM staff ORDER BY id ASC");
        $staffList = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Manage Staff</title>
    <link rel="stylesheet" href="../assets/css/mstf.css">
</head>
<body>

<div class="container">
    <header class="header">
        <a href="dashboard.php" class="back-button">← Back to Dashboard</a>
        <h1>Staff Management</h1>
        <a href="stfform.php" class="add-button">+ Add New Staff</a>
    </header>

    <main>
        <!-- Search Form -->
        <form action="manage_staff.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by staff name" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
            <?php if ($searchQuery): ?>
                <a href="manage_staff.php" class="clear-button">Clear Search</a>
            <?php endif; ?>
        </form>

        <!-- Staff Table -->
        <table class="staff-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Joining Date</th>
                    <th>DOB</th>
                    <th>Degree</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Subject</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Coordinator</th>
                    <th>Login ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($staffList)): ?>
                    <?php foreach ($staffList as $staff): ?>
                        <tr>
                            <td><?= htmlspecialchars($staff['id']) ?></td>
                            <td><?= htmlspecialchars($staff['staff_name']) ?></td>
                            <td><?= htmlspecialchars($staff['joining_date']) ?></td>
                            <td><?= htmlspecialchars($staff['dob']) ?></td>
                            <td><?= htmlspecialchars($staff['degree']) ?></td>
                            <td><?= htmlspecialchars($staff['address']) ?></td>
                            <td><?= htmlspecialchars($staff['mobile_number']) ?></td>
                            <td><?= htmlspecialchars($staff['subject_taken']) ?></td>
                            <td><?= htmlspecialchars($staff['class_sec_taken']) ?></td>
                            <td><?= htmlspecialchars($staff['sec']) ?></td>
                            <td><?= htmlspecialchars($staff['is_coordinator']) ?></td>
                            <td><?= htmlspecialchars($staff['login_id']) ?></td>
                            <td>
                                <a href="edit_staff.php?id=<?= $staff['id'] ?>" class="edit-button">Edit</a>
                                <a href="delete_staff.php?id=<?= $staff['id'] ?>" 
                                   class="delete-button" 
                                   onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13">No staff found for "<?= htmlspecialchars($searchQuery) ?>"</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>