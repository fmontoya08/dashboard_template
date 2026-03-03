<?php
require_once 'models/Audit.php'; // Cambiamos a Audit.php

class AuditController {
    private $auditModel;

    public function __construct() {
        $this->auditModel = new Audit();
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // Traemos TODO desde la tabla real de auditorías
        $actividades = $this->auditModel->getAll();
        require_once 'views/audits/index.php';
    }
}
?>