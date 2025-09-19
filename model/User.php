<?php
namespace Model;

class User {
    private $conn;
    public function __construct($mysqliConn) { $this->conn = $mysqliConn; }

    public function register($username, $email, $password) {
        // unique username check
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) return false;

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
