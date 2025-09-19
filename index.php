<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Online Store</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<header class="site-header">
  <h1>My Online Store</h1>
  <p>Your one-stop shop for awesome products</p>
</header>

<nav class="top-nav">
  <a href="view/products.php">Products</a>
  <a href="view/cart.php">Cart</a>
  <?php if (!empty($_SESSION['user_id'])): ?>
    <span class="welcome">Hi, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
    <a href="controller/UserController.php?action=logout">Logout</a>
  <?php else: ?>
    <a href="view/login.php">Login</a>
    <a href="view/register.php">Register</a>
  <?php endif; ?>
</nav>

<main class="container">
  <h2>Shop With Us</h2>
  <p>Explore our catalog, add items to your cart, and enjoy a smooth checkout experience.</p>
  <a class="btn" href="view/products.php">Start Shopping</a>
</main>
</body>
</html>
