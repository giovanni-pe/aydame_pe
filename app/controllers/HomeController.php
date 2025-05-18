<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    
    public function index() {
        // Get recent tasks
        $taskModel = $this->model('Task');
        $recentTasks = $taskModel->getRecentTasks();
        
        // Get user stats
        $userModel = $this->model('User');
        $userStats = $userModel->getUserStats();
        
        // Get highlight item
        $highlightItem = [
            'title' => 'Destellos algo',
            'code' => 'F146',
            'price' => 575,
            'image' => 'food-bowl.png'
        ];
        
        // Pass data to view
        $data = [
            'pageTitle' => 'Sarah - Dashboard',
            'recentTasks' => $recentTasks,
            'userStats' => $userStats,
            'highlightItem' => $highlightItem
        ];
        
        // Render view
        $this->view('home/index', $data);
    }
}
