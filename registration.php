<?php
session_start(); // Start session to access messages
include 'db_connection.php'; // Include your database connection

$successMessage = ""; // Initialize success message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact_no = $db->real_escape_string($_POST['contact_no']);
    $address = $db->real_escape_string($_POST['address']);

    // Check if email is already registered
    $result = $db->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, contact_no, address) VALUES ('$username', '$email', '$hashed_password', '$contact_no', '$address')";
        
        if ($db->query($sql) === TRUE) {
            $userId = $db->insert_id;
            $_SESSION['username'] = $username; // Store username in session
            $db->query("INSERT INTO audit_log (user_id, action) VALUES ('$userId', 'User registered')");
            $successMessage = "You have successfully registered!"; // Set success message
        } else {
            $_SESSION['error'] = "Error: " . $db->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #1d1f27;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .container {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 400px;
        }

        h1 {
            margin-bottom: 30px;
            color: #ff6b3a;
            font-size: 24px;
            font-weight: bold;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .error {
            background: #e74c3c;
        }

        .success {
            background: #2ecc71;
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

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            margin-bottom: 20px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            background: black;
            color: #fff;
        }

        button {
            padding: 10px;
            background: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #ff7d4a;
        }

        .link-button {
            background: none;
            border: none;
            color: #ff6b3a;
            text-decoration: none;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            margin-top: 10px;
        }

        .link-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="background-image: url('http://www.pixelstalk.net/wp-content/uploads/2016/10/Black-and-Orange-Background-Full-HD.jpg'); background-size: cover; background-repeat: no-repeat;">
    <div class="container">
        <h1>Register</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="message error"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); // Clear the error after displaying ?>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <p class="message success bg-yellow-500"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <form action="registration.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <label for="contact_no">Contact No:</label>
            <input type="text" id="contact_no" name="contact_no">
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
            <button type="submit">REGISTER</button>
            <a href="login.php" class="link-button">Back to Login</a>
        </form>
    </div>
</body>
</html>
