<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CachueApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo i {
            font-size: 3.5rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .logo h2 {
            color: #333;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .logo p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-floating input,
        .form-floating select {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .form-floating input:focus,
        .form-floating select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .role-selector {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .role-option {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .role-option:last-child {
            margin-bottom: 0;
        }
        .role-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .role-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .role-option .role-icon {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .role-option .role-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.3rem;
        }
        .role-option .role-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0;
        }
        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }
        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .login-link a:hover {
            color: #764ba2;
        }
        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 1.5rem;
        }
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.8rem;
        }
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            margin-top: 0.3rem;
            overflow: hidden;
        }
        .strength-bar .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }
        .strength-weak { background: #dc3545; }
        .strength-medium { background: #ffc107; }
        .strength-strong { background: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="register-container mx-auto">
                    <!-- Logo y título -->
                    <div class="logo">
                        <i class="fas fa-user-plus"></i>
                        <h2>Crear Cuenta</h2>
                        <p>Únete a la comunidad de CachueApp</p>
                    </div>

                    <!-- Mensajes de error -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de registro -->
                    <form method="POST" id="registerForm">
                        <!-- Selector de tipo de usuario -->
                        <div class="role-selector">
                            <h6 class="mb-3">
                                <i class="fas fa-users me-2"></i>¿Cómo quieres usar CachueApp?
                            </h6>
                            
                            <div class="role-option" onclick="selectRole('cliente')">
                                <input type="radio" name="role" value="cliente" id="role_cliente" required>
                                <div class="text-center">
                                    <div class="role-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="role-title">Soy Cliente</div>
                                    <div class="role-description">Busco personas que me ayuden con tareas</div>
                                </div>
                            </div>
                            
                            <div class="role-option" onclick="selectRole('cachuelero')">
                                <input type="radio" name="role" value="cachuelero" id="role_cachuelero" required>
                                <div class="text-center">
                                    <div class="role-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="role-title">Soy Cachuelero</div>
                                    <div class="role-description">Ofrezco mis servicios y habilidades</div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos del formulario -->
                        <div class="form-floating">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario" required>
                            <label for="username">
                                <i class="fas fa-user me-2"></i>Nombre de usuario
                            </label>
                        </div>

                        <div class="form-floating">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" required>
                            <label for="email">
                                <i class="fas fa-envelope me-2"></i>Correo electrónico
                            </label>
                        </div>

                        <div class="form-floating">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </label>
                            <div class="password-strength">
                                <div class="strength-text">Seguridad de la contraseña</div>
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
                            <label for="confirm_password">
                                <i class="fas fa-lock me-2"></i>Confirmar contraseña
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> y la <a href="#" class="text-decoration-none">política de privacidad</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-register btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            Crear Cuenta
                        </button>
                    </form>

                    <div class="divider">
                        <span>o</span>
                    </div>

                    <div class="login-link">
                        <p class="mb-2">¿Ya tienes cuenta?</p>
                        <a href="<?= BASE_URL ?>auth/login">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Selección de rol
        function selectRole(role) {
            // Remover selección anterior
            document.querySelectorAll('.role-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Seleccionar nuevo rol
            document.getElementById('role_' + role).checked = true;
            document.querySelector('#role_' + role).closest('.role-option').classList.add('selected');
        }

        // Verificador de fuerza de contraseña
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.querySelector('.strength-text');
            
            let strength = 0;
            let strengthClass = '';
            let strengthLabel = '';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength <= 2) {
                strengthClass = 'strength-weak';
                strengthLabel = 'Débil';
                strengthFill.style.width = '33%';
            } else if (strength <= 4) {
                strengthClass = 'strength-medium';
                strengthLabel = 'Media';
                strengthFill.style.width = '66%';
            } else {
                strengthClass = 'strength-strong';
                strengthLabel = 'Fuerte';
                strengthFill.style.width = '100%';
            }
            
            strengthFill.className = 'strength-fill ' + strengthClass;
            strengthText.textContent = 'Seguridad: ' + strengthLabel;
        });

        // Validación de confirmación de contraseña
        document.getElementById('confirm_password').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            
            if (confirmPassword && password !== confirmPassword) {
                e.target.setCustomValidity('Las contraseñas no coinciden');
            } else {
                e.target.setCustomValidity('');
            }
        });

        // Animación del formulario
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...';
            submitBtn.disabled = true;
        });

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.register-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>