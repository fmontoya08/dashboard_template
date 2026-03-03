<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }


    public function login($username_or_email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE (email = :user OR name = :user) AND deleted_at IS NULL 
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user", $username_or_email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                return $row; 
            }
        }
        return false; 
    }


    public function getAllActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE deleted_at IS NULL ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password) {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hashed);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Devolvemos el ID del nuevo usuario
        }
        return false;
    }

    public function update($id, $name, $email, $password = null) {
        if(!empty($password)) {
            $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $password_hashed);
        } else {
            $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email WHERE id = :id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function softDelete($id) {
        $query = "UPDATE " . $this->table_name . " SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getTotalActive() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getRecentActivity() {
        $query = "SELECT id, name, email, created_at, updated_at, deleted_at 
                  FROM " . $this->table_name . " 
                  ORDER BY updated_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAuditActivity() {
        $query = "SELECT id, name, email, created_at, updated_at, deleted_at 
                  FROM " . $this->table_name . " 
                  ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>