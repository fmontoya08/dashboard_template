<?php require_once 'views/includes/header.php'; ?>
<?php require_once 'views/includes/sidebar.php'; ?>
<?php require_once 'views/includes/topbar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Editar Usuario</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="index.php?action=user_update" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña (Dejar en blanco si no deseas cambiarla)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                <a href="index.php?action=users" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/includes/footer.php'; ?>