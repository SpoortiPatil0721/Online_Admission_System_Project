<?php




$host = 'localhost';
$db = 'kle_users';
$user = 'root'; // Change if your MySQL user is different
$pass = '';     // Change if your MySQL has a password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize form input
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$gender = $_POST['gender'];

// ✅ Block known fake/test emails
$blockedEmails = ['abc@gmail.com', '123@gmail.com', 'test@gmail.com', 'test123@gmail.com'];
if (in_array(strtolower($email), $blockedEmails)) {
    die("<script>alert('Please use a valid personal email address.'); window.location.href='signup.html';</script>");
}

// ✅ Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<script>alert('Invalid email format.'); window.location.href='signup.html';</script>");
}
// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Generate unique application number (example: APP202505281234)
$application_no = 'APP' . date('Ymd') . rand(1000, 9999);
// Check for duplicate email
$checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmail->store_result();

if ($checkEmail->num_rows > 0) {
    echo "<script>alert('This email is already registered. Please use another one.'); window.location.href='signup.html';</script>";
    exit();
}
$checkEmail->close();

// Optional: Check for duplicate username too
$checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
$checkUsername->bind_param("s", $username);
$checkUsername->execute();
$checkUsername->store_result();

if ($checkUsername->num_rows > 0) {
    echo "<script>alert('Username already taken. Please choose another username.'); window.location.href='signup.html';</script>";
    exit();
}
$checkUsername->close();


// Insert into database
$sql = "INSERT INTO users (fullname, username, email, phone, password, gender, application_no) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $fullname, $username, $email, $phone, $hashed_password, $gender,$application_no);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Please login.'); window.location.href='signin.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
