<?php
require_once 'controllers/AuthController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/AuditController.php';

$controller = new AuthController();
$userController = new UserController();
$auditController = new AuditController();

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
    case 'auditorias':
        $auditController->index();
        break;
}
?>