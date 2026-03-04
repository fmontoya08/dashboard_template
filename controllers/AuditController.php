<?php
require_once 'models/Audit.php';

class AuditController {
    private $auditModel;

    public function __construct() {
        $this->auditModel = new Audit();
    }

    private function startSessionSafe() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $this->startSessionSafe();

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $actividades = $this->auditModel->getAll();
        require_once 'views/audits/index.php';
    }
}