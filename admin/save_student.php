<?php
include '../config/db.php'; // Ensure correct path to DB config

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $joining_date = $_POST['joining_date'];
    $student_name = $_POST['student_name'];
    $dob = $_POST['dob'];
    $father_name = $_POST['father_name'];
    $father_occupation = $_POST['father_occupation'];
    $mother_name = $_POST['mother_name'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $class_name = $_POST['class_name'];
    $section = $_POST['section'];
    $stream_name = $_POST['stream_name'];

    try {
        // Begin a transaction to ensure atomic operations
        $db->beginTransaction();

        // Insert student data into the 'students' table
        $stmt = $db->prepare("
            INSERT INTO students (joining_date, student_name, dob, father_name, father_occupation, 
                                  mother_name, address, mobile_number, class_name, section, stream)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $joining_date, $student_name, $dob, $father_name, $father_occupation, 
            $mother_name, $address, $mobile_number, $class_name, $section, $stream_name
        ]);

        // Fetch all students in the same class and section, sorted alphabetically by name
        $sort_stmt = $db->prepare("
            SELECT id, student_name 
            FROM students 
            WHERE class_name = ? AND section = ? 
            ORDER BY student_name ASC
        ");
        $sort_stmt->execute([$class_name, $section]);
        $students = $sort_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Step 1: Temporarily update all student IDs to prevent conflicts
        foreach ($students as $student) {
            $temp_id = 'TEMP_' . $student['id'];
            $update_temp_stmt = $db->prepare("UPDATE students SET student_id = ? WHERE id = ?");
            $update_temp_stmt->execute([$temp_id, $student['id']]);
        }

        // Step 2: Reassign proper unique IDs based on alphabetical order
        foreach ($students as $index => $student) {
            $new_id = sprintf("%s%s%03d", $class_name, strtoupper($section), $index + 1);

            $update_stmt = $db->prepare("UPDATE students SET student_id = ? WHERE id = ?");
            $update_stmt->execute([$new_id, $student['id']]);
        }

        // Commit the entire transaction at the end
        $db->commit();

        // Redirect or display success message
        header('Location: manage_students.php');
        exit;

    } catch (PDOException $e) {
        // Check if a transaction is active before rolling back
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        die("Error: " . $e->getMessage());
    }
}
?>
