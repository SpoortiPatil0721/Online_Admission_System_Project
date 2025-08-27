<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: signin.html");
  exit();
}

$host = 'localhost';
$db = 'kle_users';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch full_name and application_no
$sql = "SELECT fullname, application_no FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

$full_name = $userData['fullname'] ?? 'User';
$application_no = $userData['application_no'] ?? 'N/A';

// AFTER setting $_SESSION['application_no']
$completedSteps = 0;

// 1. Check application_form table (category details)
$sql = "SELECT * FROM category_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// 2. Check program_details table
$sql = "SELECT * FROM program_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// 3. Check personal_details table
$sql = "SELECT * FROM uploaded_documents WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// 4. Check guardian_address_details table
$sql = "SELECT * FROM guardian_address_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// 5. Check uploaded_documents table
$sql = "SELECT * FROM uploaded_documents WHERE application_no = ? AND photo_path IS NOT NULL AND signature_path IS NOT NULL AND aadhar_path IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// 6. Check declaration table
//$sql = "SELECT * FROM declaration WHERE application_no = ?";
//$stmt = $conn->prepare($sql);
//$stmt->bind_param("s", $application_no);
//$stmt->execute();
//$result = $stmt->get_result();
//if ($result->num_rows > 0) {
  //  $completedSteps++;
//}
//$stmt->close();

// 7. Check payments table
$sql = "SELECT * FROM payment_details WHERE application_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $application_no);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $completedSteps++;
}
$stmt->close();

// Now calculate % completion
$progressPercentage = round(($completedSteps / 6) * 100);

/* --------------------------------------------------
   Determine where the Continue button should go
   -------------------------------------------------- */
   $stepPages = [
    ['table' => 'category_details',        'page' => 'category-details.php'],
    ['table' => 'program_details',         'page' => 'program-details.php'],
    ['table' => 'guardian_address_details','page' => 'guardian-address-details.php'],
    ['table' => 'personal_details',        'page' => 'personal-details.php'],
    ['table' => 'uploaded_documents',      'page' => 'upload-documents.php', // photo/sign/aadhar
     'extraWhere' => 'AND photo_path IS NOT NULL AND signature_path IS NOT NULL AND aadhar_path IS NOT NULL'
    ],
    ['table' => 'payment_details',         'page' => 'payment.php']
  ];
  
  $nextPage = 'thankyou.html';   // default for 100 %
  
  if ($progressPercentage < 100) {
      foreach ($stepPages as $step) {
          $sql = "SELECT 1 FROM {$step['table']} WHERE application_no = ? "
               . ($step['extraWhere'] ?? '');
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $application_no);
          $stmt->execute();
          $res = $stmt->get_result();
          if ($res->num_rows === 0) {
              $nextPage = $step['page'];
              $stmt->close();
              break;
          }
          $stmt->close();
      }
  }
  





$conn->close();
?>






