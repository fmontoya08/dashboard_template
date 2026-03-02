<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
</head>
<body>
    <h2>Bienvenido al panel, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Has iniciado sesión correctamente usando MVC.</p>
    
    <a href="index.php?action=logout">Cerrar Sesión</a>
</body>
</html>