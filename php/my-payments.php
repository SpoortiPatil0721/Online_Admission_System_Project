<?php
session_start();
if (!isset($_SESSION['application_no'])) {
    header("Location: signin.html");
    exit();
}

$application_no = $_SESSION['application_no'];

// Connect to DB
$host = "localhost";
$db = "kle_users";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch payment status
$sql = "SELECT payment_mode, payment_status, paid_at FROM payment_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();

$payment = null;

if ($result && $result->num_rows > 0) {
    $payment = $result->fetch_assoc();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="style3.css" />

  <style>

.main-content {
      flex-grow: 1;
      padding: 40px;
      background-color: #fff;
    }

    h1, h2 {
      color: #c33;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    .status-paid {
      color: green;
      font-weight: bold;
    }

    .status-pending {
      color: red;
      font-weight: bold;
    }

    .receipt-btn {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }

    .receipt-btn:hover {
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
        <li><a href="my-documents.php">My Documents</a></li>
        <li class="active"><a href="my-payments.php">Payments</a></li>
        <li><a href="notification-user.php">Notification</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>
    <main class="main-content">
    <h1>Payment Status</h1>

    <?php if ($payment): ?>
      <table>
        <tr>
          <th>Application No</th>
          <td><?= htmlspecialchars($application_no) ?></td>
        </tr>
        <tr>
          <th>Payment Mode</th>
          <td><?= htmlspecialchars(ucfirst($payment['payment_mode'])) ?></td>
        </tr>
        <tr>
          <th>Transaction ID</th>
          <td><?= htmlspecialchars($payment['transaction_id'] ?? 'N/A') ?></td>
        </tr>
        <tr>
          <th>Status</th>
          <td class="<?= $payment['payment_status'] === 'paid' ? 'status-paid' : 'status-pending' ?>">
            <?= strtoupper($payment['payment_status']) ?>
          </td>
        </tr>
        <tr>
          <th>Paid At</th>
          <td><?= $payment['paid_at'] ?? 'Not Available' ?></td>
        </tr>
      </table>

      <?php if ($payment['payment_status'] === 'paid'): ?>
        <a href="download-receipt.php" class="receipt-btn" target="_blank">Download Receipt</a>
      <?php endif; ?>
    <?php else: ?>
      <p>No payment record found.</p>
    <?php endif; ?>
  </main>
</div>
</body>
</html>