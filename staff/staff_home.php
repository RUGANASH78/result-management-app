<?php
// Start session and include database connection
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config/db.php'; // Ensure path is correct


if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

// Initialize error handling in case database issues arise
try {
    // Ensure $db is defined in db.php
    if (!isset($db)) {
        throw new Exception("Database connection not established.");
    }

    // Get staff details from the session
    $staff_id = $_SESSION['username'];

    // Fetch staff details from the database
    $stmt = $db->prepare("
        SELECT staff_name, subject_taken, class_sec_taken, sec 
        FROM staff 
        WHERE login_id = ?
    ");
    $stmt->execute([$staff_id]);
    $staff_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff_info) {
        throw new Exception("Staff not found.");
    }

    // Extract staff details
    $staff_name = $staff_info['staff_name'];
    $subject = $staff_info['subject_taken'];
    $classes = $staff_info['class_sec_taken'];
    $section = $staff_info['sec'];
    //$profile_photo = $staff_info['profile_photo'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
try {
    // Fetch streams, classes, sections, and subjects from the database
    $streams = $db->query("SELECT * FROM streams")->fetchAll();
    $classes = $db->query("SELECT DISTINCT class_name FROM classes")->fetchAll();
    $sections = $db->query("SELECT DISTINCT section FROM classes")->fetchAll();
    $subjects = $db->query("SELECT id, name, stream_id FROM subjects")->fetchAll();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/stfho.css">
</head>
<body>
       <!-- navbar.php -->
<nav class="navbar">
    <!-- Left side profile photo or default image -->
    <div class="navbar-left">
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
    
    <!-- Center page title -->
    <div class="navbar-title">
        <h1>Hi, <?= htmlspecialchars($staff_name) ?>!</h1>
</div>
    
    <!-- Right side icon with dropdown -->
   <div class="navbar-right">
        <div class="dropdown">
            <button class="dropbtn">
                <i class="fas fa-user-circle"></i> <!-- Font Awesome icon -->
            </button>
            <div class="dropdown-content">
                <a href="../staff/staff_home.php">Staff Home</a>
                <a href="../staff/update_marks.php">Results & Feedback</a>
                <a href="../login.php">Logout</a>
                <a href="../index.php">Home</a>
            </div>
        </div>
        </div>
        </div>

</nav>
    <div class="container">
        <header>
            <div class="profile">
                <h1>Hi, <?= htmlspecialchars($staff_name) ?>!</h1>
            </div>
        </header>
        <section class="options">
            <h2>Select Class Details to Update Marks and Feedback</h2>
            <form method="POST" action="update_marks.php">
                <!-- Class Selection -->
                <label for="class_name">Select Class:</label>
                <select name="class_name" id="class_name" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['class_name'] ?>"><?= $class['class_name'] ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <!-- Section Selection -->
                <label for="section">Select Section:</label>
                <select name="section" id="section" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= $section['section'] ?>"><?= $section['section'] ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <!-- Stream Selection -->
                <label for="stream_id">Select Stream:</label>
                <select name="stream_id" id="stream_id" onchange="autoSelectSubjects()" required>
                    <option value="">Select Stream</option>
                    <?php foreach ($streams as $stream): ?>
                        <option value="<?= $stream['id'] ?>"><?= $stream['name'] ?></option>
                    <?php endforeach; ?>
                </select><br><br>

              

                <!-- Submit Button -->
                <button type="submit">Go to Update Marks & Feedback</button>
            </form>
        </section>
    </div>

    <script>
        function autoSelectSubjects() {
            const streamId = document.getElementById('stream_id').value;
            const subjectOptions = document.querySelectorAll('#subjects option');

            subjectOptions.forEach(option => {
                if (option.getAttribute('data-stream-id') === streamId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }
    </script>
</div>
</body>
</html>

 
