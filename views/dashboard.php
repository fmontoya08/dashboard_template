<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once 'includes/topbar.php'; ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de Control</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Descargar Reporte
        </a>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Usuarios Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_users ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sistema Estado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Óptimo</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-server fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history"></i> Auditoría: Actividad Reciente de Usuarios</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Última Acción</th>
                                    <th>Fecha de Movimiento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($actividad_reciente as $actividad): ?>
                                    <?php 
                                        if ($actividad['deleted_at'] != null) {
                                            $accion = "Eliminado";
                                            $color = "danger";
                                            $fecha = $actividad['deleted_at'];
                                        } elseif ($actividad['created_at'] == $actividad['updated_at']) {
                                            $accion = "Creado";
                                            $color = "success";
                                            $fecha = $actividad['created_at'];
                                        } else {
                                            $accion = "Actualizado";
                                            $color = "warning";
                                            $fecha = $actividad['updated_at'];
                                        }
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($actividad['name']) ?></strong> <br> <small class="text-muted"><?= htmlspecialchars($actividad['email']) ?></small></td>
                                        <td><span class="badge badge-<?= $color ?>"><?= $accion ?></span></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($fecha)) ?></td>
                                        <td>
                                            <?php if($actividad['deleted_at'] == null): ?>
                                                <i class="fas fa-check-circle text-success"></i> Activo
                                            <?php else: ?>
                                                <i class="fas fa-times-circle text-danger"></i> Inactivo
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php require_once 'includes/footer.php'; ?>