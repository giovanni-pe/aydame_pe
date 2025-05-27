<!-- app/views/dashboard/index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CachueApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card.primary .icon { color: #667eea; }
        .stat-card.success .icon { color: #28a745; }
        .stat-card.warning .icon { color: #ffc107; }
        .stat-card.info .icon { color: #17a2b8; }
        
        .task-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }
        
        .task-card.available {
            border-left-color: #28a745;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-secondary-custom:hover {
            background: #5a6268;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>dashboard">
                <i class="fas fa-tools me-2"></i>CachueApp
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>dashboard">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'cliente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>task/create">
                            <i class="fas fa-plus me-1"></i>Nueva Tarea
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?= $user->first_name ?? 'Usuario' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile/edit">
                                <i class="fas fa-edit me-2"></i>Editar Perfil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>wallet/recharge">
                                <i class="fas fa-wallet me-2"></i>Mi Billetera
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">
                        ¡Hola, <?= $user->first_name ?? 'Usuario' ?>! 👋
                    </h1>
                    <p class="mb-0 opacity-75">
                        <?php if ($_SESSION['user_role'] === 'cliente'): ?>
                            Bienvenido a tu panel de cliente. Aquí puedes crear tareas y gestionar tus servicios.
                        <?php else: ?>
                            Bienvenido a tu panel de cachuelero. Encuentra trabajos y gestiona tus servicios.
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="text-white">
                        <i class="fas fa-wallet me-2"></i>
                        <strong>Saldo: $<?= number_format($user->wallet_balance ?? 0, 2) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <?php if ($_SESSION['user_role'] === 'cliente'): ?>
                <div class="col-md-3">
                    <div class="stat-card primary">
                        <div class="icon"><i class="fas fa-tasks"></i></div>
                        <h3><?= count($tasks) ?></h3>
                        <p class="mb-0">Mis Tareas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card success">
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                        <h3><?= count(array_filter($tasks, fn($t) => $t->status === 'completada')) ?></h3>
                        <p class="mb-0">Completadas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card warning">
                        <div class="icon"><i class="fas fa-clock"></i></div>
                        <h3><?= count(array_filter($tasks, fn($t) => $t->status === 'publicada')) ?></h3>
                        <p class="mb-0">Pendientes</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card info">
                        <div class="icon"><i class="fas fa-wallet"></i></div>
                        <h3>$<?= number_format($user->wallet_balance ?? 0, 0) ?></h3>
                        <p class="mb-0">Saldo</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-3">
                    <div class="stat-card primary">
                        <div class="icon"><i class="fas fa-briefcase"></i></div>
                        <h3><?= count($tasks) ?></h3>
                        <p class="mb-0">Mis Trabajos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card success">
                        <div class="icon"><i class="fas fa-star"></i></div>
                        <h3><?= number_format($user->average_rating ?? 0, 1) ?></h3>
                        <p class="mb-0">Calificación</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card warning">
                        <div class="icon"><i class="fas fa-search"></i></div>
                        <h3><?= count($available_tasks) ?></h3>
                        <p class="mb-0">Disponibles</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card info">
                        <div class="icon"><i class="fas fa-wallet"></i></div>
                        <h3>$<?= number_format($user->wallet_balance ?? 0, 0) ?></h3>
                        <p class="mb-0">Saldo</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <!-- Vista Cliente -->
            <?php if ($_SESSION['user_role'] === 'cliente'): ?>
                <div class="col-md-8">
                    <div class="welcome-card">
                        <h4 class="mb-3">
                            <i class="fas fa-plus-circle me-2 text-primary"></i>
                            ¿Necesitas ayuda con alguna tarea?
                        </h4>
                        <p class="text-muted mb-3">
                            Publica una tarea y recibe propuestas de cachueleros calificados en minutos.
                        </p>
                        <a href="<?= BASE_URL ?>task/create" class="btn btn-action">
                            <i class="fas fa-plus me-2"></i>Crear Nueva Tarea
                        </a>
                    </div>

                    <h5 class="mb-3">
                        <i class="fas fa-list me-2"></i>Mis Tareas
                    </h5>
                    
                    <?php if (empty($tasks)): ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h5>No tienes tareas publicadas</h5>
                            <p>Crea tu primera tarea para empezar</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tasks as $task): ?>
                            <div class="task-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-2"><?= $task->title ?></h6>
                                        <p class="text-muted mb-2"><?= substr($task->description, 0, 100) ?>...</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-<?= getStatusColor($task->status) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $task->status)) ?>
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-dollar-sign me-1"></i><?= number_format($task->budget, 2) ?>
                                            </small>
                                            <?php if ($task->worker_name): ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i><?= $task->worker_name ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="<?= BASE_URL ?>task/detail/<?= $task->id ?>" class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <!-- Vista Cachuelero -->
            <?php else: ?>
                <div class="col-md-8">
                    <h5 class="mb-3">
                        <i class="fas fa-search me-2"></i>Tareas Disponibles
                    </h5>
                    
                    <?php if (empty($available_tasks)): ?>
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h5>No hay tareas disponibles</h5>
                            <p>Vuelve más tarde para ver nuevas oportunidades</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($available_tasks as $task): ?>
                            <div class="task-card available">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-2"><?= $task->title ?></h6>
                                        <p class="text-muted mb-2"><?= substr($task->description, 0, 100) ?>...</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-success">Disponible</span>
                                            <small class="text-success fw-bold">
                                                <i class="fas fa-dollar-sign me-1"></i><?= number_format($task->budget, 2) ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i><?= $task->first_name ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i><?= $task->location ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="<?= BASE_URL ?>task/detail/<?= $task->id ?>" class="btn btn-sm btn-outline-info">
                                            Ver
                                        </a>
                                        <a href="<?= BASE_URL ?>task/apply/<?= $task->id ?>" class="btn btn-sm btn-action">
                                            Postular
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!empty($tasks)): ?>
                        <h5 class="mb-3 mt-4">
                            <i class="fas fa-briefcase me-2"></i>Mis Trabajos
                        </h5>
                        
                        <?php foreach ($tasks as $task): ?>
                            <div class="task-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-2"><?= $task->title ?></h6>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-<?= getStatusColor($task->status) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $task->status)) ?>
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-dollar-sign me-1"></i><?= number_format($task->budget, 2) ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i><?= $task->client_name ?>
                                            </small>
                                        </div>
                                    </div>
                                    <a href="<?= BASE_URL ?>task/detail/<?= $task->id ?>" class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="welcome-card">
                    <h6 class="mb-3">
                        <i class="fas fa-wallet me-2"></i>Mi Billetera
                    </h6>
                    <div class="text-center mb-3">
                        <h3 class="text-primary">$<?= number_format($user->wallet_balance ?? 0, 2) ?></h3>
                        <p class="text-muted mb-0">Saldo disponible</p>
                    </div>
                    <a href="<?= BASE_URL ?>wallet/recharge" class="btn btn-action w-100">
                        <i class="fas fa-plus me-2"></i>Recargar Saldo
                    </a>
                </div>

                <div class="welcome-card">
                    <h6 class="mb-3">
                        <i class="fas fa-user me-2"></i>Mi Perfil
                    </h6>
                    <p class="text-muted mb-3">
                        Mantén tu perfil actualizado para obtener mejores oportunidades.
                    </p>
                    <a href="<?= BASE_URL ?>profile/edit" class="btn btn-secondary-custom w-100">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
function getStatusColor($status) {
    switch ($status) {
        case 'publicada': return 'primary';
        case 'en_postulacion': return 'warning';
        case 'asignada': return 'info';
        case 'en_progreso': return 'success';
        case 'esperando_evidencia': return 'warning';
        case 'completada': return 'success';
        case 'cancelada': return 'danger';
        default: return 'secondary';
    }
}
?>