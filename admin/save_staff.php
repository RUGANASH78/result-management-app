<?php
include '../config/db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure variables are correctly assigned from the form input
    $staff_name = $_POST['staff_name'] ?? null;
    $joining_date = $_POST['joining_date'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $degree = $_POST['degree'] ?? null;
    $address = $_POST['address'] ?? null;
    $mobile_number = $_POST['mobile_number'] ?? null;
    $subject_taken = $_POST['subject_taken'] ?? null;
    $class_sec_taken = $_POST['class_sec_taken'] ?? null; // Corrected name
    $sec = $_POST['sec'] ?? null;
    $is_coordinator = $_POST['is_coordinator'] ?? 'No'; // Default to 'No'

    // Check if required fields are not empty
    if (!$class_sec_taken || !$sec) {
        die("Class and Section must be selected.");
    }

    try {
        // Generate unique login ID
        $stmt = $db->query("SELECT COUNT(*) AS total FROM staff");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'] + 1;
        $login_id = sprintf("STAFF%03d", $count);

        // Insert into the staff table
        $insert_stmt = $db->prepare("
            INSERT INTO staff (staff_name, joining_date, dob, degree, address, mobile_number, 
                               subject_taken, class_sec_taken, sec, is_coordinator, login_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insert_stmt->execute([
            $staff_name, $joining_date, $dob, $degree, $address, $mobile_number,
            $subject_taken, $class_sec_taken, $sec, $is_coordinator, $login_id
        ]);

        header('Location: manage_staff.php'); // Redirect to the staff management page
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
