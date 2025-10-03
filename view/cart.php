<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/Cart.php";

use Model\Database;
use Model\Cart;

define('TAX_RATE', 0.05); // 5%
define('SHIPPING_RATE', 0.10); // 10%

$db = new Database();
$conn = $db->getConnection();
$cartModel = new Cart($conn);
$items = $cartModel->items($_SESSION['user_id']);

$subtotal = 0.0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="site-header">
    <h1>Your Cart</h1>
</header>
<nav class="top-nav">
    <a href="../index.php">Home</a>
    <a href="products.php">Products</a>
    <a href="cart.php">Cart</a>
    <span class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
    <a href="../controller/UserController.php?action=logout">Logout</a>
</nav>

<main class="container">
    <?php if ($items->num_rows > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $items->fetch_assoc()):
                $itemTotal = (float)$row['price'] * (int)$row['quantity'];
                $subtotal += $itemTotal;
            ?>
            <tr>
                <td><?= (int)$row['product_id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td>$<?= number_format((float)$row['price'], 2) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td>$<?= number_format($itemTotal, 2) ?></td>
                <td>
                    <form method="post" action="../controller/CartController.php">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="cart_id" value="<?= (int)$row['cart_id'] ?>">
                        <input type="hidden" name="return_url" value="../view/cart.php">
                        <button type="submit">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="width: 300px; margin-left: auto; text-align: right;">
        <hr>
        <?php
            $tax = $subtotal * TAX_RATE;
            $shipping = $subtotal * SHIPPING_RATE;
            $orderTotal = $subtotal + $tax + $shipping;
        ?>
        <p>Subtotal: $<?= number_format($subtotal, 2) ?></p>
        <p>Tax (5%): $<?= number_format($tax, 2) ?></p>
        <p>Shipping (10%): $<?= number_format($shipping, 2) ?></p>
        <p class="total">Order Total: $<?= number_format($orderTotal, 2) ?></p>
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 24px;">
        <a href="products.php" class="btn">Continue Shopping</a>
        <form method="post" action="../controller/CartController.php">
            <input type="hidden" name="action" value="checkout">
            <button type="submit" class="btn">Check Out</button>
        </form>
    </div>

    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="products.php" class="btn">Start Shopping</a>
    <?php endif; ?>
</main>
</body>
</html>
