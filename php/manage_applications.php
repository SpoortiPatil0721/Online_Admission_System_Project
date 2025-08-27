<?php
// manage_applications.php
session_start();
$conn = new mysqli("localhost", "root", "", "kle_users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update status if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['application_status'])) {
  $id = (int)$_POST['user_id'];
  $status = $conn->real_escape_string($_POST['application_status']);

  // Update user status
  $conn->query("UPDATE users SET application_status = '$status' WHERE id = $id");

  // Get application_no
  $res = $conn->query("SELECT application_no FROM users WHERE id = $id");
  $row = $res->fetch_assoc();
  $application_no = $row['application_no'];

  // Insert notification
  $title = "Application Status Updated";
  $message = "Your application ($application_no) has been " . strtolower($status) . ".";

  $stmt = $conn->prepare("INSERT INTO notifications (application_no, title, message) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $application_no, $title, $message);
  $stmt->execute();
  $stmt->close();

  header("Location: manage_applications.php");
  exit();
}

$search = $_GET['search'] ?? '';
$search = $conn->real_escape_string($search);
$sql = "
  SELECT u.id,
         u.fullname,
         u.email,
         u.application_no,
         u.application_status,
         p.tenth_marksheet,
         p.twelfth_marksheet
  FROM users u
  LEFT JOIN program_details p
         ON u.application_no = p.application_no
  WHERE u.application_no IS NOT NULL
";


if (!empty($search)) {
    $sql .= " AND (fullname LIKE '%$search%' OR email LIKE '%$search%' OR application_no LIKE '%$search%')";
}

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Applications</title>
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
    table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding: 12px 16px; border: 1px solid #ccc; text-align: left; }
    th { background: #ea6a2e; color: white; }
    select, button { padding: 6px 10px; font-size: 14px; }
    form { display: flex; gap: 10px; align-items: center; }
  </style>
</head>
<body>
<div class="sidebar">
  <h2>Admin Panel</h2>
  <ul>
  <li><a href="admin_dashboard.php">Home</a></li>
  <li class="active"><a href="manage_applications.php">Applications</a></li>
 <li><a href="applications_overview.php">Application Overview</a></li>
 <li><a href="notification.php">Notification</a>
  <li><a href="sigin-admin.php">Logout</a></li>
</ul>
</div>

<div class="main">
  <div class="header">
    <h1>Admin Dashboard</h1>
    <p>KLE Society's College of Computer Applications, Dharwad</p>
  </div>
<br><br>

<h2>Manage Application Status</h2>
<br><br>
<form method="GET" style="margin: 20px 0; display: flex; gap: 10px;">
  <input type="text" name="search" placeholder="Search by Name, Email, or Application No" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="padding: 8px; width: 300px;">
  <button type="submit" style="padding: 8px 16px; background: #ea6a2e; color: white; border: none;">Search</button>
</form>
<br><br>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Email</th>
      <th>Application No</th>
      <th>10th Marks Card</th>
      <th>12th Marks Card</th>
      <th>Application Form</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['fullname']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['application_no']) ?></td>
        <td>
  <?php if (!empty($row['tenth_marksheet'])): ?>
    <a href="<?= htmlspecialchars($row['tenth_marksheet']) ?>" target="_blank">📄 View</a>
  <?php else: ?>
    Not&nbsp;Uploaded
  <?php endif; ?>
</td>

<td>
  <?php if (!empty($row['twelfth_marksheet'])): ?>
    <a href="<?= htmlspecialchars($row['twelfth_marksheet']) ?>" target="_blank">📄 View</a>
  <?php else: ?>
    Not&nbsp;Uploaded
  <?php endif; ?>
</td>


        <td>
  <a href="download-form.php?application_no=<?= urlencode($row['application_no']) ?>" target="_blank">
    📥 View Form
  </a>
</td>

        <td>
          <form method="POST">
            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
            <select name="application_status">
              <option value="Pending" <?= $row['application_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Verified" <?= $row['application_status'] == 'Verified' ? 'selected' : '' ?>>Verified</option>
              <option value="Rejected" <?= $row['application_status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
            <button type="submit">Update</button>
          </form>
        </td>
        <td><?= htmlspecialchars($row['application_status']) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</body>
</html>
