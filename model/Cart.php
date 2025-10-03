<?php
namespace Model;

class Cart {
    private $conn;
    public function __construct($mysqliConn) { $this->conn = $mysqliConn; }

    public function add($userId, $productId, $qty) {
        $stmt = $this->conn->prepare("SELECT cart_id, quantity FROM cart WHERE user_id=? AND product_id=?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            $newQty = max(1, (int)$existing['quantity'] + (int)$qty);
            $stmt = $this->conn->prepare("UPDATE cart SET quantity=? WHERE cart_id=?");
            $stmt->bind_param("ii", $newQty, $existing['cart_id']);
            return $stmt->execute();
        }

        $qty = max(1, (int)$qty);
        $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $productId, $qty);
        return $stmt->execute();
    }

    public function items($userId) {
        $sql = "SELECT c.cart_id, c.quantity, p.product_id, p.product_name, p.price
                FROM cart c
                JOIN products p ON c.product_id = p.product_id
                WHERE c.user_id=?
                ORDER BY c.cart_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function remove($cartId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE cart_id=?");
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }

    public function clear($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
