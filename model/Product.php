<?php
namespace Model;

class Product {
    private $conn;
    public function __construct($mysqliConn) { $this->conn = $mysqliConn; }

    public function all() {
        return $this->conn->query("SELECT * FROM products ORDER BY product_id DESC");
    }

    // NEW: Method to get all products and join the cart quantity for a specific user
    public function allForUser($userId) {
        $sql = "SELECT p.*, c.quantity AS cart_quantity, c.cart_id
                FROM products p
                LEFT JOIN cart c ON p.product_id = c.product_id AND c.user_id = ?
                ORDER BY p.product_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // ... (create, update, delete methods remain the same) ...
    public function create($name, $desc, $price, $stock) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (product_name, description, price, stock) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssdi", $name, $desc, $price, $stock);
        return $stmt->execute();
    }

    public function update($id, $name, $desc, $price, $stock) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET product_name=?, description=?, price=?, stock=? WHERE product_id=?"
        );
        $stmt->bind_param("ssdii", $name, $desc, $price, $stock, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
