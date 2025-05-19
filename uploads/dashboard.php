<?php
session_start();

// 🔐 SECURE: Allow only logged-in admin users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            background-color: #2c2c2c;
            color: white;
        }

        .sidebar {
            width: 220px;
            background-color: #000;
            height: 100vh;
            padding-top: 30px;
            position: fixed;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #1a1a1a;
        }

        .main-content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .card {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 0;
            color: rgb(255, 255, 255);
            text-transform: uppercase;
            font-weight: 700;
        }

        .icon {
            margin-right: 10px;
        }

        p {
            color: #ddd;
        }

        /* Button styles */
        .btn-check-products {
            margin-top: 20px;
            padding: 10px 20px;
            font-weight: bold;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s;
        }

        .btn-check-products:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <a href="#"><span class="icon">🏠</span>Dashboard</a>
        <a href="index.php"><span class="icon">🛒</span>Products</a>
        <a href="supplier.php"><span class="icon">🤝</span>Supplier</a>
        <a href="logout.php"><span class="icon">↩️</span>Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Use the navigation sidebar to manage products, suppliers, and more.</p>
            <button class="btn-check-products" onclick="window.location.href='indexAD.php'">Check Products</button>
        </div>
    </div>

</body>
</html>
