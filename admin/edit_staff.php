
<?php
include '../config/db.php'; // Database connection

// Fetch the staff member details to be edited
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $db->prepare("SELECT * FROM staff WHERE id = ?");
    $stmt->execute([$id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        die("Staff member not found.");
    }
}

// Update staff member details
if (isset($_POST['update'])) {
    $staff_name = $_POST['staff_name'];
    $joining_date = $_POST['joining_date'];
    $dob = $_POST['dob'];
    $degree = $_POST['degree'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $subject_taken = $_POST['subject_taken'];
    $class_sec_taken = $_POST['class_sec_taken'];
    $sec = $_POST['sec'];
    $is_coordinator = $_POST['is_coordinator'];
    $login_id = $_POST['login_id'];

    $sql = "UPDATE staff SET 
            staff_name = ?, joining_date = ?, dob = ?, degree = ?, 
            address = ?, mobile_number = ?, subject_taken = ?, 
            class_sec_taken = ?, sec = ?, is_coordinator = ?, login_id = ? 
            WHERE id = ?";
    
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$staff_name, $joining_date, $dob, $degree, $address, 
                        $mobile_number, $subject_taken, $class_sec_taken, 
                        $sec, $is_coordinator, $login_id, $id])) {
        header("Location: manage_staff.php");
        exit();
    } else {
        echo "Error updating staff member.";
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
    <label for="staff_name">Staff Name:</label>
        <input type="text" name="staff_name" value="<?= $staff['staff_name'] ?>" required>
        <label for="joining_date">Joining Date:</label>
        <input type="date" name="joining_date" value="<?= $staff['joining_date'] ?>" required>
        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" value="<?= $staff['dob'] ?>" required>
        <label for="degree">Degree:</label>
        <input type="text" name="degree" value="<?= $staff['degree'] ?>" required>
        <label for="address">Address:</label>
        <textarea name="address" required><?= $staff['address'] ?></textarea>
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" name="mobile_number" value="<?= $staff['mobile_number'] ?>" required>
        <label for="subject_taken">Subject Taken:</label>
        <input type="text" name="subject_taken" value="<?= $staff['subject_taken'] ?>" required>
        <label for="class_sec_taken">Class Section Taken:</label>
        <input type="text" name="class_sec_taken" value="<?= $staff['class_sec_taken'] ?>" required>
        <label for="sec">Section:</label>
        <input type="text" name="sec" value="<?= $staff['sec'] ?>" required>
        <label for="is_coordinator">Is Coordinator:</label>
        <select name="is_coordinator" required>
            <option value="Yes" <?= $staff['is_coordinator'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
            <option value="No" <?= $staff['is_coordinator'] == 'No' ? 'selected' : '' ?>>No</option>
        </select>
        <label for="login_id">Login ID:</label>
        <input type="text" name="login_id" value="<?= $staff['login_id'] ?>" required>

        <button type="submit" name="update">Update</button>
    </form>
</div>

</body>
</html>
