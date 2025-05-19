<?php
$conn = new mysqli("localhost", "root", "", "products_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products with suppliers
$products = $conn->query("SELECT id, supplier, product_name, quantity FROM products ORDER BY supplier ASC, product_name ASC");

// Handle update stock quantity for a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = intval($_POST['product_id']);
    $new_quantity = intval($_POST['stock_quantity']);

    // Update quantity for the specific product
    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $product_id);
    $stmt->execute();

    header("Location: supplier.php");
    exit();
}

// Handle delete product by id
if (isset($_GET['delete_product'])) {
    $product_id = intval($_GET['delete_product']);

    // Delete the specific product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    header("Location: supplier.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Suppliers & Products</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2c2c2c;
            color: white;
            padding: 20px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #444;
            border-radius: 12px;
            overflow: hidden;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #666;
        }
        th {
            background-color: #111;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover {
            background-color: #555;
        }
        input[type="number"] {
            width: 80px;
            padding: 6px 8px;
            border-radius: 12px;
            border: 1px solid #777;
            background-color: #555;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        button {
            background-color: #27ae60;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        button:hover {
            background-color: #219150;
        }
        .btn-delete {
            background-color: #c0392b;
        }
        .btn-delete:hover {
            background-color: #a93226;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .back-link {
            display: block;
            width: 90%;
            margin: 20px auto;
            text-align: center;
            color: #bbb;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .back-link:hover {
            color: white;
        }
    </style>
</head>
<body>

<h1>Supplier and Product Stock Management</h1>

<table>
    <thead>
        <tr>
            <th>Supplier - Product Name</th>
            <th>Current Stock</th>
            <th>Update Stock</th>
            <th>Delete Product</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($product = $products->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($product['supplier'] . " - " . $product['product_name']) ?></td>
            <td><?= $product['quantity'] ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="number" name="stock_quantity" min="0" required>
                    <button type="submit" name="update_stock">Update</button>
                </form>
            </td>
            <td>
                <a href="supplier.php?delete_product=<?= $product['id'] ?>" onclick="return confirm('Delete this product?');">
                    <button class="btn-delete">Delete</button>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<a href="index.php" class="back-link">&larr; Back to Product Management</a>

</body>
</html>
