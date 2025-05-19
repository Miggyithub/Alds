<?php
$conn = new mysqli("localhost", "root", "", "products_db");
$products = $conn->query("SELECT * FROM products");

$suppliers = ['Coca Cola', 'Unilab', 'Nestle'];
$categories = ['Beverage', 'Bread', 'Snack'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2c2c2c;
            color: white;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background-color: #000;
            padding: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-dashboard {
            background-color: #444;
            color: #ffffff; 
            padding: 10px 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-dashboard:hover {
            background-color: #555;
        }

        .main {
            display: flex;
            padding: 30px;
            gap: 30px;
        }

        .left {
            width: 70%;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .right {
            width: 30%;
        }

        .card {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            color: white;
        }

        .card h2 {
            color:  #ffffff; /* Changed from yellow to white */
            margin-bottom: 20px;
            font-size: 22px;
            text-transform: uppercase;
        }

        form label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #777;
            background-color: #555;
            color: white;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
            margin-right: 10px;
        }

        .btn-save { background-color: #27ae60; color: white; }
        .btn-save:hover { background-color: #219150; }

        .btn-reset { background-color: #bdc3c7; color: #2c3e50; }
        .btn-reset:hover { background-color: #dfe6e9; }

        .btn-edit { background-color: #2980b9; color: white; }
        .btn-edit:hover { background-color: #21618c; }

        .btn-delete { background-color: #c0392b; color: white; }
        .btn-delete:hover { background-color: #a93226; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #444;
            border-radius: 12px;
            overflow: hidden;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #666;
        }

        th {
            background-color: #111;
            color: #ffffff; /* Changed from yellow to white */
            text-transform: uppercase;
            font-size: 12px;
        }

        tr:hover {
            background-color: #555;
        }

        img {
            border-radius: 6px;
            width: 40px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="dashboard.php" class="btn-dashboard">Go to Dashboard</a>
</div>

<div class="main">

    <!-- LEFT: Form and Product Table -->
    <div class="left">
        <!-- Product Form -->
        <div class="card">
            <h2>Add Product</h2>
            <form action="save_product.php" method="POST" enctype="multipart/form-data">
                <label>Supplier *</label>
                <select name="supplier" required>
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s ?>"><?= $s ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Product Photo *</label>
                <input type="file" name="product_photo" required>

                <label>Product Name *</label>
                <input type="text" name="product_name" required>

                <label>Price *</label>
                <input type="number" name="price" required>

                <label>Category *</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Quantity *</label>
                <input type="number" name="quantity" required>

                <button type="submit" class="btn btn-save">Save</button>
                <button type="reset" class="btn btn-reset">Cancel</button>
            </form>
        </div>

        <!-- Product Table -->
        <div class="card">
            <h2>Product List</h2>
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
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><img src="uploads/<?= $row['product_image'] ?>" alt="<?= $row['product_name'] ?>"></td>
                        <td><?= $row['supplier'] ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['price'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id'] ?>"><button class="btn btn-edit">Edit</button></a>
                            <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">
                                <button class="btn btn-delete">Delete</button>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT: Sidebar (Optional) -->
    <div class="right">
        <div class="card">
            <h2>Quick Info</h2>
            <p>This panel is reserved for summary data, statistics, or any additional controls.</p>
        </div>
    </div>

</div>

</body>
</html>
