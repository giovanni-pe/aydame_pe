<?php
// app/views/layouts/default.php (actualizado)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? SITE_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/app.css">
</head>
<body>
    <!-- Navigation (solo si está logueado) -->
    <?php if (isLoggedIn()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>dashboard">
                <i class="fa fa-tools"></i> <?= SITE_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>dashboard">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'cliente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>task/create">
                            <i class="fa fa-plus"></i> Nueva Tarea
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-user"></i> Mi Cuenta
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile/edit">
                                <i class="fa fa-edit"></i> Editar Perfil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>wallet/recharge">
                                <i class="fa fa-wallet"></i> Mi Billetera
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout">
                                <i class="fa fa-sign-out-alt"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php 
    $success = getFlashMessage('success');
    $error = getFlashMessage('error');
    $warning = getFlashMessage('warning');
    ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($warning): ?>
        <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
            <?= $warning ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <?php if (isLoggedIn()): ?>
    <footer class="bg-light text-center text-muted py-3 mt-5">
        <div class="container">
            <small><?= SITE_NAME ?> v<?= APP_VERSION ?> - Conectamos personas que necesitan ayuda con cachueleros confiables</small>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= BASE_URL ?>js/app.js"></script>
</body>
</html>

<?php
// public/index.php (actualizado)

// Load Config
require_once '../app/config/config.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    // Try core classes first
    if (file_exists('../system/core/' . $className . '.php')) {
        require_once '../system/core/' . $className . '.php';
        return;
    }
    
    // Try models
    if (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
        return;
    }
    
    // Try controllers
    if (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
        return;
    }
});

// Check if accessing root, redirect to appropriate place
if (!isset($_GET['url']) || empty($_GET['url'])) {
    if (isLoggedIn()) {
        if (!$_SESSION['is_profile_complete']) {
            header('Location: ' . BASE_URL . 'profile/complete');
        } else {
            header('Location: ' . BASE_URL . 'dashboard');
        }
    } else {
        header('Location: ' . BASE_URL . 'auth/login');
    }
    exit();
}

// Initialize App
$app = new App();