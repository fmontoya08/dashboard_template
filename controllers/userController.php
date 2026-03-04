<?php
require_once 'models/User.php';
require_once 'models/Audit.php';

class UserController {
    private $userModel;
    private $auditModel;

    public function __construct() {
        $this->userModel = new User();
        $this->auditModel = new Audit(); 
        
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

            $new_user_id = $this->userModel->create($name, $email, $password);

        
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

            $old_data = $this->userModel->getById($id);
            $old_values = ['name' => $old_data['name'], 'email' => $old_data['email']];

            $this->userModel->update($id, $name, $email, $password);

            $new_values = ['name' => $name, 'email' => $email];
            $this->auditModel->log($_SESSION['user_id'], 'UPDATE', 'users', $id, $old_values, $new_values);

            header("Location: index.php?action=users");
        }
    }

    public function delete($id) {
    
        $old_data = $this->userModel->getById($id);
        $old_values = ['name' => $old_data['name'], 'email' => $old_data['email']];

   
        $this->userModel->softDelete($id);

     
        $this->auditModel->log($_SESSION['user_id'], 'DELETE', 'users', $id, $old_values, null);

        header("Location: index.php?action=users");
    }
}
?>