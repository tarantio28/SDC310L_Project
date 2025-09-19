<?php
session_start();
if (!empty($_SESSION['user_id'])) {
  header("Location: products.php");
  exit;
}
$msg = '';
if (isset($_GET['error']) && $_GET['error'] === 'username_taken') $msg = 'Username already taken.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="site-header"><h1>Create an Account</h1></header>
<nav class="top-nav">
  <a href="../index.php">Home</a>
  <a href="products.php">Products</a>
  <a href="login.php">Login</a>
</nav>
<main class="container">
  <?php if ($msg): ?><p class="error"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <form method="post" action="../controller/UserController.php" class="form-row" style="max-width:520px;">
    <input type="hidden" name="action" value="register">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Register</button>
  </form>
</main>
</body>
</html>
