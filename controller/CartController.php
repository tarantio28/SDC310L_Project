<?php
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/Cart.php";

use Model\Database;
use Model\Cart;

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: ../view/login.php?error=login_required");
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$cart = new Cart($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['quantity'] ?? 1);
        if ($productId > 0) $cart->add($_SESSION['user_id'], $productId, $qty);
        header("Location: ../view/cart.php");
        exit;
    }

    if ($action === 'remove') {
        $cartId = (int)($_POST['cart_id'] ?? 0);
        if ($cartId > 0) $cart->remove($cartId);
        header("Location: ../view/cart.php");
        exit;
    }
}
