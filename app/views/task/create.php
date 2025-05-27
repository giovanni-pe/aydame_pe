<!-- app/views/task/create.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Tarea - CachueApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .create-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-secondary-custom:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .category-option {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .category-option:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }
        
        .category-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        .category-option input[type="radio"] {
            display: none;
        }
        
        .category-option i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin-bottom: 0;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h5 {
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .budget-input {
            position: relative;
        }
        
        .budget-input::before {
            content: '$';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-weight: bold;
            z-index: 5;
        }
        
        .budget-input input {
            padding-left: 35px;
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

    <div class="container">
        <div class="create-container">
            <!-- Header -->
            <div class="header">
                <i class="fas fa-plus-circle"></i>
                <h2>Crear Nueva Tarea</h2>
                <p class="text-muted">Describe tu tarea y recibe propuestas de cachueleros calificados</p>
            </div>

            <!-- Mensajes de error/éxito -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form method="POST" id="createTaskForm">
                <!-- Título -->
                <div class="form-section">
                    <h5><i class="fas fa-heading me-2"></i>Título de la tarea</h5>
                    <input type="text" name="title" class="form-control" placeholder="Ej: Limpieza general de casa" required maxlength="100">
                    <small class="text-muted">Sé específico y claro sobre lo que necesitas</small>
                </div>

                <!-- Categoría -->
                <div class="form-section">
                    <h5><i class="fas fa-tags me-2"></i>Categoría</h5>
                    <?php if (!empty($categories)): ?>
                        <div class="category-grid">
                            <?php foreach ($categories as $category): ?>
                                <div class="category-option" onclick="selectCategory(<?= $category->id ?>)">
                                    <input type="radio" name="category_id" value="<?= $category->id ?>" id="cat_<?= $category->id ?>" required>
                                    <i class="fas <?= $category->icon ?>"></i>
                                    <div class="fw-bold"><?= $category->name ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <select name="category_id" class="form-control" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="1">Limpieza</option>
                            <option value="2">Jardinería</option>
                            <option value="3">Plomería</option>
                            <option value="4">Electricidad</option>
                            <option value="5">Pintura</option>
                            <option value="6">Reparaciones</option>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- Descripción -->
                <div class="form-section">
                    <h5><i class="fas fa-align-left me-2"></i>Descripción detallada</h5>
                    <textarea name="description" class="form-control" rows="5" placeholder="Describe detalladamente qué necesitas, incluye información importante como horarios, materiales necesarios, etc." required></textarea>
                    <small class="text-muted">Mientras más detalles proporciones, mejores propuestas recibirás</small>
                </div>

                <!-- Presupuesto y Ubicación -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <h5><i class="fas fa-dollar-sign me-2"></i>Presupuesto máximo</h5>
                            <div class="budget-input">
                                <input type="number" name="budget" class="form-control" step="0.01" min="1" placeholder="0.00" required>
                            </div>
                            <small class="text-muted">Este será el monto máximo que pagarás</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-section">
                            <h5><i class="fas fa-map-marker-alt me-2"></i>Ubicación</h5>
                            <input type="text" name="location" class="form-control" placeholder="Ej: San Isidro, Lima" required>
                            <small class="text-muted">Distrito y ciudad donde se realizará el trabajo</small>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>¿Cómo funciona?</h6>
                    <ul class="mb-0">
                        <li>Tu tarea será visible para todos los cachueleros</li>
                        <li>Recibirás propuestas con diferentes precios</li>
                        <li>Podrás revisar el perfil y calificaciones de cada postulante</li>
                        <li>Selecciona al cachuelero que mejor se adapte a tus necesidades</li>
                        <li>El pago se realizará automáticamente al completar el trabajo</li>
                    </ul>
                </div>

                <!-- Botones -->
                <div class="d-flex gap-3 justify-content-center">
                    <a href="<?= BASE_URL ?>dashboard" class="btn-secondary-custom">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-create">
                        <i class="fas fa-paper-plane me-2"></i>Publicar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Selección de categoría
        function selectCategory(categoryId) {
            // Remover selección anterior
            document.querySelectorAll('.category-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Seleccionar nueva categoría
            const radio = document.getElementById('cat_' + categoryId);
            if (radio) {
                radio.checked = true;
                radio.closest('.category-option').classList.add('selected');
            }
        }

        // Validación del formulario
        document.getElementById('createTaskForm').addEventListener('submit', function(e) {
            const budget = parseFloat(document.querySelector('input[name="budget"]').value);
            const title = document.querySelector('input[name="title"]').value.trim();
            const description = document.querySelector('textarea[name="description"]').value.trim();
            
            if (budget <= 0) {
                e.preventDefault();
                alert('El presupuesto debe ser mayor a 0');
                return;
            }
            
            if (title.length < 10) {
                e.preventDefault();
                alert('El título debe tener al menos 10 caracteres');
                return;
            }
            
            if (description.length < 20) {
                e.preventDefault();
                alert('La descripción debe tener al menos 20 caracteres');
                return;
            }
            
            // Cambiar botón a estado de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Publicando...';
            submitBtn.disabled = true;
        });

        // Contador de caracteres para descripción
        const descriptionTextarea = document.querySelector('textarea[name="description"]');
        if (descriptionTextarea) {
            descriptionTextarea.addEventListener('input', function() {
                const current = this.value.length;
                const min = 20;
                
                let indicator = this.parentNode.querySelector('.char-indicator');
                if (!indicator) {
                    indicator = document.createElement('small');
                    indicator.className = 'char-indicator text-muted';
                    this.parentNode.appendChild(indicator);
                }
                
                if (current < min) {
                    indicator.textContent = `Necesitas al menos ${min - current} caracteres más`;
                    indicator.className = 'char-indicator text-warning';
                } else {
                    indicator.textContent = `${current} caracteres - ¡Perfecto!`;
                    indicator.className = 'char-indicator text-success';
                }
            });
        }
    </script>
</body>
</html>