<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Applications</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #f4f4f4;
    }

    .dashboard-container {
      display: flex;
      flex-direction: row;
      min-height: 100vh;
    }

    .sidebar {
      width: 220px;
      background: #781f1f;
      color: white;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar li {
      padding: 10px 0;
      cursor: pointer;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
    }

    .sidebar a:hover {
      text-decoration: underline;
    }

    .sidebar .active {
      background: #d14545;
      padding: 10px;
      border-radius: 5px;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
    }

    .top-bar {
      text-align: right;
      font-size: 16px;
      margin-bottom: 20px;
      color: #333;
    }

    .application-section {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .tabs {
      display: flex;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .tabs button {
      padding: 10px 20px;
      border: none;
      background: #bdc3c7;
      margin-right: 10px;
      cursor: pointer;
      border-radius: 5px 5px 0 0;
      font-weight: bold;
    }

    .tabs button.active {
      background: #3498db;
      color: white;
    }

    .application-card {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 8px;
      background-color: #f9f9f9;
      margin-bottom: 10px;
    }

    .application-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .application-id {
      color: #555;
      margin-bottom: 10px;
    }

    .application-details {
      margin-bottom: 10px;
      font-size: 14px;
    }

    .progress-container {
      background: #eee;
      border-radius: 20px;
      overflow: hidden;
      height: 20px;
      margin: 10px 0;
    }

    .progress-bar {
      height: 100%;
      background-color: #2ecc71;
      width: 82%;
      text-align: right;
      color: white;
      padding-right: 5px;
      font-size: 12px;
      line-height: 20px;
    }

    .continue-button {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
      float: right;
    }

    @media (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        text-align: center;
      }

      .sidebar a {
        padding: 10px;
      }

      .main-content {
        padding: 15px;
      }

      .tabs {
        flex-direction: column;
      }

      .continue-button {
        width: 100%;
        margin-top: 10px;
        float: none;
      }
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
        <li class="active"><a href="my-applications.php">My Applications</a></li>
        <li><a href="my-documents.php">My Documents</a></li>
        <li><a href="my-payments.php">Payments</a></li>
        <li><a href="notification-user.php">Notification</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <main class="main-content">
    <div class="top-bar">
  <?php echo htmlspecialchars($full_name); ?>
</div>


<section class="application-section" >
        <div class="tabs">
        <button class="tab-button active" data-target="ongoing">Ongoing Application(s)</button>
        <button class="tab-button" data-target="completed">Completed Application(s)</button>
        </div>

        <div id="ongoing" class="application-list">
  <!-- Ongoing applications here -->
  <div class="application-card">
    <div class="application-title">MCA</div>
    <div class="application-id"><?php echo $_SESSION['application_no']; ?></div>
    <div class="application-details">📍 Offered at: KLE Dharwad<br>📅 Last Date: 30-06-2025</div>

    <div class="progress-container">
      <div class="progress-bar" style="width: <?= $progressPercentage ?>%;"><?= $progressPercentage ?>%</div>
    </div>

    <?php if ($progressPercentage < 100): ?>
      <form action="category-details.php" method="GET">
        <input type="hidden" name="application_no" value="<?php echo $application_no; ?>">
        <button class="continue-button" type="submit">Continue</button>
      </form>
    <?php else: ?>
      <button class="continue-button" disabled>Completed</button>
    <?php endif; ?>
  </div>
</div>

<div id="completed" class="application-list" style="display: none;">
  <!-- Example: completed application card -->
  <div class="application-card">
    <div class="application-title">MCA</div>
    <div class="application-id"><?php echo $_SESSION['application_no']; ?></div>
    <div class="application-details">✅ Your application has been successfully submitted!</div>
  </div>
</div>
       <div class="application-card" data-progress="<?= $progressPercentage ?>">
          <div class="application-title">MCA</div>
          <div class="application-id"><?php echo $_SESSION['application_no']; ?></div>
          <div class="application-details">📍 Offered at: KLE Dharwad<br>📅 Last Date: 30-06-2025</div>

          <div class="progress-container">
          <div class="progress-bar" style="width: <?= $progressPercentage ?>%;"><?= $progressPercentage ?>%</div>
          </div>

          <a class="continue-button" href="<?= $nextPage ?> ? $application_no=<?= $application_no ?>">
     <?= $progressPercentage == 100 ? 'View / Download' : 'Continue' ?>
  </a>
        </div>
      </section>

      </div>
    </main>
  </div>
</body>
<script>

/* ---------- Ongoing / Completed toggle ---------- */
const tabBtns = document.querySelectorAll('.tabs button');
const cards   = document.querySelectorAll('.application-card');

function showCards(showCompleted) {
  cards.forEach(card => {
    const done = Number(card.dataset.progress) === 100;
    card.style.display = (showCompleted ? done : !done) ? 'block' : 'none';
  });
}

tabBtns[0].addEventListener('click', () => {
  tabBtns[0].classList.add('active');
  tabBtns[1].classList.remove('active');
  showCards(false);                               // Ongoing
});

tabBtns[1].addEventListener('click', () => {
  tabBtns[1].classList.add('active');
  tabBtns[0].classList.remove('active');
  showCards(true);                                // Completed
});

/* Show ongoing by default */
showCards(false);

const tabButtons = document.querySelectorAll(".tab-button");
  const sections = document.querySelectorAll(".application-list");

  tabButtons.forEach(button => {
    button.addEventListener("click", () => {
      // Remove active class from all buttons
      tabButtons.forEach(btn => btn.classList.remove("active"));

      // Hide all sections
      sections.forEach(section => section.style.display = "none");

      // Show the selected section
      document.getElementById(button.dataset.target).style.display = "block";
      button.classList.add("active");
    });
  });
  </script>
</html>
