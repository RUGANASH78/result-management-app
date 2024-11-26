<?php
include '../config/db.php'; // Database connection

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM notice_manage WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $db->prepare("UPDATE notice_manage SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $description, $id]);

    header('Location: index_manage.php');
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

<h2>Edit Content</h2>

<form method="POST">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>

    <label>Description:</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($item['description']) ?></textarea>

    <button type="submit">Update</button>
</form>

</body>
</html>
