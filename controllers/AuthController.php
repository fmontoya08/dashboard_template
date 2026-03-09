<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Esta función ahora maneja tanto mostrar la vista como procesar el login (Acepta parámetros en GET y procesa en POST)
    public function login() {
        // Si el usuario ya está logueado, redirigirlo al dashboard
        if (isset($_SESSION['usuario_id'])) {
            header("Location: views/dashboard.php");
            exit;
        }

        // Si la petición es POST, el usuario intentó iniciar sesión
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';
            
            // Llamamos a la función privada para procesar la seguridad
            $this->processLogin($email, $password, $csrf_token);
        } else {
            // Si la petición es GET (solo entrar a la página principal), mostramos la vista HTML
            require_once __DIR__ . '/../views/login.php';
        }
    }

    // Lógica fuerte de seguridad separada
    private function processLogin($email, $password, $csrf_token) {
        // 1. Validar el token CSRF para evitar peticiones maliciosas externas
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $_SESSION['error_msg'] = "Petición no válida. Intenta de nuevo.";
            header("Location: index.php"); // URL Limpia
            exit;
        }

        // 2. Prevenir ataques de fuerza bruta (bloqueo por 15 minutos si falla 5 veces)
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
            if (time() - $_SESSION['last_attempt_time'] < 900) {
                $_SESSION['error_msg'] = "Demasiados intentos. Por seguridad, inténtalo en 15 minutos.";
                header("Location: index.php"); // URL Limpia
                exit;
            } else {
                $_SESSION['login_attempts'] = 0; // Liberar bloqueo
            }
        }

        // 3. Buscar al usuario en la BD
        $user = $this->userModel->findByEmail($email);

        // 4. Verificar contraseña (Debes usar password_hash en tu registro y password_verify aquí)
        if ($user && password_verify($password, $user['password'])) {
            
            // 5. PREVENIR FIJACIÓN DE SESIÓN (Seguridad crítica)
            session_regenerate_id(true);

            // 6. Guardar datos en sesión
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nombre'] = $user['username'];
            $_SESSION['usuario_rol'] = $user['role'];
            
            // Reiniciar intentos limpios
            $_SESSION['login_attempts'] = 0;

            // Redirigir al dashboard (Ajusta la ruta si es necesario)
            header("Location: views/dashboard.php");
            exit;
        } else {
            // Registrar intento fallido
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_attempt_time'] = time();

            // Mensaje GENÉRICO siempre. Nunca digas "El correo no existe" o "La contraseña es incorrecta".
            $_SESSION['error_msg'] = "Credenciales inválidas.";
            header("Location: index.php"); // URL Limpia
            exit;
        }
    }

    public function logout() {
        require_once __DIR__ . '/../config/session.php';
        session_unset();
        session_destroy();
        header("Location: index.php"); // URL Limpia
        exit;
    }
}
?>