<?php
$conn = new mysqli("localhost", "root", "", "products_db");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .left-section {
            width: 70%;
            background-color: #1a1a1a;
            height: 100vh;
            /* Placeholder content or keep empty */
        }

        .right-section {
            width: 30%;
            background-color: #1f1f1f;
            padding: 40px 30px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.3);
            overflow-y: auto;
            color: #ccc;
        }

        h2 {
            text-align: center;
            color: #f1f1f1;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #ccc;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #2a2a2a;
            color: #e0e0e0;
        }

        input[type="file"] {
            padding: 6px;
        }

        img {
            margin-top: 10px;
            border-radius: 6px;
            border: 2px solid #333;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #333;
            color: #eee;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        button:hover {
            background-color: #555;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="left-section">
    <!-- Optional: add branding, sidebar, or leave blank -->
</div>

<div class="right-section">
    <h2>Edit Product</h2>
    <form method="POST" action="update_product.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <label>Supplier</label>
        <input type="text" name="supplier" value="<?= htmlspecialchars($row['supplier']) ?>">

        <label>Product Name</label>
        <input type="text" name="product_name" value="<?= htmlspecialchars($row['product_name']) ?>">

        <label>Price</label>
        <input type="text" name="price" value="<?= htmlspecialchars($row['price']) ?>">

        <label>Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>">

        <label>Quantity</label>
        <input type="number" name="quantity" value="<?= htmlspecialchars($row['quantity']) ?>">

        <label>Current Image</label><br>
        <img src="uploads/<?= htmlspecialchars($row['product_image']) ?>" width="100" alt="<?= htmlspecialchars($row['product_name']) ?>"><br>

        <label>Change Image</label>
        <input type="file" name="product_image">

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
