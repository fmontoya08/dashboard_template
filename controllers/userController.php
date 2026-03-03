<?php
require_once 'models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Muestra la tabla con todos los usuarios
    public function index() {
        $users = $this->userModel->getAllActive();
        require_once 'views/users/index.php';
    }

    // Muestra el formulario para crear
    public function create() {
        require_once 'views/users/create.php';
    }

    // Guarda el usuario en la BD
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->userModel->create($name, $email, $password);
            header("Location: index.php?action=users"); // Redirige a la lista
        }
    }

    // Muestra el formulario para editar
    public function edit($id) {
        $user = $this->userModel->getById($id);
        require_once 'views/users/edit.php';
    }

    // Actualiza los datos en la BD
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password']; // Puede venir vacío

            $this->userModel->update($id, $name, $email, $password);
            header("Location: index.php?action=users");
        }
    }

    // Aplica el Soft Delete
    public function delete($id) {
        $this->userModel->softDelete($id);
        header("Location: index.php?action=users");
    }
}
?>