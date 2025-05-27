<?php 


class DashboardController extends Controller {
    
    public function __construct() {
        // Verificaciones básicas sin bucles
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
            exit();
        }
        if (!isset($_SESSION['is_profile_complete']) || !$_SESSION['is_profile_complete']) {
            redirect('profile/complete');
            exit();
        }
    }
    
    public function index() {
        try {
            $userModel = $this->model('User');
            $taskModel = $this->model('Task');
            
            // Datos básicos siempre necesarios
            $data = [];
            $data['user'] = $userModel->getUserById($_SESSION['user_id']);
            $data['tasks'] = [];
            $data['available_tasks'] = [];
            
            // Obtener tareas del usuario
            try {
                $data['tasks'] = $taskModel->getUserTasks($_SESSION['user_id'], $_SESSION['user_role']);
            } catch (Exception $e) {
                $data['tasks'] = [];
            }
            
            // Si es cachuelero, obtener tareas disponibles
            if ($_SESSION['user_role'] === 'cachuelero') {
                try {
                    $data['available_tasks'] = $taskModel->getAvailableTasks();
                } catch (Exception $e) {
                    $data['available_tasks'] = [];
                }
            }
            
            // Renderizar vista directamente
            $this->renderDashboardView('dashboard/index', $data);
            
        } catch (Exception $e) {
            // En caso de error, mostrar dashboard básico
            $data = [
                'user' => (object)['first_name' => 'Usuario', 'wallet_balance' => 0],
                'tasks' => [],
                'available_tasks' => []
            ];
            $this->renderDashboardView('dashboard/index', $data);
        }
    }
    
    // Método para renderizar dashboard sin layout complejo
    private function renderDashboardView($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            extract($data);
            include '../app/views/' . $view . '.php';
        } else {
            echo "<h1>Dashboard</h1>";
            echo "<p>¡Bienvenido a CachueApp!</p>";
            echo "<a href='" . BASE_URL . "auth/logout'>Cerrar Sesión</a>";
        }
    }
}