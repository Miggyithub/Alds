<?php
session_start();
$conn = new mysqli("localhost", "root", "", "products_db");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $raw_password = $_POST['password'];
    $role = $_POST['role'];

    // Server-side email domain validation
    if (!preg_match("/@(gmail\.com|yahoo\.com)$/i", $username)) {
        $error = "Only Gmail or Yahoo email addresses are allowed.";
    } else {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['role'] = $role;
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #121212;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
        }

        .left-section {
            flex: 0 0 70%;
        }

        .right-section {
            flex: 0 0 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.4);
        }

        .register-container {
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 360px;
            color: #e0e0e0;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #f0f0f0;
            font-size: 24px;
            font-weight: bold;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: #cccccc;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            background-color: #2b2b2b;
            color: #f5f5f5;
        }

        input:focus, select:focus {
            border-color: #888;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #666;
        }

        .error {
            text-align: center;
            color: #ff6b6b;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #66b2ff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="left-section">
    <!-- Optional branding or image -->
</div>

<div class="right-section">
    <div class="register-container">
        <h2>Create Account</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" action="">
            <label for="username">Email:</label>
            <input type="email" name="username" id="username" required
                   pattern="^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com)$"
                   title="Only Gmail or Yahoo email addresses are allowed">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</div>

</body>
</html>
