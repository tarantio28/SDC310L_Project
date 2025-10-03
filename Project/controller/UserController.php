<?php
require_once __DIR__ . "/../model/database.php";
require_once __DIR__ . "/../model/User.php";

use Model\Database;
use Model\User;

session_start();

$db = new Database();
$conn = $db->getConnection();
$userModel = new User($conn);

// logout (GET action)
if (($_GET['action'] ?? '') === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $ok = $userModel->register(
            trim($_POST['username'] ?? ''),
            trim($_POST['email'] ?? ''),
            $_POST['password'] ?? ''
        );
        if ($ok) {
            header("Location: ../view/login.php?msg=registered");
        } else {
            header("Location: ../view/register.php?error=username_taken");
        }
        exit;
    }

    if ($action === 'login') {
        $u = $userModel->login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '');
        if ($u) {
            $_SESSION['user_id'] = $u['user_id'];
            $_SESSION['username'] = $u['username'];
            header("Location: ../view/products.php");
        } else {
            header("Location: ../view/login.php?error=invalid");
        }
        exit;
    }
}
