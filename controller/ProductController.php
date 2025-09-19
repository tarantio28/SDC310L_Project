<?php
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/Product.php";

use Model\Database;
use Model\Product;

session_start();

$db = new Database();
$conn = $db->getConnection();
$product = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $product->create($name, $desc, $price, $stock);
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        if ($id > 0) $product->update($id, $name, $desc, $price, $stock);
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $product->delete($id);
    }

    header("Location: ../view/products.php");
    exit;
}
