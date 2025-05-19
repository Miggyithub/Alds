<?php
$conn = new mysqli("localhost", "root", "", "products_db");

$id = $_POST['id'];
$supplier = $_POST['supplier'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];

// Handle image upload
if ($_FILES['product_image']['name'] != '') {
    $image = $_FILES['product_image']['name'];
    $tmp = $_FILES['product_image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/$image");

    $conn->query("UPDATE products SET supplier='$supplier', product_name='$product_name', price='$price', category='$category', quantity='$quantity', product_image='$image' WHERE id=$id");
} else {
    $conn->query("UPDATE products SET supplier='$supplier', product_name='$product_name', price='$price', category='$category', quantity='$quantity' WHERE id=$id");
}

header("Location: index.php");
?>
