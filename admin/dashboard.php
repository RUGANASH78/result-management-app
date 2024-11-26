<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/adb.css">
</head>
<body>
<img src="../assets/images/admin_dashboard.png" alt="Background" class="background-img">


    <header>
        <h1>Welcome, Admin</h1>
        <a href="../login.php" class="logout-button">Logout</a>
    </header>
    <div class="admin-dashboard">
    <div class="dashboard-cards">
    <div class="card">
            <h2>Create Staff</h2>
            <p>Create staff details.</p>
            <a href="stfform.php" class="btn">Go to Staff </a>
        </div>
        
        <div class="card">
            <h2>Create Students</h2>
            <p>Create student details.</p>
            <a href="stform.php" class="btn">Go to Students</a>
        </div>
        <div class="card">
            <h2>Manage Students</h2>
            <p>Add, update, delete student details.</p>
            <a href="manage_students.php" class="btn">Go to Students</a>
        </div>

        <div class="card">
            <h2>Manage Staff</h2>
            <p>Add, update, and manage staff details.</p>
            <a href="manage_staff.php" class="btn">Go to Staff</a>
        </div>

        <div class="card">
            <h2>Create Class</h2>
            <p>Create and assign classes with subjects.</p>
            <a href="create_class.php" class="btn">Create Class</a>
        </div>

        <div class="card">
            <h2>Manage School Content</h2>
            <p>Update school Event Details. </p>
            <a href="index_manage.php" class="btn">Upload Events</a>
        </div>
        <div class="card">
            <h2>Manage School Notice Board</h2>
            <p>Update school notices and circulars.</p>
            <a href="notice.php" class="btn">Upload Notice</a>
        </div>
        <div class="card">
            <h2>Manage Class</h2>
            <p>Update class details.</p>
            <a href="manage_classes.php" class="btn">Manage Class</a>
        </div>
        <div class="card">
            <h2>Manage About Content</h2>
            <p>Update About details.</p>
            <a href="manage_about.php" class="btn">Manage Class</a>
        </div>
    </div>
</div>

</body>
</html>

