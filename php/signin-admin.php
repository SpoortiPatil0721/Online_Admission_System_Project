




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sigin page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message {
          color: red;
          font-size: 14px;
          text-align: center;
          margin-top: 10px;
        }
      </style>
</head>
<body>
    <header>
        <img src="logo.jpg" alt="logo" class="logo">
        <div class="text">
            <h1>KLE SOCIETY'S</h1>
            <h2>COLLEGE OF BBA AND BCA DHARWAD</h2>
        </div>
    </header>

    <nav>
        <ul>
          <li><a href="home.html">Home</a></li>
          <li><a href="about.html">AboutUs</a></li>
          <li><a href="faculty.html">Faculty</a></li>
          <li><a href="contact.html">ContactUs</a></li>
          <li><a class="active" href="signin.html">SignIn</a></li>
          <li><a href="signup.html">SignUp</a></li>
        </ul>
      </nav>

    <div class="login-container">
        <div class="login-box">
          <h2>Login</h2>

          <form action="sigin.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (isset($_GET['error'])): ?>
  <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

           

          </form>
        </div>
      </div>
    
    <footer>
        <div class="corner">
            OUR WEBSITE: <a href="https://klebcadwd.com/" target="_blank">klebcadwd</a>
        </div>
        <div class="cont">
            CONTACT US: +91-9591909429
        </div>
    </footer>

    <script>
  function goTodashboard(event) {
    event.preventDefault(); // Prevent form submission

    // Get form input values
    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;

    // Define admin credentials (example only)
    const adminUsername = "admin";
    const adminPassword = "admin123";

    // Check if entered credentials match
    if (username === adminUsername && password === adminPassword) {
      window.location.href = "admin_dashboard.php";
    } else {
      // Show error message
      showError("Invalid username or password.");
    }
  }

  function showError(message) {
    let errorDiv = document.querySelector('.error-message');
    if (!errorDiv) {
      errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      document.querySelector('.login-box').appendChild(errorDiv);
    }
    errorDiv.textContent = message;
  }
</script>
</body>
</html>