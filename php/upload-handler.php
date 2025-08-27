<?php
session_start();
$application_no = $_SESSION['application_no'] ?? null;

/* ----------------------------------------------------
   CONFIG: one place to tune behaviour per document type
---------------------------------------------------- */
$docConfig = [
  'photo'  => [
      'table'   => 'uploaded_documents',
      'column'  => 'photo_path',
      'mimes'   => ['image/jpeg', 'image/jpg'],
      'maxMB'   => 2
  ],
  'signature' => [
      'table'   => 'uploaded_documents',
      'column'  => 'signature_path',
      'mimes'   => ['image/jpeg', 'image/jpg'],
      'maxMB'   => 2
  ],
  'aadhar' => [
      'table'   => 'uploaded_documents',
      'column'  => 'aadhar_path',
      'mimes'   => ['application/pdf'],
      'maxMB'   => 2
  ],
  'tenth_marksheet' => [
      'table'   => 'program_details',
      'column'  => 'tenth_marksheet',
      'mimes'   => ['application/pdf', 'image/jpeg', 'image/jpg'],
      'maxMB'   => 2
  ],
  'twelfth_marksheet' => [
      'table'   => 'program_details',
      'column'  => 'twelfth_marksheet',
      'mimes'   => ['application/pdf', 'image/jpeg', 'image/jpg'],
      'maxMB'   => 2
  ]
];

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$response = ["status" => "error", "message" => "Unknown error"];

if (isset($_FILES['file'], $_POST['type'], $docConfig[$_POST['type']], $application_no)) {

    $file = $_FILES['file'];
    $type = $_POST['type'];
    $cfg  = $docConfig[$type];

    /* ---------- basic validation ---------- */
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $cfg['mimes'])) {
        $response['message'] = "Invalid file type for $type.";
        echo json_encode($response); exit;
    }
    if ($file['size'] > $cfg['maxMB'] * 1024 * 1024) {
        $response['message'] = "File exceeds {$cfg['maxMB']} MB limit.";
        echo json_encode($response); exit;
    }

    /* ---------- move file ---------- */
    $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName   = $type . '_' . $application_no . '.' . $ext;
    $target    = $targetDir . $newName;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        $response['message'] = "Failed to store file on server.";
        echo json_encode($response); exit;
    }

    /* ---------- save path in DB ---------- */
    $conn = new mysqli('localhost', 'root', '', 'kle_users');
    if ($conn->connect_error) {
        $response['message'] = "Database connection failed.";
        echo json_encode($response); exit;
    }

    $table  = $cfg['table'];
    $column = $cfg['column'];

    /* ensure a row exists, then update */
    $ensure = $conn->prepare("INSERT IGNORE INTO $table (application_no) VALUES (?)");
    $ensure->bind_param("s", $application_no);
    $ensure->execute();
    $ensure->close();

    $stmt = $conn->prepare("UPDATE $table SET $column = ? WHERE application_no = ?");
    $stmt->bind_param("ss", $target, $application_no);

    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "File uploaded and saved."];
    } else {
        $response['message'] = "Database update failed.";
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
