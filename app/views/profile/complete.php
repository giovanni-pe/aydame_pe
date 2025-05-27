<!-- app/views/profile/complete.php (SIMPLIFICADA) -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - CachueApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        
        .profile-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .btn-complete {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .confirmation-card {
            text-align: center;
            padding: 3rem 2rem;
        }
        
        .confirmation-card .check-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <?php if (isset($show_confirmation) && $show_confirmation): ?>
                <!-- Confirmación -->
                <div class="confirmation-card">
                    <i class="fas fa-check-circle check-icon"></i>
                    <h3>¡Perfil Completado!</h3>
                    <p>Tu perfil ha sido registrado exitosamente.</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= BASE_URL ?>profile/edit" class="btn btn-secondary">
                            <i class="fas fa-edit me-2"></i>Editar Perfil
                        </a>
                        <a href="<?= BASE_URL ?>dashboard" class="btn btn-complete">
                            <i class="fas fa-arrow-right me-2"></i>Continuar
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Formulario -->
                <div class="header">
                    <i class="fas fa-user-edit"></i>
                    <h2>Completar Perfil</h2>
                    <p>Complete todos los campos para continuar</p>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Describe tus habilidades..."></textarea>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">DNI *</label>
                            <input type="text" name="dni" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Ciudad *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                    </div>

                    <label class="form-label">Teléfono Opcional</label>
                    <input type="tel" name="optional_phone" class="form-control">

                    <button type="submit" class="btn btn-complete w-100 mt-3">
                        <i class="fas fa-save me-2"></i>Completar Perfil
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>