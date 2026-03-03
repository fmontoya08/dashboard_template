<?php
require_once 'controllers/AuthController.php';
require_once 'controllers/userController.php';

$controller = new AuthController();
$userController = new UserController();

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
    case 'users':
        $userController->index();
        break;
    case 'user_create':
        $userController->create();
        break;
    case 'user_store':
        $userController->store();
        break;
    case 'user_edit':
        $id = $_GET['id'];
        $userController->edit($id);
        break;
    case 'user_update':
        $userController->update();
        break;
    case 'user_delete':
        $id = $_GET['id'];
        $userController->delete($id);
        break;
}
?>