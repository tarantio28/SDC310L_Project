<?php
session_start();
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/Product.php";

use Model\Database;
use Model\Product;

$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);
$products = $productModel->all();
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
  <?php if (!empty($_SESSION['user_id'])): ?>
    <span class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
    <a href="../controller/UserController.php?action=logout">Logout</a>
  <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
  <?php endif; ?>
</nav>

<main class="container">
  <?php if (!empty($_SESSION['user_id'])): ?>
    <h2>Add a Product (simple admin form)</h2>
    <form class="form-row" method="post" action="../controller/ProductController.php">
      <input type="hidden" name="action" value="create">
      <input type="text" name="name" placeholder="Name" required>
      <input type="text" name="description" placeholder="Description">
      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="number" name="stock" placeholder="Stock" required>
      <button class="btn" type="submit">Add</button>
    </form>
    <p class="note">For full role-based admin, you can later add user levels and limit this form to admins only.</p>
  <?php else: ?>
    <p class="note">Login to add products and manage your cart.</p>
  <?php endif; ?>

  <h2>Product List</h2>
  <table class="table">
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $products->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$row['product_id'] ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
          <td>$<?= number_format((float)$row['price'], 2) ?></td>
          <td><?= (int)$row['stock'] ?></td>
          <td class="action">
            <!-- Add to cart -->
            <form method="post" action="../controller/CartController.php" class="action">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="product_id" value="<?= (int)$row['product_id'] ?>">
              <input type="number" name="quantity" min="1" value="1" style="width:70px;">
              <button type="submit" class="btn">Add to Cart</button>
            </form>

            <!-- Update / Delete (simple inline admin controls) -->
            <?php if (!empty($_SESSION['user_id'])): ?>
              <form method="post" action="../controller/ProductController.php" class="action">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$row['product_id'] ?>">
                <button type="submit">Delete</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <?php if (!empty($_SESSION['user_id'])): ?>
    <h3>Quick Update Product</h3>
    <form class="form-row" method="post" action="../controller/ProductController.php">
      <input type="hidden" name="action" value="update">
      <input type="number" name="id" placeholder="Product ID" required>
      <input type="text" name="name" placeholder="Name" required>
      <input type="text" name="description" placeholder="Description">
      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="number" name="stock" placeholder="Stock" required>
      <button class="btn" type="submit">Update</button>
    </form>
  <?php endif; ?>
</main>
</body>
</html>


