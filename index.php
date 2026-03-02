<?php
require_once 'controllers/AuthController.php';

$controller = new AuthController();

// Capturamos la acción de la URL, por defecto es 'login'
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->login();
        break;
}
?>