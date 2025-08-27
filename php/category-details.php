<?php
session_start();

$nationality = $category = $gender = $dob = $religion = $physically_handicapped = "";

if (!isset($_SESSION['application_no'])) {
    die("Application number not found.");
}
$application_no = $_SESSION['application_no'];

$conn = new mysqli('localhost', 'root', '', 'kle_users');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if already submitted and load data
$check = $conn->prepare("SELECT * FROM category_details WHERE application_no = ?");
$check->bind_param("s", $application_no);
$check->execute();
$result = $check->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nationality = $row['nationality'];
    $category = $row['category'];
    $gender = $row['gender'];
    $dob = $row['dob'];
    $religion = $row['religion'];
    $physically_handicapped = $row['physically_handicapped'];
    $formAlreadySubmitted = true;
} else {
    $formAlreadySubmitted = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$formAlreadySubmitted) {
        $nationality = $_POST['nationality'] ?? '';
        $category = $_POST['category'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $religion = $_POST['religion'] ?? '';
        $physically_handicapped = $_POST['physically_handicapped'] ?? '';

        if (!$application_no || !$nationality || !$category || !$gender || !$dob || !$religion || !$physically_handicapped) {
            die("All fields are required.");
        }

        $stmt = $conn->prepare("INSERT INTO category_details (application_no, nationality, category, gender, dob, religion, physically_handicapped) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $application_no, $nationality, $category, $gender, $dob, $religion, $physically_handicapped);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Redirect always
    header("Location: program-details.html");
    exit();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Category Details</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f5f5f5;
    }

    header {
      background-color: #ea6a2e;
      padding: 15px;
      color: white;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 22px;
    }

    header small {
      font-size: 14px;
    }

    .container {
      padding: 30px;
      background-color: white;
      max-width: 1000px;
      margin: 30px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .nav {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 25px;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
      margin-bottom: 25px;
    }

    .step {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-weight: 500;
    }

    .step.active {
      color: #ea6a2e;
      font-weight: bold;
    }

    .step.completed .circle {
      background-color: green;
      border-color: green;
    }

    .circle {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background-color: transparent;
      border: 2px solid #ccc;
    }

    .step.active .circle {
      background-color: #ea6a2e;
      border-color: #ea6a2e;
    }

    .note {
      font-size: 0.9em;
      margin-bottom: 20px;
    }

    .note a {
      color: #ea6a2e;
      text-decoration: none;
      font-weight: bold;
      margin-right: 20px;
    }

    .form-section h2 {
      color: #c33;
      margin-bottom: 20px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 5px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 20px;
    }

    .form-group {
      flex: 1;
      min-width: 250px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }

    .required {
      color: red;
    }

    input, select {
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .btn {
      padding: 10px 25px;
      font-size: 16px;
      cursor: pointer;
      border: none;
      border-radius: 4px;
      color: white;
      background-color: #ea6a2e;
    }

    .btn:hover {
      background-color: #c2541c;
    }
  </style>
</head>
<body>

<header>
  <h1>KLE Society's<br><small>College of Computer Application, Dharwad</small></h1>
</header>

<div class="container">

  <!-- Navigation -->
  <div class="nav">
    <div class="step completed"><div class="circle"></div> Important Instructions</div>
    <div class="step active"><div class="circle"></div> Category Details</div>
    <div class="step"><div class="circle"></div> Program Details</div>
    <div class="step"><div class="circle"></div> Personal Details</div>
    <div class="step"><div class="circle"></div> Guardian and Address Details</div>
    <div class="step"><div class="circle"></div> Declaration</div>
    <div class="step"><div class="circle"></div> Payments</div>
  </div>

  <!-- Links -->
  <div class="note">
    <a href="dashboard.php">Back to Dashboard</a>
    <a href="form.html">Check your eligibility</a>
  </div>

  <!-- Category Details Form -->
  <div class="form-section">
    <h2>Category Details</h2>

    <form action="category-details.php" method="POST">
      <!-- Hidden field for application_no -->
     
<input type="hidden" name="application_no" value="<?php echo $_SESSION['application_no'] ?? ''; ?>">


      <div class="form-row">
        <div class="form-group">
          <label for="nationality"><span class="required">*</span> Nationality</label>
          <select id="nationality" name="nationality" required>
            <option value="">Select</option>
            <option value="Indian" selected>Indian</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="category"><span class="required">*</span> Category</label>
          <select id="category" name="category" required>
            <option value="">Select</option>
            <option value="General">General</option>
            <option value="SC">SC</option>
            <option value="ST">ST</option>
            <option value="OBC">OBC</option>
            <option value="Others">Others</option>
          </select>
        </div>

        <div class="form-group">
          <label for="gender"><span class="required">*</span> Gender</label>
          <select id="gender" name="gender" required>
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>

      <div class="form-row">
  <div class="form-group">
    <label for="dob"><span class="required">*</span> Date of Birth</label>
    <input type="date" id="dob" name="dob" max="<?= date('Y-m-d'); ?>" required />
  </div>
</div>


        <div class="form-group">
          <label for="religion"><span class="required">*</span> Religion</label>
          <select id="religion" name="religion" required>
            <option value="">Select</option>
            <option value="Hindu">Hindu</option>
            <option value="Muslim">Muslim</option>
            <option value="Christian">Christian</option>
            <option value="Jain">Jain</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="physically_handicapped"><span class="required">*</span> Physically Handicapped</label>
          <select id="physically_handicapped" name="physically_handicapped" required>
            <option value="">Select</option>
            <option value="No">No</option>
            <option value="Yes">Yes</option>
          </select>
        </div>
      </div>

      <!-- Navigation Buttons -->
      <div class="buttons">
        <button type="button" class="btn" onclick="window.history.back()">Previous</button>
        <button type="submit" class="btn">Next</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
