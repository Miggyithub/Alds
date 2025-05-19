<?php
session_start();
echo "<h1>Welcome Admin!</h1>";
echo "<p>You are logged in as: " . $_SESSION['role'] . "</p>";
header("Location: index.php");
?>
