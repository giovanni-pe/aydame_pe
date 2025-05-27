<!-- app/views/task/detail.php -->
<?php
// Devuelve el color de la insignia según el estado de la tarea
function getStatusColor($status) {
    switch ($status) {
        case 'publicada':
            return 'primary';
        case 'en_progreso':
            return 'warning';
        case 'finalizada':
            return 'success';
        case 'cancelada':
            return 'danger';
        default:
            return 'secondary';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tarea - CachueApp</title>
    
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
        }
        
        .task-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            padding: 0.8rem 1.5rem;
        }
        
        .application-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
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
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="<?= BASE_URL ?>dashboard">
                    <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['applied'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>¡Postulación enviada exitosamente!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['selected'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-user-check me-2"></i>Cachuelero seleccionado exitosamente
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <!-- Información de la tarea -->
                <div class="task-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h3><?= $task->title ?></h3>
                            <p class="text-muted">Por: <?= $task->first_name ?> <?= $task->last_name ?></p>
                        </div>
                        <div>
                            <span class="badge bg-<?= getStatusColor($task->status) ?> status-badge">
                                <?= ucfirst(str_replace('_', ' ', $task->status)) ?>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-align-left me-2"></i>Descripción</h5>
                        <p><?= nl2br($task->description) ?></p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-tags me-2"></i>Categoría</h6>
                            <p><i class="fas <?= $task->icon ?>"></i> <?= $task->category_name ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-dollar-sign me-2"></i>Presupuesto</h6>
                            <p class="text-success fw-bold fs-5">$<?= number_format($task->budget, 2) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt me-2"></i>Ubicación</h6>
                            <p><?= $task->location ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar me-2"></i>Publicado</h6>
                            <p><?= date('d/m/Y H:i', strtotime($task->created_at)) ?></p>
                        </div>
                    </div>

                    <?php if ($_SESSION['user_role'] === 'cachuelero' && $task->status === 'publicada'): ?>
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>task/apply/<?= $task->id ?>" class="btn btn-action">
                                <i class="fas fa-paper-plane me-2"></i>Postular a esta tarea
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Evidencia (si existe) -->
                <?php if (isset($evidence) && $evidence): ?>
                    <div class="task-card">
                        <h5><i class="fas fa-camera me-2"></i>Evidencia del Trabajo</h5>
                        <div class="mb-3">
                            <span class="badge bg-<?= $evidence->status === 'aprobada' ? 'success' : ($evidence->status === 'rechazada' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($evidence->status) ?>
                            </span>
                        </div>
                        
                        <p><strong>Descripción:</strong> <?= $evidence->description ?></p>
                        
                        <?php if ($evidence->image_path): ?>
                            <img src="<?= BASE_URL . $evidence->image_path ?>" class="img-fluid rounded mb-3" style="max-height: 300px;">
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['user_role'] === 'cliente' && $evidence->status === 'enviada'): ?>
                            <div class="d-flex gap-2">
                                <form method="POST" action="<?= BASE_URL ?>task/reviewEvidence" class="d-inline">
                                    <input type="hidden" name="evidence_id" value="<?= $evidence->id ?>">
                                    <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Aprobar
                                    </button>
                                </form>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>Rechazar
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <!-- Postulaciones (solo para clientes) -->
                <?php if ($_SESSION['user_role'] === 'cliente' && isset($applications) && !empty($applications)): ?>
                    <div class="task-card">
                        <h5><i class="fas fa-users me-2"></i>Postulantes (<?= count($applications) ?>)</h5>
                        
                        <?php foreach ($applications as $app): ?>
                            <div class="application-card">
                                <h6><?= $app->first_name ?> <?= $app->last_name ?></h6>
                                <p class="text-muted small"><?= substr($app->description, 0, 80) ?>...</p>
                                <p><strong>Propuesta:</strong> $<?= number_format($app->proposal_amount, 2) ?></p>
                                <p class="small">
                                    <i class="fas fa-star text-warning"></i> <?= number_format($app->average_rating, 1) ?>
                                    (<?= $app->total_jobs ?> trabajos)
                                </p>
                                
                                <?php if ($app->proposal_message): ?>
                                    <p class="small"><em>"<?= $app->proposal_message ?>"</em></p>
                                <?php endif; ?>
                                
                                <?php if ($task->status === 'publicada'): ?>
                                    <form method="POST" action="<?= BASE_URL ?>task/selectWorker">
                                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                        <input type="hidden" name="worker_id" value="<?= $app->worker_id ?>">
                                        <input type="hidden" name="application_id" value="<?= $app->id ?>">
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-check me-1"></i>Seleccionar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Información del cliente/trabajador -->
                <div class="task-card">
                    <h6><i class="fas fa-info-circle me-2"></i>Información</h6>
                    
                    <?php if ($_SESSION['user_role'] === 'cachuelero'): ?>
                        <p><strong>Cliente:</strong> <?= $task->first_name ?> <?= $task->last_name ?></p>
                        <?php if ($task->phone): ?>
                            <p><strong>Teléfono:</strong> <?= $task->phone ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p><strong>Estado:</strong> <?= ucfirst(str_replace('_', ' ', $task->status)) ?></p>
                        <p><strong>Presupuesto:</strong> $<?= number_format($task->budget, 2) ?></p>
                    <?php endif; ?>

                    <!-- Acciones según el estado -->
                    <?php if ($_SESSION['user_role'] === 'cachuelero' && $task->selected_worker_id == $_SESSION['user_id'] && $task->status === 'en_progreso'): ?>
                        <a href="<?= BASE_URL ?>task/submitEvidence/<?= $task->id ?>" class="btn btn-action w-100">
                            <i class="fas fa-camera me-2"></i>Enviar Evidencia
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar evidencia -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Evidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>task/reviewEvidence">
                    <div class="modal-body">
                        <input type="hidden" name="evidence_id" value="<?= $evidence->id ?? '' ?>">
                        <input type="hidden" name="task_id" value="<?= $task->id ?>">
                        <input type="hidden" name="action" value="reject">
                        
                        <div class="mb-3">
                            <label class="form-label">Explica por qué rechazas la evidencia:</label>
                            <textarea name="feedback" class="form-control" rows="4" required 
                                      placeholder="Describe qué necesita ser corregido o mejorado..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Rechazar Evidencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>