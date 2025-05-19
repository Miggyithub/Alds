<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "products_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch only Coca-Cola beverages
$sql = "SELECT * FROM products WHERE category = 'Beverages' AND product_name LIKE '%Coca-Cola%'";
$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coca-Cola Beverages</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            margin: 20px;
            color: #ccc;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .product {
            background: #2a2a2a;
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
            text-align: center;
            color: #ccc;
        }

        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 10px;
            border: 2px solid #444;
        }

        .product h4 {
            margin: 10px 0 6px;
            font-size: 16px;
            color: #ddd;
        }

        .product p {
            font-weight: bold;
            color: #aaa;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Coca-Cola Beverages</h2>

<div class="product-grid">
    <?php if ($products->num_rows > 0): ?>
        <?php while ($row = $products->fetch_assoc()): ?>
            <div class="product">
                <img src="uploads/<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                <p>₱<?= number_format($row['price'], 2) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No Coca-Cola beverages found.</p>
    <?php endif; ?>
</div>

</body>
</html>
