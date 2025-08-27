<?php
session_start();
if (!isset($_SESSION['application_no'])) {
  header("Location: signin.html");
  exit();
}

$application_no = $_SESSION['application_no'];
$conn = new mysqli('localhost', 'root', '', 'kle_users');

$photo = $signature = $aadhar = '';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT photo_path, signature_path, aadhar_path FROM uploaded_documents WHERE application_no = ?");
$stmt->bind_param("s", $application_no);
$stmt->execute();
$stmt->bind_result($photo, $signature, $aadhar);
$stmt->fetch();
$stmt->close();

/* ----------------------------------------------------
   Get 10th & 12th marks‑cards from program_details
---------------------------------------------------- */
$tenth_card = $twelfth_card = '';

$stmt2 = $conn->prepare(
  "SELECT tenth_marksheet, twelfth_marksheet
   FROM program_details
   WHERE application_no = ?"
);
$stmt2->bind_param("s", $application_no);
$stmt2->execute();
$stmt2->bind_result($tenth_card, $twelfth_card);
$stmt2->fetch();
$stmt2->close();

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Documents</title>
  <link rel="stylesheet" href="style3.css" />
  <style>
    .content {
      padding: 20px;
      flex: 1;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    input[type="file"] {
      margin-top: 5px;
    }
    .upload-btn {
      margin-top: 10px;
      padding: 5px 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
    }
    .upload-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
<div class="dashboard-container">
  <aside class="sidebar">
    <h2>KLEBCADWD</h2>
    <ul>
      <li><a href="dashboard.php">Home</a></li>
      <li><a href="all-applications.php">All Applications</a></li>
      <li><a href="my-applications.php">My Applications</a></li>
      <li class="active"><a href="my-documents.php">My Documents</a></li>
      <li><a href="my-payments.php">Payments</a></li>
      <li><a href="notification-user.php">Notification</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <div class="content">
    <h2>My Uploaded Documents</h2>
    <table>
      <thead>
        <tr>
          <th>Document Type</th>
          <th>Status</th>
          <th>Download</th>
          <th>Upload/Replace</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Photo</td>
          <td><?= $photo ? 'Uploaded' : 'Not Uploaded' ?></td>
          <td>
            <?php if ($photo): ?>
              <a href="<?= $photo ?>" download>Download Photo</a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <input type="file" accept=".jpg,.jpeg" onchange="uploadFile(this, 'photo')">
          </td>
        </tr>
        <tr>
          <td>Signature</td>
          <td><?= $signature ? 'Uploaded' : 'Not Uploaded' ?></td>
          <td>
            <?php if ($signature): ?>
              <a href="<?= $signature ?>" download>Download Signature</a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <input type="file" accept=".jpg,.jpeg" onchange="uploadFile(this, 'signature')">
          </td>
        </tr>
        <tr>
          <td>Aadhar</td>
          <td><?= $aadhar ? 'Uploaded' : 'Not Uploaded' ?></td>
          <td>
            <?php if ($aadhar): ?>
              <a href="<?= $aadhar ?>" download>Download Aadhar</a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td>
            <input type="file" accept=".pdf" onchange="uploadFile(this, 'aadhar')">
          </td>
        </tr>
        <tr>
  <td>10<sup>th</sup> Marks‑card</td>
  <td><?= $tenth_card ? 'Uploaded' : 'Not Uploaded' ?></td>
  <td>
    <?php if ($tenth_card): ?>
      <a href="<?= $tenth_card ?>" download>Download</a>
    <?php else: ?>
      — 
    <?php endif; ?>
  </td>
  <td>
    <input type="file" accept=".pdf,.jpg,.jpeg"
           onchange="uploadFile(this, 'tenth_marksheet')">
  </td>
</tr>

<tr>
  <td>12<sup>th</sup> Marks‑card</td>
  <td><?= $twelfth_card ? 'Uploaded' : 'Not Uploaded' ?></td>
  <td>
    <?php if ($twelfth_card): ?>
      <a href="<?= $twelfth_card ?>" download>Download</a>
    <?php else: ?>
      — 
    <?php endif; ?>
  </td>
  <td>
    <input type="file" accept=".pdf,.jpg,.jpeg"
           onchange="uploadFile(this, 'twelfth_marksheet')">
  </td>
</tr>

      </tbody>
    </table>
  </div>
</div>

<script>
function uploadFile(input, type) {
  const file = input.files[0];
  const formData = new FormData();
  formData.append("file", file);
  formData.append("type", type);

  fetch("upload-handler.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    if (data.status === 'success') {
      window.location.reload(); // Reload to reflect updated table
    }
  })
  .catch(err => {
    console.error(err);
    alert("Upload failed");
  });
}
</script>
</body>
</html>
