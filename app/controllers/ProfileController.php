<?php
// app/controllers/ProfileController.php (CORREGIDO)

class ProfileController extends Controller {
    
    public function __construct() {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        }
    }
    
    public function complete() {
        // Si el perfil ya está completo, redirigir al dashboard
        if (isset($_SESSION['is_profile_complete']) && $_SESSION['is_profile_complete']) {
            redirect('dashboard');
            return;
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $profileModel = $this->model('UserProfile');
            $_POST['user_id'] = $_SESSION['user_id'];
            
            if ($profileModel->createProfile($_POST)) {
                $_SESSION['is_profile_complete'] = true;
                $data['success'] = 'Perfil completado exitosamente';
                $data['show_confirmation'] = true;
            } else {
                $data['error'] = 'Error al completar perfil';
            }
        }
        
        // Renderizar vista sin layout (como auth)
        $this->renderProfileView('profile/complete', $data);
    }
    
    public function edit() {
        // Verificar que el perfil esté completo
        if (!isset($_SESSION['is_profile_complete']) || !$_SESSION['is_profile_complete']) {
            redirect('profile/complete');
            return;
        }
        
        $profileModel = $this->model('UserProfile');
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['user_id'] = $_SESSION['user_id'];
            
            if ($profileModel->updateProfile($_POST)) {
                $data['success'] = 'Perfil actualizado exitosamente';
            } else {
                $data['error'] = 'Error al actualizar perfil';
            }
        }
        
        $data['profile'] = $profileModel->getProfile($_SESSION['user_id']);
        $this->view('profile/edit', $data);
    }
    
    public function dashboard() {
        // Verificar que el perfil esté completo
        if (!isset($_SESSION['is_profile_complete']) || !$_SESSION['is_profile_complete']) {
            redirect('profile/complete');
            return;
        }
        
        $userModel = $this->model('User');
        $taskModel = $this->model('Task');
        
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);
        $data['tasks'] = $taskModel->getUserTasks($_SESSION['user_id'], $_SESSION['user_role']);
        
        // Si es cachuelero, mostrar tareas disponibles
        if ($_SESSION['user_role'] === 'cachuelero') {
            $data['available_tasks'] = $taskModel->getAvailableTasks();
        } else {
            $data['available_tasks'] = [];
        }
        
        $this->view('dashboard/index', $data);
    }
    
    // Método para renderizar vistas de perfil sin layout (como complete)
    private function renderProfileView($view, $data = []) {
        // Verificar si existe la vista
        if (file_exists('../app/views/' . $view . '.php')) {
            // Extraer datos
            extract($data);
            
            // Incluir vista directamente (sin layout)
            include '../app/views/' . $view . '.php';
        } else {
            die('Vista no encontrada: ' . $view);
        }
    }
}

