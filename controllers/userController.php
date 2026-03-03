<?php
require_once 'models/User.php';
require_once 'models/Audit.php'; // 1. Traemos el modelo de auditoría

class UserController {
    private $userModel;
    private $auditModel;

    public function __construct() {
        $this->userModel = new User();
        $this->auditModel = new Audit(); // 2. Lo instanciamos
        
        // Arrancamos sesión en el constructor para asegurar tener $_SESSION['user_id']
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $users = $this->userModel->getAllActive();
        require_once 'views/users/index.php';
    }

    public function create() {
        require_once 'views/users/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Creamos al usuario y recibimos su nuevo ID
            $new_user_id = $this->userModel->create($name, $email, $password);

            // REGISTRO DE AUDITORÍA
            $new_values = ['name' => $name, 'email' => $email];
            $this->auditModel->log($_SESSION['user_id'], 'CREATE', 'users', $new_user_id, null, $new_values);

            header("Location: index.php?action=users");
        }
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        require_once 'views/users/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password']; 

            // 1. Obtenemos los datos viejos ANTES de actualizar
            $old_data = $this->userModel->getById($id);
            $old_values = ['name' => $old_data['name'], 'email' => $old_data['email']];

            // 2. Actualizamos
            $this->userModel->update($id, $name, $email, $password);

            // 3. REGISTRO DE AUDITORÍA
            $new_values = ['name' => $name, 'email' => $email];
            $this->auditModel->log($_SESSION['user_id'], 'UPDATE', 'users', $id, $old_values, $new_values);

            header("Location: index.php?action=users");
        }
    }

    public function delete($id) {
        // 1. Obtenemos los datos viejos ANTES de borrar
        $old_data = $this->userModel->getById($id);
        $old_values = ['name' => $old_data['name'], 'email' => $old_data['email']];

        // 2. Borramos (Soft Delete)
        $this->userModel->softDelete($id);

        // 3. REGISTRO DE AUDITORÍA
        $this->auditModel->log($_SESSION['user_id'], 'DELETE', 'users', $id, $old_values, null);

        header("Location: index.php?action=users");
    }
}
?>