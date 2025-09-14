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
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Database connection failed: " . $this->conn->connect_error);
            }
        } catch (\Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}

