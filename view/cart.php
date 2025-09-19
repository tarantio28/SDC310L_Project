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

$db = new Database();
$conn = $db->getConnection();
$cartModel = new Cart($conn);
$items = $cartModel->items($_SESSION['user_id']);

$total = 0.0;
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
  <table class="table">
    <thead>
      <tr>
        <th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $items->fetch_assoc()): 
        $sub = (float)$row['price'] * (int)$row['quantity'];
        $total += $sub;
      ?>
      <tr>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td>$<?= number_format((float)$row['price'], 2) ?></td>
        <td><?= (int)$row['quantity'] ?></td>
        <td>$<?= number_format($sub, 2) ?></td>
        <td>
          <form method="post" action="../controller/CartController.php">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="cart_id" value="<?= (int)$row['cart_id'] ?>">
            <button type="submit">Remove</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p class="total">Total: $<?= number_format($total, 2) ?></p>
  <p class="note">Checkout flow is out of scope for this mini build; you can add it later.</p>
</main>
</body>
</html>
