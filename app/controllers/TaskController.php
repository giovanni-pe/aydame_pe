<?php
// app/controllers/TaskController.php

class TaskController extends Controller {
    
    public function __construct() {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
            exit();
        }
        if (!isset($_SESSION['is_profile_complete']) || !$_SESSION['is_profile_complete']) {
            redirect('profile/complete');
            exit();
        }
    }
    
    public function create() {
        // Solo clientes pueden crear tareas
        if ($_SESSION['user_role'] !== 'cliente') {
            redirect('dashboard');
            exit();
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar datos básicos
            if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['budget']) || empty($_POST['category_id'])) {
                $data['error'] = 'Todos los campos obligatorios deben ser completados';
            } elseif ($_POST['budget'] <= 0) {
                $data['error'] = 'El presupuesto debe ser mayor a 0';
            } else {
                $taskModel = $this->model('Task');
                $_POST['client_id'] = $_SESSION['user_id'];
                
                $taskId = $taskModel->create($_POST);
                if ($taskId) {
                    redirect('task/detail/' . $taskId);
                    exit();
                } else {
                    $data['error'] = 'Error al crear la tarea';
                }
            }
        }
        
        // Obtener categorías para el formulario
        try {
            $taskModel = $this->model('Task');
            $data['categories'] = $taskModel->getCategories();
        } catch (Exception $e) {
            $data['categories'] = [];
        }
        
        $this->renderTaskView('task/create', $data);
    }
    
    public function detail($id) {
        if (empty($id)) {
            redirect('dashboard');
            exit();
        }
        
        try {
            $taskModel = $this->model('Task');
            $data['task'] = $taskModel->getTaskById($id);
            
            if (!$data['task']) {
                redirect('dashboard');
                exit();
            }
            
            // Solo el cliente puede ver las postulaciones
            if ($_SESSION['user_role'] === 'cliente' && $data['task']->client_id == $_SESSION['user_id']) {
                $applicationModel = $this->model('TaskApplication');
                $data['applications'] = $applicationModel->getTaskApplications($id);
            } else {
                $data['applications'] = [];
            }
            
            // Verificar si hay evidencia
            try {
                $evidenceModel = $this->model('TaskEvidence');
                $data['evidence'] = $evidenceModel->getTaskEvidence($id);
            } catch (Exception $e) {
                $data['evidence'] = null;
            }
            
        } catch (Exception $e) {
            redirect('dashboard');
            exit();
        }
        
        $this->renderTaskView('task/detail', $data);
    }
    
    public function apply($taskId) {
        // Solo cachueleros pueden postular
        if ($_SESSION['user_role'] !== 'cachuelero') {
            redirect('dashboard');
            exit();
        }
        
        if (empty($taskId)) {
            redirect('dashboard');
            exit();
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['proposal_amount']) || $_POST['proposal_amount'] <= 0) {
                $data['error'] = 'Debe ingresar una propuesta válida';
            } else {
                try {
                    $applicationModel = $this->model('TaskApplication');
                    $_POST['task_id'] = $taskId;
                    $_POST['worker_id'] = $_SESSION['user_id'];
                    
                    if ($applicationModel->apply($_POST)) {
                        redirect('task/detail/' . $taskId . '?applied=1');
                        exit();
                    } else {
                        $data['error'] = 'Error al enviar postulación o ya has postulado a esta tarea';
                    }
                } catch (Exception $e) {
                    $data['error'] = 'Error al procesar la postulación';
                }
            }
        }
        
        // Obtener información de la tarea
        try {
            $taskModel = $this->model('Task');
            $data['task'] = $taskModel->getTaskById($taskId);
            
            if (!$data['task'] || $data['task']->status !== 'publicada') {
                redirect('dashboard');
                exit();
            }
        } catch (Exception $e) {
            redirect('dashboard');
            exit();
        }
        
        $this->renderTaskView('task/apply', $data);
    }
    
    public function selectWorker() {
        // Solo clientes pueden seleccionar trabajadores
        if ($_SESSION['user_role'] !== 'cliente') {
            redirect('dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $taskId = $_POST['task_id'] ?? '';
            $workerId = $_POST['worker_id'] ?? '';
            $applicationId = $_POST['application_id'] ?? '';
            
            if (empty($taskId) || empty($workerId) || empty($applicationId)) {
                redirect('dashboard');
                exit();
            }
            
            try {
                $taskModel = $this->model('Task');
                
                if ($taskModel->selectWorker($taskId, $workerId, $applicationId)) {
                    redirect('task/detail/' . $taskId . '?selected=1');
                } else {
                    redirect('task/detail/' . $taskId . '?error=1');
                }
            } catch (Exception $e) {
                redirect('dashboard');
            }
        } else {
            redirect('dashboard');
        }
    }
    
    public function submitEvidence($taskId) {
        // Solo cachueleros pueden enviar evidencia
        if ($_SESSION['user_role'] !== 'cachuelero') {
            redirect('dashboard');
            exit();
        }
        
        if (empty($taskId)) {
            redirect('dashboard');
            exit();
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['description'])) {
                $data['error'] = 'La descripción es obligatoria';
            } else {
                try {
                    $evidenceModel = $this->model('TaskEvidence');
                    
                    // Manejar imagen si se subió
                    $imagePath = null;
                    if (isset($_FILES['evidence_image']) && $_FILES['evidence_image']['error'] == 0) {
                        $uploadDir = 'uploads/evidence/';
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $imagePath = $uploadDir . time() . '_' . $_FILES['evidence_image']['name'];
                        if (!move_uploaded_file($_FILES['evidence_image']['tmp_name'], $imagePath)) {
                            $imagePath = null;
                        }
                    }
                    
                    $evidenceData = [
                        'task_id' => $taskId,
                        'worker_id' => $_SESSION['user_id'],
                        'description' => $_POST['description'],
                        'image_path' => $imagePath
                    ];
                    
                    if ($evidenceModel->submitEvidence($evidenceData)) {
                        redirect('task/detail/' . $taskId . '?evidence_sent=1');
                        exit();
                    } else {
                        $data['error'] = 'Error al enviar la evidencia';
                    }
                } catch (Exception $e) {
                    $data['error'] = 'Error al procesar la evidencia';
                }
            }
        }
        
        // Obtener información de la tarea
        try {
            $taskModel = $this->model('Task');
            $data['task'] = $taskModel->getTaskById($taskId);
            
            if (!$data['task'] || $data['task']->selected_worker_id != $_SESSION['user_id']) {
                redirect('dashboard');
                exit();
            }
        } catch (Exception $e) {
            redirect('dashboard');
            exit();
        }
        
        $this->renderTaskView('task/evidence', $data);
    }
    
    public function reviewEvidence() {
        // Solo clientes pueden revisar evidencia
        if ($_SESSION['user_role'] !== 'cliente') {
            redirect('dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';
            $evidenceId = $_POST['evidence_id'] ?? '';
            $taskId = $_POST['task_id'] ?? '';
            
            if (empty($action) || empty($evidenceId) || empty($taskId)) {
                redirect('dashboard');
                exit();
            }
            
            try {
                $evidenceModel = $this->model('TaskEvidence');
                $taskModel = $this->model('Task');
                $userModel = $this->model('User');
                
                if ($action === 'approve') {
                    $evidenceModel->approveEvidence($evidenceId, $taskId);
                    
                    // Transferir pago
                    $task = $taskModel->getTaskById($taskId);
                    if ($task) {
                        $userModel->updateWallet($task->selected_worker_id, $task->budget, 'pago_recibido', 'Pago por tarea: ' . $task->title, $taskId);
                        $userModel->updateWallet($_SESSION['user_id'], -$task->budget, 'pago_enviado', 'Pago por tarea: ' . $task->title, $taskId);
                    }
                    
                    redirect('task/rate/' . $taskId);
                } else {
                    $feedback = $_POST['feedback'] ?? 'Evidencia rechazada';
                    $evidenceModel->rejectEvidence($evidenceId, $feedback);
                    redirect('task/detail/' . $taskId . '?evidence_rejected=1');
                }
            } catch (Exception $e) {
                redirect('dashboard');
            }
        } else {
            redirect('dashboard');
        }
    }
    
    public function rate($taskId) {
        // Solo clientes pueden calificar
        if ($_SESSION['user_role'] !== 'cliente') {
            redirect('dashboard');
            exit();
        }
        
        if (empty($taskId)) {
            redirect('dashboard');
            exit();
        }
        
        $data = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rating = $_POST['rating'] ?? '';
            $comment = $_POST['comment'] ?? '';
            
            if (empty($rating) || $rating < 1 || $rating > 5) {
                $data['error'] = 'Debe seleccionar una calificación válida (1-5)';
            } else {
                try {
                    $ratingModel = $this->model('Rating');
                    $taskModel = $this->model('Task');
                    
                    $task = $taskModel->getTaskById($taskId);
                    if ($task && $task->client_id == $_SESSION['user_id']) {
                        $ratingData = [
                            'task_id' => $taskId,
                            'client_id' => $_SESSION['user_id'],
                            'worker_id' => $task->selected_worker_id,
                            'rating' => $rating,
                            'comment' => $comment
                        ];
                        
                        if ($ratingModel->rateWorker($ratingData)) {
                            redirect('dashboard?task_completed=1');
                            exit();
                        } else {
                            $data['error'] = 'Error al guardar la calificación';
                        }
                    } else {
                        redirect('dashboard');
                        exit();
                    }
                } catch (Exception $e) {
                    $data['error'] = 'Error al procesar la calificación';
                }
            }
        }
        
        // Obtener información de la tarea
        try {
            $taskModel = $this->model('Task');
            $data['task'] = $taskModel->getTaskById($taskId);
            
            if (!$data['task'] || $data['task']->client_id != $_SESSION['user_id'] || $data['task']->status !== 'completada') {
                redirect('dashboard');
                exit();
            }
        } catch (Exception $e) {
            redirect('dashboard');
            exit();
        }
        
        $this->renderTaskView('task/rate', $data);
    }
    
    // Método para renderizar vistas de tareas
    private function renderTaskView($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            extract($data);
            include '../app/views/' . $view . '.php';
        } else {
            echo "<h1>Vista no encontrada</h1>";
            echo "<p>La vista $view no existe.</p>";
            echo "<a href='" . BASE_URL . "dashboard'>Volver al Dashboard</a>";
        }
    }
}