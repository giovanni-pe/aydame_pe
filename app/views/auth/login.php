<!-- app/views/auth/login.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CachueApp</title>
    
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
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .logo h2 {
            color: #333;
            font-weight: 700;
            font-size: 2rem;
        }
        .logo p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-floating input {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .form-floating input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
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
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #764ba2;
        }
        .alert {
            border-radius: 15px;
            border: none;
            margin-bottom: 1.5rem;
        }
        .demo-users {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        .demo-users h6 {
            color: #495057;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .demo-user {
            background: white;
            border-radius: 10px;
            padding: 0.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .demo-user:hover {
            background: #e3f2fd;
            transform: translateX(5px);
        }
        .demo-user:last-child {
            margin-bottom: 0;
        }
        .demo-user .info {
            flex: 1;
        }
        .demo-user .role {
            font-size: 0.75rem;
            color: #6c757d;
        }
        .demo-user .username {
            font-weight: 600;
            color: #333;
        }
        .demo-user .btn-demo {
            background: #e3f2fd;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            color: #1976d2;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container mx-auto">
                    <!-- Logo y título -->
                    <div class="logo">
                        <i class="fas fa-tools"></i>
                        <h2>CachueApp</h2>
                        <p>Conectamos personas que necesitan ayuda<br>con cachueleros confiables</p>
                    </div>

                    <!-- Mensajes de error -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de login -->
                    <form method="POST" id="loginForm">
                        <div class="form-floating">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Usuario o Email" required>
                            <label for="username">
                                <i class="fas fa-user me-2"></i>Usuario o Email
                            </label>
                        </div>

                        <div class="form-floating">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <button type="submit" class="btn btn-login btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </button>
                    </form>

                    <div class="divider">
                        <span>o</span>
                    </div>

                    <div class="register-link">
                        <p class="mb-2">¿No tienes cuenta?</p>
                        <a href="<?= BASE_URL ?>auth/register">
                            <i class="fas fa-user-plus me-2"></i>Crear cuenta gratis
                        </a>
                    </div>

                    <!-- Usuarios de prueba -->
                    <div class="demo-users">
                        <h6><i class="fas fa-flask me-2"></i>Usuarios de Prueba</h6>
                        
                        <div class="demo-user" onclick="fillLogin('cliente1', 'password123')">
                            <div class="info">
                                <div class="username">cliente1</div>
                                <div class="role">Cliente - Busca servicios</div>
                            </div>
                            <button type="button" class="btn-demo">Probar</button>
                        </div>
                        
                        <div class="demo-user" onclick="fillLogin('cachuelero1', 'password123')">
                            <div class="info">
                                <div class="username">cachuelero1</div>
                                <div class="role">Cachuelero - Ofrece servicios</div>
                            </div>
                            <button type="button" class="btn-demo">Probar</button>
                        </div>
                        
                        <div class="demo-user" onclick="fillLogin('cachuelero2', 'password123')">
                            <div class="info">
                                <div class="username">cachuelero2</div>
                                <div class="role">Cachuelero - Especialista</div>
                            </div>
                            <button type="button" class="btn-demo">Probar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para rellenar datos de prueba
        function fillLogin(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            
            // Animar los campos
            document.getElementById('username').focus();
            setTimeout(() => {
                document.getElementById('password').focus();
            }, 200);
        }

        // Animación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Ingresando...';
            submitBtn.disabled = true;
        });

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.login-container');
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