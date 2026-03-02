<?php
// Requerir la conexión a la base de datos que ya creamos
require_once 'config/database.php';

echo "<h2>Generador de Usuario Administrador</h2>";

try {
    // Instanciar la conexión
    $database = new Database();
    $db = $database->getConnection();

    // Datos del administrador que queremos crear
    $admin_username = 'admin';
    $admin_password = 'admin123'; // Cambia esto por la contraseña que desees

    // 1. Encriptar la contraseña (¡NUNCA guardar en texto plano!)
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // 2. Comprobar si el usuario ya existe para no duplicarlo
    $check_query = "SELECT id FROM users WHERE username = :username LIMIT 1";
    $stmt_check = $db->prepare($check_query);
    $stmt_check->bindParam(':username', $admin_username);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        echo "<p style='color: orange;'>El usuario '{$admin_username}' ya existe en la base de datos.</p>";
    } else {
        // 3. Preparar la consulta de inserción
        $insert_query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt_insert = $db->prepare($insert_query);
        
        $stmt_insert->bindParam(':username', $admin_username);
        $stmt_insert->bindParam(':password', $hashed_password);

        // 4. Ejecutar la inserción
        if ($stmt_insert->execute()) {
            echo "<p style='color: green;'>¡Éxito! El usuario administrador ha sido creado.</p>";
            echo "<ul>
                    <li><strong>Usuario:</strong> {$admin_username}</li>
                    <li><strong>Contraseña:</strong> {$admin_password}</li>
                  </ul>";
            echo "<p><a href='index.php'>Ir al Login</a></p>";
        } else {
            echo "<p style='color: red;'>Hubo un error al intentar crear el usuario.</p>";
        }
    }

} catch(PDOException $e) {
    echo "<p style='color: red;'>Error de Base de Datos: " . $e->getMessage() . "</p>";
}
?>