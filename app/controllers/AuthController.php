<?php 
class AuthController extends Controller {
    
    public function __construct() {
        // No requerir login para auth controller
    }
    
    public function login() {
        // Si ya está logueado, redirigir
        if (isset($_SESSION['user_id'])) {
            if (!$_SESSION['is_profile_complete']) {
                redirect('profile/complete');
            } else {
                redirect('dashboard');
            }
            return;
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->login($_POST['username'], $_POST['password']);
            
            if ($user) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['is_profile_complete'] = $user->is_profile_complete;
                
                if (!$user->is_profile_complete) {
                    redirect('profile/complete');
                } else {
                    redirect('dashboard');
                }
                return;
            } else {
                $data['error'] = 'Credenciales incorrectas';
            }
        }
        
        // Renderizar vista sin layout
        $this->renderAuthView('auth/login', $data);
    }
    
    public function register() {
        // Si ya está logueado, redirigir
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
            return;
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar datos
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['role'])) {
                $data['error'] = 'Todos los campos son obligatorios';
            } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                $data['error'] = 'Las contraseñas no coinciden';
            } else {
                $userModel = $this->model('User');
                $userId = $userModel->register($_POST);
                
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_role'] = $_POST['role'];
                    $_SESSION['is_profile_complete'] = false;
                    redirect('profile/complete');
                    return;
                } else {
                    $data['error'] = 'Error al registrar usuario. El usuario o email ya existe.';
                }
            }
        }
        
        // Renderizar vista sin layout
        $this->renderAuthView('auth/register', $data);
    }
    
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruir la sesión
        session_destroy();
        
        redirect('auth/login');
    }
    
    // Método para renderizar vistas de auth sin layout
    private function renderAuthView($view, $data = []) {
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