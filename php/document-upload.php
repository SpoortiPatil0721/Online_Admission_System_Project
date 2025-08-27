<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uploadDir = "uploads/";

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $files = ['photo', 'signature', 'aadhar'];
    $success = true;

    foreach ($files as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
            $filename = basename($_FILES[$field]['name']);
            $targetFile = $uploadDir . time() . "_" . $filename;

            if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
                $success = false;
                echo "Error uploading $field file.<br>";
            }
        } else {
            $success = false;
            echo "Missing or invalid file for $field.<br>";
        }
    }

    if ($success) {
        // Redirect to next page
        header("Location: GandA-details.html");
        exit();
    }
}
?>
