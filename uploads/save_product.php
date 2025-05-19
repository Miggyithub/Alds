<?php
$conn = new mysqli("localhost", "root", "", "products_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate & capture input
$supplier = $_POST['supplier'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];

$product_image = "";

if (isset($_FILES['product_photo']) && $_FILES['product_photo']['error'] === UPLOAD_ERR_OK) {
    $img_name = basename($_FILES['product_photo']['name']);
    $target_dir = "uploads/";
    $target_file = $target_dir . time() . "_" . $img_name;

    if (move_uploaded_file($_FILES['product_photo']['tmp_name'], $target_file)) {
        $product_image = basename($target_file);
    } else {
        echo "Image upload failed.";
        exit();
    }
} else {
    echo "No image uploaded or there was an error.";
    exit();
}

// Insert into database
$sql = "INSERT INTO products (supplier, product_name, price, category, quantity, product_image)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsis", $supplier, $product_name, $price, $category, $quantity, $product_image);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error saving product: " . $stmt->error;
}

$conn->close();
?>
