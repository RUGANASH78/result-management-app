<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config/db.php'; // Ensure the path is correct

// Check if the student is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// Assuming student ID is stored in session after login
$student_id = $_SESSION['username'];

// Fetch student details, including stream, from the database
$sql= "SELECT student_name, class_name, section, profile_photo, stream FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Check if student data was retrieved correctly
if (!$student) {
    die("Student data not found.");
}

// Store the stream name for later use
$stream = $student['stream'];

// Fetch subjects based on student's stream
$sql_subjects = "SELECT name FROM subjects WHERE stream_id = (SELECT id FROM streams WHERE name = ?)";
$stmt_subjects = $conn->prepare($sql_subjects);
$stmt_subjects->bind_param("s", $stream);
$stmt_subjects->execute();
$subjects_result = $stmt_subjects->get_result();

// Check if subjects were retrieved correctly
if (!$subjects_result || $subjects_result->num_rows === 0) {
    echo "No subjects found for stream: " . htmlspecialchars($stream);
    $subjects = [];
} else {
    $subjects = [];
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[] = $row['name']; // Ensure 'name' is the correct column in your subjects table
    }
}

$stmt_subjects->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/sdhome.css">
</head>
<body>
    <!-- navbar.php -->
<nav class="navbar">
    <!-- Left side profile photo or default image -->
    <div class="navbar-left">
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
    
    
    
    <!-- Right side icon with dropdown -->
   <div class="navbar-right">
        <div class="dropdown">
            <button class="dropbtn">
                <i class="fas fa-user-circle"></i> <!-- Font Awesome icon -->
            </button>
            <div class="dropdown-content">
                <a href="../student/student_home.php">Student Home</a>
                <a href="../student/view_results.php">Results</a>
                <a href="../login.php">Logout</a>
                <a href="../index.php">Home</a>
            </div>
        </div>
        </div>
        </div>

</nav>
  <div class="student-home">
        <h2>Hi, <?php echo htmlspecialchars($student['student_name']); ?></h2>
        
        <div class="profile-info">
           
            <p><strong>Class & Section:</strong> <?php echo htmlspecialchars($student['class_name']) . " " . htmlspecialchars($student['section']); ?></p>
            
            <p><strong>Subjects:</strong></p>
            <ul>
                <?php 
                if (empty($subjects)) {
                    echo "<li>No subjects found.</li>";
                } else {
                    foreach ($subjects as $subject): ?>
                        <li><?php echo htmlspecialchars($subject); ?></li>
                    <?php endforeach; 
                }
                ?>
            </ul>
            <a href="view_results.php" class="btn">View Results</a>
        </div>
    </div>
     
</body>
</html>
