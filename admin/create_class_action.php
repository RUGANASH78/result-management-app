<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_name'];
    $section = $_POST['section'];
    $grade = $_POST['grade'];
    $stream_id = $_POST['stream_id'];
    $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];

    // Insert class into the database
    $stmt = $db->prepare("INSERT INTO classes (class_name, section, subjects) VALUES (:class_name, :section, :subjects)");
    $stmt->execute([':class_name' => $class_name, ':section' => $section, ':subjects' => implode(',', $subjects)]);

    header("Location: dashboard.php");
    exit();
}
if ($stmt->execute()) {
    echo "Class created successfully.";
} else {
    echo "Error creating class.";
}
}
?>