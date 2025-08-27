<?php
session_start();
if (!isset($_SESSION['application_no'])) {
  die("Application number not set. Please login again.");
}

$application_no = $_SESSION['application_no'];

// Sanitize POST inputs
$father_name = $_POST['father_name'] ?? '';
$father_occupation = $_POST['father_occupation'] ?? '';
$father_phone = $_POST['father_phone'] ?? '';
$mother_name = $_POST['mother_name'] ?? '';
$mother_occupation = $_POST['mother_occupation'] ?? '';
$mother_phone = $_POST['mother_phone'] ?? '';
$permanent_address = $_POST['permanent_address'] ?? '';
$correspondence_address = $_POST['correspondence_address'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$pin_code = $_POST['pin_code'] ?? '';

// DB Connection
$conn = new mysqli('localhost', 'root', '', 'kle_users');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if a record already exists
$check_sql = "SELECT application_no FROM guardian_address_details WHERE application_no = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $application_no);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
  // Record exists, perform UPDATE
  $sql = "UPDATE guardian_address_details SET
            father_name = ?,
            father_occupation = ?,
            father_phone = ?,
            mother_name = ?,
            mother_occupation = ?,
            mother_phone = ?,
            permanent_address = ?,
            correspondence_address = ?,
            city = ?,
            state = ?,
            pin_code = ?
          WHERE application_no = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssss",
    $father_name, $father_occupation, $father_phone,
    $mother_name, $mother_occupation, $mother_phone,
    $permanent_address, $correspondence_address, $city, $state, $pin_code,
    $application_no
  );
} else {
  // Record doesn't exist, perform INSERT
  $sql = "INSERT INTO guardian_address_details (
            application_no, father_name, father_occupation, father_phone,
            mother_name, mother_occupation, mother_phone,
            permanent_address, correspondence_address, city, state, pin_code
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssss",
    $application_no, $father_name, $father_occupation, $father_phone,
    $mother_name, $mother_occupation, $mother_phone,
    $permanent_address, $correspondence_address, $city, $state, $pin_code
  );
}

if ($stmt->execute()) {
  header("Location: declaration.html");
  exit();
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$check_stmt->close();
$conn->close();
?>
