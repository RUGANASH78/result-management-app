<?php
include '../config/db.php'; // Ensure the correct path to the config file

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $class_name = $_POST['class_name'];
        $section = $_POST['section'];
        $stream_id = $_POST['stream_id']; // This should capture the stream ID
        $subjects = $_POST['subjects'];

        // Fetch subject names based on the IDs received
        $subjectNames = [];
        $subjectIds = implode(',', $subjects); // Convert array to a comma-separated string

        $stmt = $db->prepare("SELECT name FROM subjects WHERE id IN (" . $subjectIds . ")");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the names

        // Store subject names in a string
        if ($result) {
            $subjectNames = implode(', ', $result); // Join names with a comma
        }

        // Fetch stream name based on the stream ID
        $stmtStream = $db->prepare("SELECT name FROM streams WHERE id = ?");
        $stmtStream->execute([$stream_id]);
        $streamName = $stmtStream->fetchColumn(); // Fetch the stream name

        // Prepare the SQL statement to insert data into created_class table
        $insert_stmt = $db->prepare("INSERT INTO created_class (class_name, section, grade, stream_name, subjects) VALUES (?, ?, ?, ?, ?)");
        
        // Replace '6' with the appropriate grade if needed
        $insert_stmt->execute([$class_name, $section, 6, $streamName, $subjectNames]);

        // Redirect or display success message
        header('Location: manage_classes.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
