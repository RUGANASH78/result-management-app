<?php
include '../config/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_path = null;

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_path = '../uploads/' . $image_name;

        if (move_uploaded_file($image_tmp, $upload_path)) {
            $image_path = $image_name;
        }
    }

    // Insert into Database
    $stmt = $db->prepare("INSERT INTO index_manage (category, title, description, image_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$category, $title, $description, $image_path]);

    header('Location: index_manage.php');
    exit;
}
?>
