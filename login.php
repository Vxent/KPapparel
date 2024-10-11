<?php
session_start(); // Start the session

// Include your database connection file
require 'db_connection.php'; // Adjust the path as needed

// Initialize an error message variable
$error = '';

// Check for success message
$successMessage = isset($_SESSION['message']) ? $_SESSION['message'] : '';

// Unset the message after displaying it
if (isset($_SESSION['message'])) {
    unset($_SESSION['message']);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the form submission
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute a query to find the user
    $stmt = $db->prepare("SELECT id, password, role FROM users WHERE email = ?"); // Include role
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $user['id']; // Set session variable
            $_SESSION['user_role'] = $user['role']; // Set user role
            
            // Redirect based on user role
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: adminDashboard.php'); // Redirect to admin dashboard
            } else {
                // Use the redirect parameter if available
                $redirect_url = $_GET['redirect'] ?? 'index.php'; // Default to homepage if no redirect is set
                header("Location: $redirect_url");
            }
            exit();
        } else {
            // Handle invalid password
            $error = "Invalid email or password.";
        }
    } else {
        // Handle user not found
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Your existing CSS styles here */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #1d1f27;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        nav {
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        nav .logo {
            margin: 0 50px;
            text-align: center;
        }

        nav .logo img {
            height: 40px;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ff6b3a;
        }
        .nav-left{
            text-align: center;
        }

        .container {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px); /* This adds the blur effect */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 400px;
            margin-top: 100px; /* Add margin to avoid overlap with the navbar */
        }

        h1 {
            margin-bottom: 30px;
            color: #ff6b3a;
            font-size: 24px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            text-align: left;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            background: black;
            color: #fff;
        }

        .password-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .password-container input[type="password"] {
            flex: 1;
            margin: 0;
        }

        .toggle-password {
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            margin-left: 10px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            margin-bottom: 30px;
            background: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #ff7d4a;
        }

        a {
            color: #ff6b3a;
            text-decoration: underline;
        }

        a:hover {
            text-decoration: none;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body style="background-image: url('http://www.pixelstalk.net/wp-content/uploads/2016/10/Black-and-Orange-Background-Full-HD.jpg'); background-size: cover; background-repeat: no-repeat;">

    <nav>
        <ul class="nav-left">
            <li><a href="#">Sports</a></li>
            <li><a href="#">Services</a></li>
        </ul>
        <div class="logo">
            <img src="images/logo1.png" alt="Logo">
        </div>
        <ul class="nav-right">
            <li><a href="#">Contacts</a></li>
            <li><a href="#">About</a></li>
        </ul>
    </nav>

    <div class="container">
        <img src="images/logo.png" alt="" width="200px" style="padding-bottom: 10px;">
      
        <form action="" method="POST">
            <label for="email">Your Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <label for="password">Your Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">Show</button>
            </div>
            <button type="submit">LOG IN</button>
        </form>

        <a onclick="window.location.href='registration.php'"><p>REGISTER</p></a>

        <?php if ($successMessage): ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var toggleButton = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>
