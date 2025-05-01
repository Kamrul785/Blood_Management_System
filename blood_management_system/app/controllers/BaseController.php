<?php
class BaseController {
    protected $db;
    protected $view;
    protected $user;

    public function __construct() {
        // Initialize database connection
        $this->db = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Initialize user session
        $this->user = $_SESSION['user'] ?? null;
    }

    protected function render($view, $data = []) {
        // Extract data for the view
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        require_once BASE_PATH . '/app/views/' . $view . '.php';

        // Get the contents of the buffer
        $content = ob_get_clean();

        // Include the layout
        require_once BASE_PATH . '/app/views/layouts/main.php';
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            redirect('login');
        }
    }

    protected function requireAdmin() {
        $this->requireAuth();
        if ($this->user['role'] !== 'admin') {
            redirect('home');
        }
    }

    protected function validateCSRF() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF token validation failed');
        }
    }
} 