<?php
// Database connection settings
$servername = "localhost";
$username = "root";        // Change if you use a different DB user
$password = "";            // Add your DB password if any
$database = "kle_users";    // Your existing database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and fetch input values
$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

// Insert the data into the database
$sql = "INSERT INTO contact_messages (name, email, phone, message)
        VALUES ('$name', '$email', '$phone', '$message')";

if ($conn->query($sql) === TRUE) {
    // Success: show popup and redirect
    echo "<script>
            alert('Your message has been submitted successfully!');
            window.location.href = 'contact.html';
          </script>";
} else {
    // Failure: show error
    echo "<script>
            alert('There was an error submitting your message. Please try again.');
            window.history.back();
          </script>";
}

$conn->close();
?>
