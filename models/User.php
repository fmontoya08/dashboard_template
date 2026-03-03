<?php
require_once 'config/database.php'; // Asegúrate de que la ruta a tu conexión sea correcta

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        // Usamos el Singleton para la conexión
        $this->conn = Database::getInstance()->getConnection();
    }

    // Leer todos los usuarios ACTIVOS (Soft Delete: deleted_at IS NULL)
    public function getAllActive() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE deleted_at IS NULL ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo usuario
    public function create($name, $email, $password) {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);

        // Encriptar la contraseña por seguridad
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password_hashed);

        return $stmt->execute();
    }

    // Actualizar usuario
    public function update($id, $name, $email, $password = null) {
        // Si mandaron contraseña, la actualizamos. Si no, solo nombre y correo.
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

    // ELIMINACIÓN LÓGICA (Soft Delete)
    public function softDelete($id) {
        $query = "UPDATE " . $this->table_name . " SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>