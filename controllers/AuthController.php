<?php
class AuthController {
    
    public function login() {
        // prepare error variable for view
        $error = '';

        // handle POST submission
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // example validation (replace with real logic)
            if ($email === 'admin@example.com' && $password === 'secret') {
                header('Location: /hyperion/dashboard');
                exit;
            } else {
                $error = 'Correo o contraseña incorrectos.';
            }
        }

        require 'views/auth/login.php';
    }
}
?>