<?php
session_start();
// Process form on POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "kle_users");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get application_no from session
    if (!isset($_SESSION['application_no'])) {
        die("Error: User not logged in or application_no missing.");
    }
    $application_no = $_SESSION['application_no'];

    // Get values
    $program_level = $_POST['program_level'];
    $stream = $_POST['stream'];
    $program = $_POST['program'];
    $source = $_POST['know_about_kle'];

    $tenth_board = $_POST['tenth_board'];
    $tenth_school = $_POST['tenth_school'];
    $tenth_year = $_POST['tenth_year'];

    $twelfth_board = $_POST['twelfth_board'];
    $twelfth_college = $_POST['twelfth_college'];
    $twelfth_year = $_POST['twelfth_year'];
    $twelfth_received = $_POST['twelfth_received'];

    // File uploads
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $tenth_path = $upload_dir . basename($_FILES['tenth_marksheet']['name']);
    move_uploaded_file($_FILES['tenth_marksheet']['tmp_name'], $tenth_path);

    $twelfth_path = '';
    if ($twelfth_received === "yes" && isset($_FILES['twelfth_marksheet'])) {
        $twelfth_path = $upload_dir . basename($_FILES['twelfth_marksheet']['name']);
        move_uploaded_file($_FILES['twelfth_marksheet']['tmp_name'], $twelfth_path);
    }

    // Insert data into program_details table
    $stmt = $conn->prepare("INSERT INTO program_details (
        application_no,program_level, stream, program, source_of_info,
        tenth_board, tenth_school, tenth_year, tenth_marksheet,
        twelfth_board, twelfth_college, twelfth_year, twelfth_received, twelfth_marksheet
    ) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssssss",
       $application_no, $program_level, $stream, $program, $source,
        $tenth_board, $tenth_school, $tenth_year, $tenth_path,
        $twelfth_board, $twelfth_college, $twelfth_year, $twelfth_received, $twelfth_path
    );

    if ($stmt->execute()) {
        header("Location: personal-details.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
