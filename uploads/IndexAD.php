<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "products_db");

// Get category from query string (default to all)
$category = isset($_GET['category']) ? strtolower($_GET['category']) : 'all';

// Prepare SQL query with category filter
if ($category === 'all') {
    $stmt = $conn->prepare("SELECT * FROM products");
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE LOWER(category) = ?");
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 20px;
            display: flex;
            gap: 20px;
            color: #ccc;
        }
        .logout-bar {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #1e1e1e;
            padding: 10px 20px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #aaa;
            z-index: 10;
        }
        .logout-btn {
            background-color: #333;
            color: #ccc;
            padding: 6px 14px;
            border-radius: 12px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        .logout-btn:hover {
            background-color: #555;
            color: #fff;
        }
        .left-section {
            flex: 7;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .right-section {
            flex: 3;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.05);
            height: fit-content;
            color: #ccc;
        }
        .card {
            background-color: #1e1e1e;
            padding: 24px;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.05);
            margin-bottom: 30px;
        }
        .card h2,
        .right-section h2 {
            color: #bbb;
            margin-bottom: 20px;
        }
        .category-filter {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .category-link {
            background-color: #333;
            color: #ccc;
            padding: 10px 18px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
            display: inline-block;
        }
        .category-link:hover,
        .category-link.active {
            background-color: #555;
            color: #eee;
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
            transition: transform 0.2s;
            color: #ccc;
        }
        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
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
        .product input[type="number"] {
            width: 60px;
            padding: 6px;
            border: 1px solid #555;
            border-radius: 10px;
            background-color: #222;
            color: #ccc;
            margin-bottom: 8px;
        }
        .product input[type="number"]::-webkit-inner-spin-button,
        .product input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .btn-add-cart,
        .btn-reduce-cart,
        .btn-remove-cart {
            padding: 8px 14px;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            font-size: 14px;
        }
        .btn-add-cart {
            background-color: #444;
            color: #ccc;
        }
        .btn-add-cart:hover {
            background-color: #666;
            color: #fff;
        }
        .btn-reduce-cart {
            background-color: #666;
            color: #ccc;
        }
        .btn-reduce-cart:hover {
            background-color: #888;
            color: #fff;
        }
        .btn-remove-cart {
            background-color: #555;
            color: #bbb;
        }
        .btn-remove-cart:hover {
            background-color: #777;
            color: #eee;
        }
        .btn-checkout {
            background-color: #444;
            color: #ccc;
            padding: 12px 20px;
            font-weight: bold;
            border: none;
            border-radius: 16px;
            width: 100%;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            font-size: 16px;
        }
        .btn-checkout:hover {
            background-color: #666;
            color: #fff;
        }
        #cart-list li {
            margin-bottom: 8px;
            font-size: 14px;
            color: #bbb;
        }
        hr {
            border: 0;
            height: 1px;
            background: #333;
            margin: 20px 0;
        }
        #total {
            font-size: 18px;
            color: #eee;
        }
    </style>
</head>
<body>

<div class="logout-bar">
    <span>Welcome<?= isset($_SESSION['first_name']) ? ' (' . htmlspecialchars($_SESSION['first_name']) . ')' : '' ?>!</span>
    <a href="?logout=true" class="logout-btn">Logout</a>
</div>

<!-- Back to Dashboard button -->
<div style="position: absolute; top: 20px; left: 20px;">
    <a href="dashboard.php" class="logout-btn" style="background-color: #333;">← </a>
</div>

<div class="left-section">
    <div class="card">
        <h2>Available Products</h2>

        <div class="category-filter">
            <a href="?category=all" class="category-link <?= $category === 'all' ? 'active' : '' ?>">All Items</a>
            <a href="?category=beverage" class="category-link <?= $category === 'beverage' ? 'active' : '' ?>">Beverage</a>
            <a href="?category=bread" class="category-link <?= $category === 'bread' ? 'active' : '' ?>">Bread</a>
            <a href="?category=snack" class="category-link <?= $category === 'snack' ? 'active' : '' ?>">Snack</a>
        </div>

        <div class="product-grid" id="product-grid">
            <?php while($row = $products->fetch_assoc()): ?>
            <div class="product" data-category="<?= strtolower($row['category'] ?? 'items') ?>">
                <img src="uploads/<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                <p>₱<?= number_format($row['price'], 2) ?></p>
                <p style="color:#aaa; font-size: 14px;">Stock: <?= intval($row['quantity']) ?></p>
                <input type="number" min="1" value="1" id="qty-<?= $row['id'] ?>">
                <br>
                <div style="display: flex; justify-content: center; gap: 8px; font-size: 14px;">
                    <button class="btn-add-cart" onclick="addToCart(<?= $row['id'] ?>, '<?= addslashes($row['product_name']) ?>', <?= $row['price'] ?>)">Add</button>
                    <button class="btn-reduce-cart" onclick="reduceFromCart(<?= $row['id'] ?>)">-</button>
                    <button class="btn-remove-cart" onclick="removeFromCart(<?= $row['id'] ?>)">Remove</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="right-section">
    <h2>Receipt</h2>
    <ul id="cart-list" style="list-style: none; padding-left: 0;"></ul>
    <hr>
    <p><strong>Total: ₱<span id="total">0.00</span></strong></p>
    <button onclick="checkout()" class="btn-checkout">Checkout</button>
</div>

<script>
    let cartItems = {};
    let total = 0;

    function addToCart(id, name, price) {
        const qtyInput = document.getElementById('qty-' + id);
        let qty = parseInt(qtyInput.value);
        if (isNaN(qty) || qty < 1) {
            alert('Please enter a valid quantity.');
            return;
        }
        if (cartItems[id]) {
            cartItems[id].qty += qty;
        } else {
            cartItems[id] = {name: name, price: price, qty: qty};
        }
        updateCart();
    }

    function reduceFromCart(id) {
        if (cartItems[id]) {
            cartItems[id].qty -= 1;
            if (cartItems[id].qty <= 0) {
                delete cartItems[id];
            }
            updateCart();
        }
    }

    function removeFromCart(id) {
        if (cartItems[id]) {
            delete cartItems[id];
            updateCart();
        }
    }

    function updateCart() {
        const cartList = document.getElementById('cart-list');
        cartList.innerHTML = '';
        total = 0;
        for (const id in cartItems) {
            let item = cartItems[id];
            let li = document.createElement('li');
            li.textContent = `${item.name} x ${item.qty} = ₱${(item.price * item.qty).toFixed(2)}`;
            cartList.appendChild(li);
            total += item.price * item.qty;
        }
        document.getElementById('total').textContent = total.toFixed(2);
    }

    function checkout() {
        if (Object.keys(cartItems).length === 0) {
            alert('Your cart is empty.');
            return;
        }
        alert('Checkout successful! Total: ₱' + total.toFixed(2));
        cartItems = {};
        updateCart();
    }
</script>

</body>
</html>
