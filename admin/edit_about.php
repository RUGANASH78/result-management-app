<?php
include '../config/db.php'; // Database connection

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM school_content WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['about_school'];

    // Option 1: If you want to update image_path
    $image_path = $_POST['image_path'];  // Assuming you have an input for image_path

    $stmt = $db->prepare("UPDATE school_content SET title = ?, about_school = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$title, $description, $image_path, $id]);

    header('Location: manage_about.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <link rel="stylesheet" href="../assets/css/edstf.css">
</head>
<body>
<div class="container">
<h2>Edit Content</h2>

<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>

    <label>Description:</label>
    <textarea name="about_school" rows="4"><?= htmlspecialchars($item['about_school']) ?></textarea>

    <!-- Optionally, add a field for image_path -->
    <label>Image Path:</label>
    <?php if ($item['image_path']): ?>
         <img src="<?= '../uploads/' . htmlspecialchars($item['image_path']) ?>" width="100">            
    <?php else: ?>                    
        No Image
    <?php endif; ?>
    <input type="file" name="image_path" >

    <button type="submit">Update</button>
</form>
</div>
</body>
</html>
