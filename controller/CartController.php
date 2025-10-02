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
        // MODIFIED: Redirect back to products page for better UX
        header("Location: ../view/products.php");
        exit;
    }

    if ($action === 'remove') {
        $cartId = (int)($_POST['cart_id'] ?? 0);
        if ($cartId > 0) $cart->remove($cartId);
        // MODIFIED: Redirect can go to cart or products
        $return_url = $_POST['return_url'] ?? '../view/cart.php';
        header("Location: " . $return_url);
        exit;
    }

    // NEW: Handle checkout action
    if ($action === 'checkout') {
        $cart->clear($_SESSION['user_id']);
        // Redirect to catalog page with a success message
        header("Location: ../view/products.php?msg=checkout_success");
        exit;
    }
}
