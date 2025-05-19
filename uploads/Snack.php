<?php
$conn = new mysqli("localhost", "root", "", "products_db");

// Filter products by category 'Snack' only
$category = 'Snack'; 
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Snack Products</title>
    <style>
        /* You can reuse all your existing CSS here or link a common stylesheet */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2c2c2c;
            color: white;
            margin: 0;
            padding: 0;
        }
        /* ... (rest of your CSS) ... */
    </style>
</head>
<body>

<div class="top-bar">
    <a href="product_management.php" class="btn-dashboard">Back to Product Management</a>
</div>

<div class="main">
    <div class="left">
        <div class="card">
            <h2>Snack Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>ITEM NO.</th>
                        <th>PRODUCT IMAGE</th>
                        <th>SUPPLIER</th>
                        <th>PRODUCT NAME</th>
                        <th>PRICE</th>
                        <th>CATEGORY</th>
                        <th>QUANTITY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><img src="uploads/<?= $row['product_image'] ?>" alt="<?= htmlspecialchars($row['product_name']) ?>"></td>
                        <td><?= htmlspecialchars($row['supplier']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= intval($row['quantity']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
