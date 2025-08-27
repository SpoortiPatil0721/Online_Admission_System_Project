<?php
session_start();
if (!isset($_SESSION['application_no'])) {
    die("Session expired. Please log in again.");
}

$application_no = $_SESSION['application_no'];

$host = "localhost";
$db = "kle_users";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM payment_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Payment record not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment Receipt</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; }
    h1 { text-align: center; }
    .info { margin-top: 20px; }
    .info p { line-height: 1.8; }
    .print-btn {
      text-align: center;
      margin-top: 20px;
    }
    button {
      padding: 10px 15px;
      background: #2ecc71;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background: #27ae60;
    }
  </style>
</head>
<body>

  <h1>Payment Receipt</h1>
  <hr/>

  <div class="info">
    <p><strong>Application No:</strong> <?= htmlspecialchars($application_no) ?></p>
    <p><strong>Payment Mode:</strong> <?= htmlspecialchars($data['payment_mode']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($data['payment_status']) ?></p>
    <p><strong>Paid At:</strong> <?= htmlspecialchars($data['paid_at']) ?></p>
    <p><strong>Generated At:</strong> <?= date("Y-m-d H:i:s") ?></p>
  </div>

  <div class="print-btn">
    <button onclick="window.print()">Print / Save as PDF</button>
  </div>

</body>
</html>
