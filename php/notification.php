<!-- notification.php -->
<?php
$conn = new mysqli("localhost", "root", "", "kle_users");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST['title'];
  $message = $_POST['message'];
  $application_no = $_POST['application_no'] ?? null;

  // Set to NULL if not specified (for global message)
  $application_no = empty($application_no) ? null : $conn->real_escape_string($application_no);
  $title = $conn->real_escape_string($title);
  $message = $conn->real_escape_string($message);

  $query = "INSERT INTO notifications (application_no, title, message) VALUES ";
  $query .= is_null($application_no)
            ? "(NULL, '$title', '$message')"
            : "('$application_no', '$title', '$message')";
  $conn->query($query);

  echo "<script>alert('Notification added successfully'); window.location.href='notification.php';</script>";
}


?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Notification</title>
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
    input, textarea, button { width: 100%; margin-top: 10px; padding: 10px; }
    button { background-color: #ea6a2e; color: white; border: none; }
  </style>
</head>
<body>
<div class="sidebar">
  <h2>Add New Notification</h2>
  <ul>
  <li><a href="admin_dashboard.php">Home</a></li>
  <li><a href="manage_applications.php">Applications</a></li>
 <li><a href="applications_overview.php">Application Overview</a></li>
 <li class="active"><a href="notification.php">Notification</a>
  <li><a href="signin-admin.php">Logout</a></li>
</ul>
</div>

<div class="main">
  <div class="header">
    <h1>Admin Dashboard</h1>
    <p>KLE Society's College of Computer Applications, Dharwad</p>
  </div>

  <form method="POST">
    <input type="text" name="title" placeholder="Title" required>

    <select name="application_no">
  <option value="">Send to All Users</option>
  <?php
    $users = $conn->query("SELECT application_no FROM users WHERE application_no IS NOT NULL");
    while($user = $users->fetch_assoc()):
  ?>
    <option value="<?= $user['application_no'] ?>"><?= $user['application_no'] ?></option>
  <?php endwhile; ?>
</select>

    <textarea name="message" placeholder="Message" rows="5" required></textarea>
    <button type="submit">Post Notification</button>
  </form>
</body>
</html>
