<?php
$request = $_SERVER['REQUEST_URI'];
$base_path = '/hyperion/'; 

$route = str_replace($base_path, '', $request);
$route = trim($route, '/');

if ($route == 'login') {
    
    require 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->login();

} elseif ($route == '' || $route == 'dashboard') {
    
    echo "Aquí irá el Dashboard";

} else {
    http_response_code(404);
    require 'recursos/404.html';
}
?>