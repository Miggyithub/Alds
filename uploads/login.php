<?php
session_start();

$conn = new mysqli("localhost", "root", "", "products_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: index.php");
            } elseif ($user['role'] === 'user') {
                header("Location: index 2.php");
            } else {
                header("Location: login.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: #121212;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
        }

        .left-section {
            flex: 0 0 70%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #121212;
        }

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            max-width: 60%;
            max-height: 60%;
            object-fit: contain;
            filter: grayscale(100%) contrast(110%);
        }

        .right-section {
            flex: 0 0 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.4);
        }

        .login-container {
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 360px;
            color: #e0e0e0;
        }

        .login-container h2 {
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            background-color: #2b2b2b;
            color: #f5f5f5;
        }

        input:focus {
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
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a {
            color: #66b2ff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="left-section">
        <div class="logo-container">
            <img src="Logo.png" alt="Logo">
        </div>
    </div>

    <div class="right-section">
        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Sign In</button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </div>
    </div>
</body>
</html>
