
<?php
include '../config/db.php'; // Database connection

// Fetch the staff member details to be edited
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $db->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Student member not found.");
    }
}

// Update staff member details
if (isset($_POST['update'])) {
    $student_name = $_POST['student_name'];
    $joining_date = $_POST['joining_date'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $stream = $_POST['stream'];
    $class = $_POST['class_name'];
    $father = $_POST['father_name'];
    $mom = $_POST['mother_name'];
    $occup = $_POST['father_occupation'];
    


    

    $sql = "UPDATE students SET 
            student_name = ?, joining_date = ?, dob = ?, 
            address = ?, mobile_number = ?, stream = ?, 
            class_name = ?, section = ?, father_name = ?, mother_name = ?,father_occupation = ? 
            WHERE id = ?";
    
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$student_name, $joining_date, $dob,  $address, 
                        $mobile_number, $stream, $class_name, 
                        $section,$father,$mom,$occup,$id])) {
        header("Location: manage_students.php");
        exit();
    } else {
        echo "Error updating student member.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="../assets/css/edstf.css">
</head>
<body>

<div class="container">
    <h1>Edit Staff Member</h1>
    <form action="" method="POST">
    <label for="staff_name">Student Name:</label>
        <input type="text" name="student_name" value="<?= $student['student_name'] ?>" required>
        <label for="joining_date">Joining Date:</label>
        <input type="date" name="joining_date" value="<?= $student['joining_date'] ?>" required>
        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" value="<?= $student['dob'] ?>" required>
        <label for="stream">Stream name:</label>
        <input type="text" name="stream" value="<?= $student['stream'] ?>" required>
        <label for="class_name">Class name:</label>
        <input type="text" name="class_name" value="<?= $student['class_name'] ?>" required>
        <label for="sec">Section:</label>
        <input type="text" name="sec" value="<?= $student['section'] ?>" required>
        <label for="father">Father name:</label>
        <input type="text" name="occupation" value="<?= $student['father_name'] ?>" required>
        <label for="mom">Mother name:</label>
        <input type="text" name="mom" value="<?= $student['mother_name'] ?>" required>
        

        <label for="occupation">Father occupation:</label>
        <input type="text" name="occupation" value="<?= $student['father_occupation'] ?>" required>
        <label for="address">Address:</label>
        <textarea name="address" required><?= $student['address'] ?></textarea>
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" name="mobile_number" value="<?= $student['mobile_number'] ?>" required>

        <button type="submit" name="update">Update</button>
    </form>
</div>

</body>
</html>
