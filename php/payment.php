<?php
session_start();

// Step 1: Check if application_no exists
if (!isset($_SESSION['application_no'])) {
    die("Session expired. Please log in again.");
}

$application_no = $_SESSION['application_no'];
$payment_mode = $_POST['payment_mode'] ?? '';

if (empty($payment_mode)) {
    die("Payment mode not selected.");
}

// Step 2: Connect to the database
$host = "localhost";
$db = "kle_users";  // Change if different
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 3: Check if record already exists
$check = $conn->prepare("SELECT * FROM payment_details WHERE application_no = ?");
$check->bind_param("s", $application_no);
$check->execute();
$result = $check->get_result();

// Step 4: Insert or update payment
if ($result->num_rows > 0) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE payment_details SET payment_mode = ?, payment_status = 'paid', paid_at = NOW() WHERE application_no = ?");
    $stmt->bind_param("ss", $payment_mode, $application_no);
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO payment_details (application_no, payment_mode, payment_status, paid_at) VALUES (?, ?, 'paid', NOW())");
    $stmt->bind_param("ss", $application_no, $payment_mode);
}

// Step 5: Execute and redirect
if ($stmt->execute()) {
    header("Location: thankyou.html");
    exit();
} else {
    echo "Failed to record payment: " . $stmt->error;
}
?>
