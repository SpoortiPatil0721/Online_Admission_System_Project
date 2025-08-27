<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['application_no'])) {
  header("Location: signin.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Applications</title>
  <link rel="stylesheet" href="style3.css" />
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>KLEBCADWD</h2>
      <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li class="active"><a href="all-applications.php">All Applications</a></li>
        <li><a href="my-applications.php">My Applications</a></li>
        <li><a href="my-documents.php">My Documents</a></li>
        <li><a href="my-payments.php">Payments</a></li>
        <li><a href="notification-user.php">Notification</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <main class="applications-content">
      <div class="top-section">
        <div class="application-box">
          <h3>All Applications</h3>
          <p>Select a program to apply</p>
        </div>

        <div class="applicant-info-box">
          <h2>Applicant Name: <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
          <h2>Application No: <?php echo htmlspecialchars($_SESSION['application_no']); ?></h2>
        </div>
      </div>

      <div class="programs-grid">
        <div class="program-card">
          <img src="images.jpeg" alt="BCA Degree" />
          <h3>Undergraduation - BCA</h3>
          <p><a href="form.html">Click here to Apply for BCA</a></p>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
