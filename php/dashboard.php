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

// Get application number from users table
$sql1 = "SELECT application_no FROM users WHERE username = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $username);
$stmt1->execute();
$res1 = $stmt1->get_result();
$row = $res1->fetch_assoc();

$application_no = $row['application_no'] ?? '';
$_SESSION['application_no'] = $application_no;

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

// 6. Check payments table
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
  

$stmt1->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="style3.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: #f4f4f4;
      min-height: 100vh;
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
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
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

    .dashboard-header {
      background: url('dahboard.jpg') no-repeat center center / cover;
      color: white;
      padding: 40px;
      border-radius: 10px;
      margin-bottom: 30px;
      padding: 30px 20px 10px;
    }

    .application-section {
     flex: 1;
  max-width: 1250px;
  padding: 30px 20px;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.application-section h2 {
  text-align: center;
  color: #781f1f;
  margin-bottom: 30px;
  font-size: 24px;
}

.application-boxes {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
}

.application-box {
  flex: 1 1 220px;
  max-width: 260px;
  height: 140px;
  background-color: #f3f3f3;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: left;
  text-align: center;
  font-size: 16px;
  font-weight: 500;
  color: #333;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.application-box:hover {
  transform: translateY(-5px);
  background-color: #eaeaea;
}
    .tabs {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .tabs button {
      padding: 10px 20px;
      margin-right: 10px;
      border: none;
      background: #bdc3c7;
      border-radius: 5px 5px 0 0;
      font-weight: bold;
      cursor: pointer;
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
      margin-bottom: 20px;
    }

    .application-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 5px;
    }

    .application-id {
      font-size: 14px;
      color: #555;
      margin-bottom: 10px;
    }

    .application-details {
      font-size: 14px;
      margin-bottom: 10px;
    }

    .progress-container {
      background-color: #ddd;
      border-radius: 20px;
      overflow: hidden;
      margin-bottom: 10px;
    }

    .progress-bar {
      height: 20px;
      background-color: #27ae60;
      text-align: center;
      color: white;
      font-size: 12px;
      line-height: 20px;
    }

    .continue-button {
      padding: 10px 20px;
      background-color: #781f1f;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    a.continue-button{
      padding: 10px 20px;
      background-color: #781f1f;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .countdown-container {
      flex: 1;
      display: flex;
  justify-content: space-between;
  align-items: center ;
  gap: 20px;
  padding: 30px 20px;
  background-color: #f8f9fa;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  min-width: 700px;
}
.countdown-box:hover {
  transform: scale(1.05);
}

.countdown-box {
  flex: 1;
  min-width: 100px;
  max-width: 100px;
  background-color: #ffffff;
  padding: 10px 15px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease-in-out;
}

.countdown-box span {
  display: block;
  font-size: 28px;
  font-weight: 700;
  color: #781f1f;
}

.countdown-box label {
  font-size: 14px;
  color: #555;
  margin-top: 15px;
}

    

    @media screen and (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>KLEBCADWD</h2>
      <ul>
        <li class="active"><a href="dashboard.php">Home</a></li>
        <li><a href="all-applications.php">All Applications</a></li>
        <li><a href="my-applications.php">My Applications</a></li>
        <li><a href="my-documents.php">My Documents</a></li>
        <li><a href="my-payments.php">Payments</a></li>
        <li><a href="notification-user.php">Notification</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <section class="dashboard-header">
        <h1>Start New Applications</h1>
        <p>Hi <?php echo $_SESSION['username']; ?>! Welcome to KLEBCADWD Applicant Portal</p>
        <p>Your Application Number: <?php echo $_SESSION['application_no']; ?></p>
      </section>

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
  <?php if ($progressPercentage == 100): ?>
    <div class="application-card" data-progress="<?= $progressPercentage ?>">
      <div class="application-title">MCA</div>
      <div class="application-id"><?= $_SESSION['application_no']; ?></div>
      <div class="application-details">✅ Your application has been successfully submitted!</div>
      
      <div class="progress-container">
        <div class="progress-bar" style="width: <?= $progressPercentage ?>%;"><?= $progressPercentage ?>%</div>
      </div>

      <a class="continue-button" href="<?= $nextPage ?>?application_no=<?= $application_no ?>">
        View / Download
      </a>
    </div>
  <?php else: ?>
    <p>No completed applications.</p>
  <?php endif; ?>
</div>


      </section>

      <section class="countdown-container">
        <h2>Application Deadline Countdown</h2>
        <div class="countdown-box">
    <span id="days">--</span>
    <label>Days</label>
  </div>
  <div class="countdown-box">
    <span id="hours">--</span>
    <label>Hours</label>
  </div>
  <div class="countdown-box">
    <span id="minutes">--</span>
    <label>Minutes</label>
  </div>
  <div class="countdown-box">
    <span id="seconds">--</span>
    <label>Seconds</label>
  </div>
      </section>
    </main>
  </div>

  <script>
    const deadline = new Date("2025-06-28T23:59:59").getTime();

    function updateCountdown() {
      const now = new Date().getTime();
      const diff = deadline - now;

      if (diff <= 0) {
        document.getElementById("countdown-timer").innerHTML = "The application deadline has passed.";
        clearInterval(timerInterval);
        return;
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      document.getElementById("days").textContent = days;
      document.getElementById("hours").textContent = hours;
      document.getElementById("minutes").textContent = minutes;
      document.getElementById("seconds").textContent = seconds;
    }

    const timerInterval = setInterval(updateCountdown, 1000);
    updateCountdown();

    
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
</body>
</html>
