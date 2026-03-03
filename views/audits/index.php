<?php require_once 'views/includes/header.php'; ?>
<?php require_once 'views/includes/sidebar.php'; ?>
<?php require_once 'views/includes/topbar.php'; ?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Módulo de Auditoría Avanzada</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Log del Sistema</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario (Actor)</th>
                            <th>Acción</th>
                            <th>Tabla afectada</th>
                            <th>ID Reg.</th>
                            <th>Valores Anteriores</th>
                            <th>Valores Nuevos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($actividades as $actividad): ?>
                            <?php 
                                // Color de la etiqueta según la acción
                                $color = "secondary";
                                if ($actividad['action'] == 'CREATE') $color = "success";
                                if ($actividad['action'] == 'UPDATE') $color = "warning";
                                if ($actividad['action'] == 'DELETE') $color = "danger";
                            ?>
                            <tr>
                                <td><?= date('d/m/Y H:i:s', strtotime($actividad['created_at'])) ?></td>
                                <td><i class="fas fa-user-shield"></i> <?= htmlspecialchars($actividad['actor_name']) ?></td>
                                <td><span class="badge badge-<?= $color ?> p-2"><?= $actividad['action'] ?></span></td>
                                <td><code><?= $actividad['table_name'] ?></code></td>
                                <td>#<?= $actividad['record_id'] ?></td>
                                <td style="font-size: 0.85rem; word-break: break-all;"><?= $actividad['old_values'] ? htmlspecialchars($actividad['old_values']) : '<span class="text-muted">N/A</span>' ?></td>
                                <td style="font-size: 0.85rem; word-break: break-all;"><?= $actividad['new_values'] ? htmlspecialchars($actividad['new_values']) : '<span class="text-muted">N/A</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/includes/footer.php'; ?>