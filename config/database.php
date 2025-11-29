<?php
// Check if class already exists to prevent redeclaration
if (!class_exists('Database')) {
    class Database {
        private $host = "localhost";
        private $db_name = "foodfusion_db";
        private $username = "root";
        private $password = "";
        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $exception) {
                error_log("Connection error: " . $exception->getMessage());
                echo "Database connection failed. Please try again later.";
            }
            return $this->conn;
        }

        public function createTables() {
            try {
                $sql = file_get_contents(__DIR__ . '/../sql/database.sql');
                $this->conn->exec($sql);
                return true;
            } catch(PDOException $exception) {
                error_log("Table creation error: " . $exception->getMessage());
                return false;
            }
        }
    }
}
?>