<?php
session_start();
if (!isset($_SESSION['application_no'])) {
    die("Application number not set. Please login again.");
}
$application_no = $_GET['application_no'] ?? ($_SESSION['application_no'] ?? null);
if (!$application_no) {
    die("Application number not set. Please login again.");
}

// DB connection
$conn = new mysqli('localhost', 'root', '', 'kle_users');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all details (assumes one record per form section)
// Fetch full name from users table
$user = $conn->query("SELECT fullname FROM users WHERE application_no = '$application_no'")->fetch_assoc();
$category = $conn->query("SELECT * FROM category_details WHERE application_no = '$application_no'")->fetch_assoc();
$program = $conn->query("SELECT * FROM program_details WHERE application_no = '$application_no'")->fetch_assoc();
$documents = $conn->query("SELECT * FROM uploaded_documents WHERE application_no = '$application_no'")->fetch_assoc();
$guardian = $conn->query("SELECT * FROM guardian_address_details WHERE application_no = '$application_no'")->fetch_assoc();
//$declaration = $conn->query("SELECT * FROM declaration_details WHERE application_no = '$application_no'")->fetch_assoc();
$payment = $conn->query("SELECT * FROM payment_details WHERE application_no = '$application_no'")->fetch_assoc();
$photo      = !empty($documents['photo_path'])      ?  $documents['photo_path']      : null;
$signature  = !empty($documents['signature_path'])  ?  $documents['signature_path']  : null;
$applied_at = $payment['paid_at'] ?? '';   // or pull a different timestamp column if you have one

?>
<!DOCTYPE html>
<html>
<head>
    <title>Application Form</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h1 { text-align: center; }
        h2 { background-color:rgb(125, 224, 249); color: white; padding: 10px; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .download-button {
            margin: 30px 0;
            text-align: center;
        }
        button {
            background:rgb(125, 224, 249);
            color: white;
            padding: 10px 25px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background:rgb(125, 224, 249);;
        }
    </style>
</head>
<body>

<h1>KLE Society's College of BBA & BCA, Dharwad</h1>
<h2>Application Form - <?= htmlspecialchars($application_no) ?></h2>
<h3>Applicant Name: <?= htmlspecialchars($user['fullname'] ?? '') ?></h3>
<h2>1. Category Details</h2>
<div style="display:flex; gap:25px; align-items:flex-start;">
  <!-- Table -->
  <table style="flex:2;">
    <tr><th>Nationality</th><td><?= $category['nationality'] ?? '' ?></td></tr>
    <tr><th>Category</th><td><?= $category['category'] ?? '' ?></td></tr>
    <tr><th>Gender</th><td><?= $category['gender'] ?? '' ?></td></tr>
    <tr><th>Date of Birth</th><td><?= $category['dob'] ?? '' ?></td></tr>
    <tr><th>Religion</th><td><?= $category['religion'] ?? '' ?></td></tr>
    <tr><th>Physically Handicapped</th><td><?= $category['physically_handicapped'] ?? '' ?></td></tr>
  </table>

  <!-- Photo box -->
  <?php if ($photo): ?>
    <div style="flex:1; text-align:center;">
      <img src="<?= $photo ?>" alt="Applicant Photo"
           style="width:130px; height:160px; object-fit:cover; border:1px solid #ccc;"/>
      <div style="margin-top:6px; font-size:12px;">Applicant&nbsp;Photo</div>
    </div>
  <?php endif; ?>
</div>


<h2>2. Program Details</h2>
<table>
    <tr><th>Program Level</th><td><?= $program['program_level'] ?? '' ?></td></tr>
    <tr><th>Stream</th><td><?= $program['stream'] ?? '' ?></td></tr>
    <tr><th>Program</th><td><?= $program['program'] ?? '' ?></td></tr>
    <tr><th>Tenth Board</th><td><?= $program['tenth_board'] ?? '' ?></td></tr>
    <tr><th>Tenth School Name</th><td><?= $program['tenth_school'] ?? '' ?></td></tr>
    <tr><th>Tenth Year</th><td><?= $program['tenth_year'] ?? '' ?></td></tr>
    <tr><th>Twelth Board</th><td><?= $program['twelfth_board'] ?? '' ?></td></tr>
    <tr><th>Twelth College Name</th><td><?= $program['twelfth_college'] ?? '' ?></td></tr>
    <tr><th>Twelth Year</th><td><?= $program['twelfth_year'] ?? '' ?></td></tr>
</table>

<!--<h2>3. Personal Details</h2>
<table>
    <tr><th>Full Name</th><td><?= $personal['full_name'] ?? '' ?></td></tr>
    <tr><th>Email</th><td><?= $personal['email'] ?? '' ?></td></tr>
    <tr><th>Phone</th><td><?= $personal['phone'] ?? '' ?></td></tr>
</table>-->

<h2>3. Guardian & Address Details</h2>
<table>
    <tr><th>Father's Name</th><td><?= $guardian['father_name'] ?? '' ?></td></tr>
    <tr><th>Mother's Name</th><td><?= $guardian['mother_name'] ?? '' ?></td></tr>
    <tr><th>Father's Phone.no</th><td><?= $guardian['father_phone'] ?? '' ?></td></tr>
    <tr><th>Mother's Phone.no</th><td><?= $guardian['mother_phone'] ?? '' ?></td></tr>
    <tr><th>Address</th><td><?= $guardian['permanent_address'] ?? '' ?></td></tr>
    <tr><th>City</th><td><?= $guardian['city'] ?? '' ?></td></tr>
    <tr><th>State</th><td><?= $guardian['state'] ?? '' ?></td></tr>
    <tr><th>Pin-Code</th><td><?= $guardian['pin_code'] ?? '' ?></td></tr>
</table>

<!--<h2>5. Declaration</h2>
<table>
    <tr><th>Declaration Date</th><td><?= $declaration['declared_on'] ?? '' ?></td></tr>
</table>-->

<h2>4. Payment Details</h2>
<table>
    <tr><th>Payment Mode</th><td><?= $payment['payment_mode'] ?? '' ?></td></tr>
    <tr><th>Payment Status</th><td><?= $payment['payment_status'] ?? '' ?></td></tr>
    <tr><th>Paid At</th><td><?= $payment['paid_at'] ?? '' ?></td></tr>
</table>

<?php if ($signature): ?>
  <h2>Applicant Signature</h2>
  <div style="text-align:right; margin-right:60px;">
     <img src="<?= $signature ?>" alt="Signature"
          style="width:200px; height:auto; border:1px solid #ccc;"/>
     <div style="font-size:12px; margin-top:4px;">
         Applied&nbsp;on: <?= htmlspecialchars($applied_at) ?>
     </div>
  </div>
<?php endif; ?>


<div class="download-button">
    <button onclick="window.print()">🖨️ Download / Print</button>
</div>

</body>
</html>
