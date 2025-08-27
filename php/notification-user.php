<?php
session_start();
if (!isset($_SESSION['application_no'])) {
    header("Location: signin.html"); // Redirect to login if not authenticated
    exit();
}

$application_no = $_SESSION['application_no'];
$conn = new mysqli("localhost", "root", "", "kle_users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch notifications only for this application_no
$result = $conn->query("SELECT * FROM notifications WHERE application_no IS NULL 
 OR  application_no = '$application_no' ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard - Notifications</title>
  <link rel="stylesheet" href="style3.css" />
  <style>
    body { font-family: Arial, sans-serif; }
    .notification-list {
      margin: 40px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .notification-list h2 {
      margin-bottom: 20px;
      color: #ea6a2e;
    }
    .notification-item {
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }
    .notification-item strong {
      color: #333;
    }
    .notification-item em {
      color: #777;
      font-size: 12px;
      display: block;
      margin-top: 4px;
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
      <li><a href="my-documents.php">My Documents</a></li>
      <li><a href="my-payments.php">Payments</a></li>
      <li class="active"><a href="notification-user.php">Notification</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </aside>

  <main class="notification-list">
    <h2>My Notifications</h2>
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="notification-item">
          <strong><?= htmlspecialchars($row['title']) ?>:</strong>
          <?= htmlspecialchars($row['message']) ?>
          <em><?= $row['created_at'] ?></em>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No notifications yet.</p>
    <?php endif; ?>
  </main>
</div>
</body>
</html>
