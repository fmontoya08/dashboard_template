<?php
class Database {
    private $host = "localhost";
    private $db_name = "hyperion_db";
    private $username = "root";
    private $password = ""; 
    private $conn;
    private static $instance = null;

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Error de conexión a la Base de Datos: " . $exception->getMessage());
        }
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getConnection() {
        return $this->conn;
    }
    private function __clone() {}
}
?>