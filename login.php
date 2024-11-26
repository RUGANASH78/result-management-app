<?php
session_start();
require_once '../result-management-app/config/db.php'; // Ensure this file contains your database connection code

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data safely
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

    // Prepare SQL based on user type
    if ($user_type === 'admin') {
        // Admin login: query the `user` table, using MD5 for now but consider updating to bcrypt
        $query = "SELECT * FROM users WHERE username = ? AND password = MD5(?)";
    } elseif ($user_type === 'staff') {
        // Staff login: query the `staff` table using DOB as password
        $query = "SELECT * FROM staff WHERE login_id = ? AND dob = ?";
    } elseif ($user_type === 'student') {
        // Student login: query the `student` table using DOB as password
        $query = "SELECT * FROM students WHERE student_id = ? AND dob = ?";
    } else {
        $error = "Invalid user type selected.";
        $user_type = ""; // Reset user type in case of error
    }

    // Proceed only if a valid user type was selected
    if (isset($query)) {
        // Prepare and execute the statement
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if login is successful
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Store session variables
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;

            // Redirect based on user type
            if ($user_type === 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($user_type === 'staff') {
                header("Location: staff/staff_home.php");
            } elseif ($user_type === 'student') {
                header("Location: student/student_home.php");
            }
            exit;
        } else {
            $error = "Invalid login credentials.";
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../result-management-app/assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username (Admin/Unique ID)</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>

            <label for="password">Password (Admin Password or DOB)</label>
            <input type="password" id="password" name="password" placeholder="Enter password or DOB in YYYY-MM-DD format" required>

            <label for="user_type">User Type</label>
            <select id="user_type" name="user_type" required>
                <option value="">Select User Type</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="student">Student</option>
            </select>

            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
