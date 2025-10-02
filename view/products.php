<?php
session_start();
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/Product.php";

use Model\Database;
use Model\Product;

$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);

// MODIFIED: Fetch products differently based on login status
$products = null;
$isLoggedIn = !empty($_SESSION['user_id']);
if ($isLoggedIn) {
    // New method gets products and joins cart quantity
    $products = $productModel->allForUser($_SESSION['user_id']);
} else {
    // Original method for guests
    $products = $productModel->all();
}

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'checkout_success') {
    $msg = 'Checkout successful! Your cart has been cleared.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="site-header">
    <h1>Products</h1>
    <p>Browse our catalog</p>
</header>
<nav class="top-nav">
    <a href="../index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
    <?php if ($isLoggedIn): ?>
        <span class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
        <a href="../controller/UserController.php?action=logout">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>

<main class="container">
    <?php if ($msg): ?><p class="success"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
    
    <h2>Product List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <?php if ($isLoggedIn): ?><th>Qty in Cart</th><?php endif; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= (int)$row['product_id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>$<?= number_format((float)$row['price'], 2) ?></td>
                
                <?php if ($isLoggedIn): ?>
                    <td><?= (int)($row['cart_quantity'] ?? 0) ?></td>
                <?php endif; ?>

                <td class="action">
                    <?php if ($isLoggedIn): ?>
                        <form method="post" action="../controller/CartController.php" class="action" style="margin-right: 8px;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= (int)$row['product_id'] ?>">
                            <input type="number" name="quantity" min="1" value="1" style="width:70px;">
                            <button type="submit" class="btn">Add to Cart</button>
                        </form>

                        <?php if (!empty($row['cart_quantity']) && $row['cart_quantity'] > 0): ?>
                        <form method="post" action="../controller/CartController.php">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="cart_id" value="<?= (int)$row['cart_id'] ?>">
                            <input type="hidden" name="return_url" value="../view/products.php">
                            <button type="submit">Remove</button>
                        </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="note">Login to shop</p>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
</body>
</html>


