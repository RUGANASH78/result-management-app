<?php
include '../config/db.php'; // Database connection

// Check if the ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Delete the staff record
        $stmt = $db->prepare("DELETE FROM staff WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: manage_staff.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting staff: " . $e->getMessage());
    }
} else {
    echo "No staff ID provided.";
}
?>
