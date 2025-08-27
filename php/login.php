<?php
session_start();

// Database connection details
$host = 'localhost';
$db = 'kle_users'; // Make sure this matches your database name
$user = 'root';     // Change if your MySQL user is different
$pass = '';         // Set your MySQL password if any

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize user input
$username = trim($_POST['username']);
$password = $_POST['password'];

// Fetch user by username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Login successful - set session and redirect
        $_SESSION['username'] = $user['username'];
        $application_no = $_SESSION['application_no'];
        $_SESSION['application_no'] = $user['application_no'];  // ✅ Store application_no
        $_SESSION['fullname'] = $user['fullname'];  

        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid password
        echo "<script>alert('Incorrect password'); window.location.href='signin.html';</script>";
    }
} else {
    // User not found
    echo "<script>alert('User not found'); window.location.href='signin.html';</script>";
}

$stmt->close();
$conn->close();
?>
