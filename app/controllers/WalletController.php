<?php 
class WalletController extends Controller {
    
    public function recharge() {
        if (!isset($_SESSION['user_id'])) redirect('auth/login');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $amount = floatval($_POST['amount']);
            
            if ($amount > 0) {
                if ($userModel->updateWallet($_SESSION['user_id'], $amount, 'recarga', 'Recarga de saldo')) {
                    redirect('wallet/recharge?success=1');
                } else {
                    $data['error'] = 'Error al recargar saldo';
                }
            } else {
                $data['error'] = 'Monto inválido';
            }
        }
        
        $userModel = $this->model('User');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);
        
        if (isset($_GET['success'])) {
            $data['success'] = 'Saldo recargado exitosamente';
        }
        
        $this->view('wallet/recharge', $data ?? []);
    }
    
    public function history() {
        if (!isset($_SESSION['user_id'])) redirect('auth/login');
        
        $transactionModel = $this->model('WalletTransaction');
        $data['transactions'] = $transactionModel->getUserTransactions($_SESSION['user_id']);
        
        $userModel = $this->model('User');
        $data['user'] = $userModel->getUserById($_SESSION['user_id']);
        
        $this->view('wallet/history', $data);
    }
}