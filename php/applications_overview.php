<?php
session_start();   // keep if you have admin auth checks
$conn = new mysqli("localhost", "root", "", "kle_users");
if ($conn->connect_error) die("DB error: " . $conn->connect_error);

/* ----- fetch three buckets ----- */
$statuses = ['Pending','Verified','Rejected'];
$data = [];
foreach ($statuses as $st) {
    $sql = "
      SELECT u.fullname, u.email, u.application_no,
             p.program_level, p.stream
      FROM users u
      LEFT JOIN program_details p ON u.application_no = p.application_no
      WHERE u.application_status = '$st'
      ORDER BY u.id DESC";
    $data[$st] = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Application Overview</title>
<style>
  :root{
    --accent:#ea6a2e;--bg:#f5f5f5;--card:#fff;--shadow:0 2px 6px rgba(0,0,0,.1)
  }
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
  /* main layout */
  .main{margin-left:220px;padding:24px;flex:1}
  h1{margin-bottom:20px;color:var(--accent)}
  .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px}
  .col{background:var(--card);border-radius:10px;box-shadow:var(--shadow);padding:20px;overflow:auto}
  .col h3{margin-bottom:12px;text-align:center}
  table{width:100%;border-collapse:collapse}
  th,td{border:1px solid #eee;padding:8px;font-size:13px;text-align:left}
  th{background:var(--accent);color:#fff;position:sticky;top:0}
</style>
</head>
<body>
<!-- sidebar (reuse links) -->
<div class="sidebar">
  <h2>Admin Panel</h2>
  <ul>
  <li><a href="admin_dashboard.php">Home</a></li>
  <li><a href="manage_applications.php">Applications</a></li>
 <li class="active"><a href="applications_overview.php">Application Overview</a></li>
 <li><a href="notification.php">Notification</a>
  <li><a href="sigin-admin.php">Logout</a></li>
</ul>
</div>
<div class="main">
  <h1>Application Status Overview</h1>
  <div class="grid">
    <?php foreach ($statuses as $st): ?>
      <div class="col">
        <h3><?= $st ?> (<?= $data[$st]->num_rows ?>)</h3>
        <?php if ($data[$st]->num_rows): ?>
          <table>
            <thead>
              <tr>
                <th>App No</th><th>Name</th><th>Program</th><th>Email</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row=$data[$st]->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['application_no']) ?></td>
                  <td><?= htmlspecialchars($row['fullname']) ?></td>
                  <td><?= htmlspecialchars($row['program_level'].' '.$row['stream']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="text-align:center;color:#777;">No <?= strtolower($st) ?> applications.</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
