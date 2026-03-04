<?php
require_once 'models/User.php';
require_once 'config/database.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    private function startSessionSafe() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        $this->startSessionSafe();

        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (!empty($username) && !empty($password)) {
                $loggedInUser = $this->userModel->login($username, $password);

                if ($loggedInUser) {
                    $_SESSION['user_id'] = $loggedInUser['id'];
                    $_SESSION['username'] = $loggedInUser['username'];
                    header("Location: index.php?action=dashboard");
                    exit();
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            } else {
                $error = "Por favor, completa todos los campos.";
            }
        }

        require 'views/login.php';
    }

    public function dashboard() {
        $this->startSessionSafe();
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $total_users = $this->userModel->getTotalActive();
        $actividad_reciente = $this->userModel->getRecentActivity();

        require_once 'views/dashboard.php';
    }

    public function logout() {
        $this->startSessionSafe();
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}