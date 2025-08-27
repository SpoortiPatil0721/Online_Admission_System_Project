<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: signin-admin.php");
    exit();
}



$conn = new mysqli("localhost", "root", "", "kle_users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total users
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Users who started application (have application_no)
$withAppNo = $conn->query("SELECT COUNT(*) AS total FROM users WHERE application_no IS NOT NULL")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) AS total FROM users WHERE application_status = 'Pending'")->fetch_assoc()['total'];
$verified = $conn->query("SELECT COUNT(*) AS total FROM users WHERE application_status = 'Verified'")->fetch_assoc()['total'];
$rejected = $conn->query("SELECT COUNT(*) AS total FROM users WHERE application_status = 'Rejected'")->fetch_assoc()['total'];
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple Admin Dashboard</title>
  <style>
  :root {
    --accent: #ea6a2e;
    --accent-dark: #d45c22;
    --bg-light: #f0f2f5;
    --bg-card: #fff;
    --text-dark: #333;
    --text-muted: #777;
    --shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
  }

  body {
    display: flex;
    background: var(--bg-light);
  }

  .sidebar {
    width: 220px;
    background: var(--accent);
    color: white;
    height: 100vh;
    position: fixed;
    padding: 24px 0;
    transition: transform 0.3s ease;
  }

  .sidebar h2 {
    text-align: center;
    margin-bottom: 32px;
    font-size: 22px;
    color : white;
  }

  .sidebar a {
    display: block;
    padding: 12px 22px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.2s;
  }

  .sidebar a:hover {
    background-color: var(--accent-dark);
  }
  .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar li {
      padding: 10px 0;
    }
    .sidebar .active {
      background: #d14545;
      padding: 10px;
      border-radius: 5px;
    }
  .main {
    margin-left: 220px;
    padding: 24px;
    flex: 1;
  }

  .header {
    background: var(--accent);
    color: white;
    padding: 28px 24px;
    border-radius: 10px;
    text-align: center;
    box-shadow: var(--shadow);
  }

  .card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
  }

  .card {
    background: var(--bg-card);
    padding: 24px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: transform 0.2s;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .card h3 {
    font-size: 26px;
    color: var(--accent);
    margin-bottom: 8px;
  }

  .card p {
    color: var(--text-muted);
    font-size: 14px;
  }

  h2 {
    margin: 30px 0 12px;
    color: var(--text-dark);
  }


  table {
    width: 100%;
    border-collapse: collapse;
    background: var(--bg-card);
    box-shadow: var(--shadow);
    border-radius: 8px;
    overflow: hidden;
  }

  thead {
    background: var(--accent);
    color: white;
  }

  th, td {
    padding: 14px 16px;
    border: 1px solid #eee;
    text-align: left;
    font-size: 14px;
  }

  tbody tr:nth-child(even) {
    background: #f9f9f9;
  }

  @media (max-width: 768px) {
    .sidebar {
      transform: translateX(-100%);
    }

    .sidebar.open {
      transform: translateX(0);
    }

    .main {
      margin-left: 0;
      padding: 16px;
    }

    .card-container {
      grid-template-columns: 1fr;
    }
  }
</style>

</head>
<body>

<div class="sidebar">
  <h2>Admin Panel</h2>
  <ul>
  <li class="active"><a href="admin_dashboard.php">Home</a></li>
  <li><a href="manage_applications.php"> All Applications</a></li>
 <li><a href="applications_overview.php">Application Overview</a></li>
 <li><a href="notification.php">Notification</a>
  <li><a href="signin-admin.php">Logout</a></li>
</ul>
</div>

<div class="main">
  <div class="header">
    <h1>Admin Dashboard</h1>
    <p>KLE Society's College of Computer Applications, Dharwad</p>
  </div>

  <div class="card-container">
  <div class="card">
    <h3><?= $totalUsers ?></h3>
    <p>Total Registered Users</p>
  </div>
  <div class="card">
    <h3><?= $withAppNo ?></h3>
    <p>Applications Started</p>
  </div>
  <div class="card">
    <h3><?= $pending ?></h3>
    <p>Pending Applications</p>
  </div>
  <div class="card">
    <h3><?= $verified ?></h3>
    <p>Verified Applications</p>
  </div>
  <div class="card">
    <h3><?= $rejected ?></h3>
    <p>Rejected Applications</p>
  </div>
</div>


<h2 style="margin-bottom: 10px;">Recent Applications</h2>
<table>
  <thead>
    <tr>
      <th>Application No</th>
      <th>Name</th>
      <th>Program</th>
      <th>Status</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $recent = $conn->query("SELECT * FROM users WHERE application_no IS NOT NULL ORDER BY id DESC LIMIT 5");
    while ($row = $recent->fetch_assoc()):
    ?>
      <tr>
        <td><?= htmlspecialchars($row['application_no']) ?></td>
        <td><?= htmlspecialchars($row['fullname']) ?></td>
        <td>BCA</td> <!-- Replace with dynamic program if you store it -->
        <td><?= htmlspecialchars($row['application_status']) ?></td>
        <td><?= isset($row['created_at']) ? date('d-m-Y', strtotime($row['created_at'])) : '-' ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
    </div>
</body>
</html>