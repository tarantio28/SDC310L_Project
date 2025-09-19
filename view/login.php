<?php
session_start();
if (!empty($_SESSION['user_id'])) {
  header("Location: products.php");
  exit;
}
$msg = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid') $msg = 'Invalid credentials.';
if (isset($_GET['error']) && $_GET['error'] === 'login_required') $msg = 'Please log in to continue.';
if (isset($_GET['msg']) && $_GET['msg'] === 'registered') $msg = 'Registration successful. Please log in.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="site-header"><h1>Login</h1></header>
<nav class="top-nav">
  <a href="../index.php">Home</a>
  <a href="products.php">Products</a>
  <a href="register.php">Register</a>
</nav>
<main class="container">
  <?php if ($msg): ?><p class="<?= str_starts_with($msg,'Invalid') ? 'error' : 'success' ?>"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <form method="post" action="../controller/UserController.php" class="form-row" style="max-width:420px;">
    <input type="hidden" name="action" value="login">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Login</button>
  </form>
</main>
</body>
</html>
