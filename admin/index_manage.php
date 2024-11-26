<?php
include '../config/db.php'; // Database connection

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM index_manage WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index_manage.php');
    exit;
}

// Fetch All Items
$stmt = $db->query("SELECT * FROM index_manage ORDER BY created_at DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage School Content</title>
    <link rel="stylesheet" href="../assets/css/im.css">
</head>
<body>

<h2>Upload School Events, Infra, Buildings, and Notices</h2>

<!-- Form to Upload New Content -->
<form method="POST" action="save_content.php" enctype="multipart/form-data">
    <label>Category:</label>
    <select name="category" required>
        <option value="event">Event</option>
        <option value="infra">Infrastructure</option>
        <option value="building">Building</option>
        
    </select>

    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" rows="4"></textarea>

    <label>Upload Image (Optional):</label>
    <input type="file" name="image">
    <div class="upload">
    <a href="dashboard.php"> Go Back</a>
    <button type="submit">Upload</button>
    </div>
</form>

<h2>Manage Uploaded Content</h2>

<!-- Display Uploaded Content -->
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['category']) ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td>
                    <?php if ($item['image_path']): ?>
                        <img src="<?= '../uploads/' . htmlspecialchars($item['image_path']) ?>" width="100">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_content.php?id=<?= $item['id'] ?>">Edit</a> |
                    <a href="index_manage.php?delete_id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
