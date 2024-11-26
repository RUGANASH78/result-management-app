<?php
include '../config/db.php'; // Ensure the correct path to the config file

if (isset($_GET['id'])) {
    $classId = $_GET['id'];

    try {
        $deleteQuery = $db->prepare("DELETE FROM created_class WHERE id = ?");
        $deleteQuery->execute([$classId]);
        echo "Class deleted successfully.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Redirect back to manage classes page
header("Location: manage_classes.php");
exit;
