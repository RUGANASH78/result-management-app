<?php
include '../config/db.php'; // Database connection

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM school_content WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage_about.php');
    exit;
}

// Fetch All Items
$stmt = $db->query("SELECT * FROM school_content ");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage School About Content</title>
    <link rel="stylesheet" href="../assets/css/im.css">
</head>
<body>

<h2>Upload School About Content</h2>

<!-- Form to Upload New Content -->
<form method="POST" action="save_notice.php" enctype="multipart/form-data">
   
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" rows="4"></textarea>

    <label>Upload Image (Optional):</label>
    <input type="file" name="image">

    <button type="submit">Upload</button>
</form>

<h2>Manage Uploaded Content</h2>

<!-- Display Uploaded Content -->
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['about_school']) ?></td>
                <td>
                    <?php if ($item['image_path']): ?>
                        <img src="<?= '../uploads/' . htmlspecialchars($item['image_path']) ?>" width="100">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_about.php?id=<?= $item['id'] ?>">Edit</a> |
                    <a href="manage_about.php" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
