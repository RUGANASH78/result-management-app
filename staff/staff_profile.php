<?php
session_start();
include '../config/db.php'; // Ensure path is correct

// Check if the staff is logged in and fetch the staff_id from session
if (!isset($_SESSION['username'])) {
    header('Location: ../staff/staff_home.php'); // Redirect if not logged in
    exit;
} else {
    $staff_id = $_SESSION['username']; // Get staff_id from session
}

// Fetch staff profile information
$sql = "SELECT staff_name, subject_taken, class_sec_taken, sec, profile_photo,dob,degree,address,mobile_number FROM staff WHERE login_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();
$stmt->close();

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    // Define upload directory
    $upload_dir = "../uploads/staff_photos/";
    $file_name = basename($_FILES['profile_photo']['name']);
    $upload_file = $upload_dir . $staff_id . "_" . $file_name;

    // Check file type (only allow JPG, JPEG, PNG)
    $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
    if (in_array($file_type, ['jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_file)) {
            // Update the profile_photo path in the database
            $sql = "UPDATE staff SET profile_photo = ? WHERE staff_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $upload_file, $staff_id);
            if ($stmt->execute()) {
                echo "Profile photo updated successfully.";
                // Refresh the page to load the new profile photo
                header("Location: staff_profile.php");
                exit;
            } else {
                echo "Error updating profile photo: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, JPEG, and PNG files are allowed.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Profile</title>
    <link rel="stylesheet" href="../assets/css/stp.css">
</head>
<body>
    
    
<div class="student-profile">
    <div class="first-part">
    <div class="profile">
        <?php if (!empty($staff['profile_photo'])): ?>
            <a href="../staff/staff_profile.php">
                <img src="<?php echo htmlspecialchars($staff['profile_photo']); ?>" alt="Profile Photo" width="150" height="150"> 
            </a>
            <br>
        <?php else: ?>
            <a href="../staff/staff_profile.php">
                <img src="../assets/images/default_img.png" alt="Default Profile" class="profile-photo">
            </a>
        <?php endif; ?>
    </div>
    <div class="form-design">    
        <form action="staff_profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_photo" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
    </div>
    </div> 
    <div class="details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($staff['staff_name'] ?? ''); ?></p>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($staff['subject_taken'] ?? ''); ?></p>
        <p><strong>Class & Section:</strong> <?php echo htmlspecialchars($staff['class_sec_taken'] ?? '') . " " . htmlspecialchars($staff['sec'] ?? ''); ?></p>
        <p><strong>Degree:</strong> <?php echo htmlspecialchars($staff['degree'] ?? ''); ?></p>
        <p><strong>Date of Brith:</strong> <?php echo htmlspecialchars($staff['dob'] ?? ''); ?></p>
        <p><strong>Mobile NO:</strong><?php echo htmlspecialchars($staff['mobile_number'] ?? ''); ?></p>
        <p><strong>Address:</strong><?php echo htmlspecialchars($staff['address'] ?? ''); ?></p>

    </div>
    
    <button class="btn" onclick="window.location.href='../staff/staff_home.php';">Back</button>


</div>
   
 

    

  

</body>
</html>
