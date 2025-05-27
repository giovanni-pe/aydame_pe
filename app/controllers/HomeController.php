<?php
// app/controllers/HomeController.php
class HomeController extends Controller {
    
    public function index() {
        // Redirigir según el estado del usuario
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
        } elseif (!$_SESSION['is_profile_complete']) {
            redirect('profile/complete');
        } else {
            redirect('profile/dashboard');
        }
    }
}
