<?php
namespace Model;

use mysqli;

class Database {
    private $host = "localhost";
    private $dbname = "store_db";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        if ($this->conn) return $this->conn;

        $this->conn = @new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
        // Optional: set charset
        $this->conn->set_charset("utf8mb4");
        return $this->conn;
    }
}


