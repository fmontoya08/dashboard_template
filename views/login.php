<?php
require_once __DIR__ . '/../config/session.php';

//Capturar errores del AuthController y mostrarlos aquí
$error_msg = $_SESSION['error_msg'] ?? null;
unset($_SESSION['error_msg']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Acceso - Sistema</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        .bg-login-custom {
            background: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600&auto=format&fit=crop') center;
            background-size: cover;
        }
        .password-toggle { cursor: pointer; pointer-events: all; }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-custom"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">¡Bienvenido de nuevo!</h1>
                                    </div>
                                    
                                    <?php if ($error_msg): ?>
                                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-2"></i><?= htmlspecialchars($error_msg) ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" action="index.php" method="POST" id="loginForm">
                                        
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" name="email" aria-describedby="emailHelp"
                                                placeholder="Correo electrónico..." autocomplete="username" required>
                                            <div class="invalid-feedback d-none" id="emailError">Ingresa un correo válido.</div>
                                        </div>
                                        
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Contraseña" autocomplete="current-password" required>
                                            
                                            <span class="position-absolute password-toggle text-gray-500" 
                                                  style="right: 15px; top: 15px; z-index: 10;" 
                                                  onclick="togglePassword()">
                                                <i class="far fa-eye" id="toggleIcon"></i>
                                            </span>
                                            <div class="invalid-feedback d-none" id="passwordError">La contraseña es obligatoria.</div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Recordarme</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold" id="btnLogin">
                                            <span id="btnText">Ingresar</span>
                                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none ml-2" role="status" aria-hidden="true"></span>
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="#">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>

    <script>
        //Logica Mostrar/Ocultar contraseña
        function togglePassword() {
            const pwdInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        //Logica de Validacion y Botón de Carga
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;

            //Pre-validar campos vacios en tiempo real
            if (!email.value.includes('@')) {
                email.classList.add('is-invalid');
                document.getElementById('emailError').classList.remove('d-none');
                isValid = false;
            }
            if (password.value.trim() === '') {
                password.classList.add('is-invalid');
                document.getElementById('passwordError').classList.remove('d-none');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); //Evitar que el formulario se mande si hay error
                return;
            }

            //Cambiar aspecto del boton al enviar
            const btn = document.getElementById('btnLogin');
            document.getElementById('btnText').innerText = 'Verificando...';
            document.getElementById('btnSpinner').classList.remove('d-none');
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none'; //Evita doble clic
        });
        
        //Quitar la alerta roja en cuanto el usuario empiece a escribir de nuevo
        $('input').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').addClass('d-none');
        });
    </script>
</body>
</html>