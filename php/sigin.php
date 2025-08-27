<?php
session_start();

$_SESSION['admin_logged_in'] = true;
header("Location: admin_dashboard.php");
exit();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'kle_users');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: signin-admin.php?error=Invalid+password");
            exit();
        }
    } else {
        header("Location: signin-admin.php?error=Admin+not+found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
