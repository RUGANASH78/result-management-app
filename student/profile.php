<?php
session_start();
// Include database connection
include('../config/db.php');
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

// Check if the student is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../student/student_home.php");
    exit;
} else {
    $student_id = $_SESSION['username']; // Get student_id from session
}

// Fetch student profile details
$sql = "SELECT student_id, student_name, class_name, section, profile_photo, stream, father_name, mother_name, father_occupation, mobile_number, address, dob FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Handle profile photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    $upload_dir = "../uploads/student_photos/";
    $file_name = basename($_FILES['profile_photo']['name']);
    $upload_file = $upload_dir . $student_id . "_" . $file_name;

    $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
    if (in_array($file_type, ['jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_file)) {
            $sql = "UPDATE students SET profile_photo = ? WHERE student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $upload_file, $student_id);
            if ($stmt->execute()) {
                echo "Profile photo updated successfully.";
                // Refresh the page to show the updated profile photo
                header("Location: profile.php");
                exit;
            } else {
                echo "Error updating profile photo.";
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
    <title>Student Profile</title>
    <link rel="stylesheet" href="../assets/css/sdp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="student-profile">
    <div class="first-part">
        <div class="profile">
            <?php if (!empty($student['profile_photo'])): ?>
                <a href="../student/profile.php">
                    <img src="<?php echo htmlspecialchars($student['profile_photo']); ?>" alt="Profile Photo" class="profile-photo">
                </a>
            <?php else: ?>
                <a href="../student/profile.php">
                    <img src="../assets/images/default_img.png" alt="Default Profile" class="profile-photo">
                </a>
            <?php endif; ?>
        </div>
        <div class="form-design">
            <form action="profile.php" method="POST" enctype="multipart/form-data">
                <div class="form-content">
                    <input type="file" name="profile_photo" accept="image/*" required>
                    <button type="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
    <div class="details">
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id'] ?? ''); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['student_name'] ?? ''); ?></p>
        <p><strong>Class:</strong> <?php echo htmlspecialchars($student['class_name'] ?? ''); ?></p>
        <p><strong>Section:</strong> <?php echo htmlspecialchars($student['section'] ?? ''); ?></p>
        <p><strong>Stream:</strong> <?php echo htmlspecialchars($student['stream'] ?? ''); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob'] ?? ''); ?></p>
        <p><strong>Father's Name:</strong> <?php echo htmlspecialchars($student['father_name'] ?? ''); ?></p>
        <p><strong>Father's Occupation:</strong> <?php echo htmlspecialchars($student['father_occupation'] ?? ''); ?></p>
        <p><strong>Mother's Name:</strong> <?php echo htmlspecialchars($student['mother_name'] ?? ''); ?></p>
        <p><strong>Mobile No:</strong> <?php echo htmlspecialchars($student['mobile_number'] ?? ''); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address'] ?? ''); ?></p>
    </div>

    <button class="btn" onclick="window.location.href='../student/student_home.php';">Back</button>
</div>

<script>
    function toggleMenu() {
        document.querySelector('.menu').classList.toggle('active');
    }
</script>
</body>
</html>
