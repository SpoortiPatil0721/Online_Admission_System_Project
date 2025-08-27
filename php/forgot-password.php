<?php
$resetMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate all fields are filled
    if (empty($username) || empty($email) || empty($new_password) || empty($confirm_password)) {
        $resetMessage = "All fields are required!";
    }
    // Check password match
    elseif ($new_password !== $confirm_password) {
        $resetMessage = "Passwords do not match!";
    }
    // Validate password strength
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
        $resetMessage = "Password must be at least 8 characters, include uppercase, lowercase, number, and symbol.";
    } else {
        $conn = new mysqli("localhost", "root", "", "kle_users");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update->bind_param("ss", $hashed_password, $username);
            $update->execute();

            $resetMessage = "<span style='color: green;'>Password successfully updated! <a href='signin.html'>Login here</a></span>";
        } else {
            $resetMessage = "Invalid username or email. You must be a registered user.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .reset-box {
      width: 400px;
      margin: auto;
      padding: 30px;
      border: 1px solid #ccc;
      margin-top: 50px;
    }
    .reset-box h2 {
      text-align: center;
    }
    .reset-box input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
    }
    .reset-box button {
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      color: white;
      border: none;
    }
    .reset-message {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="reset-box">
  <h2>Reset Password</h2>
  <form method="post" action="">
    <input type="text" name="username" placeholder="Username" pattern="^[A-Za-z0-9_]{3,20}$" title="3-20 characters, no special symbols except underscore" required>
    
    <input type="email" name="email" placeholder="Registered Email" required>
    
    <input type="password" name="new_password" placeholder="New Password"
           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
           title="Minimum 8 characters, with uppercase, lowercase, number, and special character" required>
    
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
    
    <button type="submit">Reset Password</button>
    <div class="reset-message"><?php echo $resetMessage; ?></div>
  </form>
</div>

</body>
</html>
