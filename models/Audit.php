<?php
require_once 'config/database.php';

class Audit {
    private $conn;
    private $table_name = "audit_logs";

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Función para registrar un movimiento
    public function log($user_id, $action, $table_name, $record_id, $old_values = null, $new_values = null) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, action, table_name, record_id, old_values, new_values) 
                  VALUES (:user_id, :action, :table_name, :record_id, :old_values, :new_values)";
        
        $stmt = $this->conn->prepare($query);

        $old_json = is_array($old_values) ? json_encode($old_values, JSON_UNESCAPED_UNICODE) : $old_values;
        $new_json = is_array($new_values) ? json_encode($new_values, JSON_UNESCAPED_UNICODE) : $new_values;

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":table_name", $table_name);
        $stmt->bindParam(":record_id", $record_id);
        $stmt->bindParam(":old_values", $old_json);
        $stmt->bindParam(":new_values", $new_json);

        return $stmt->execute();
    }

        public function getAll() {
            $query = "SELECT a.*, u.name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id
                    ORDER BY a.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}