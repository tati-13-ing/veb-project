<?php
class Controller {
    public $view;
    
    public function __construct() {
        $this->view = new View();
    }
    
    /**
     * Логирование посещения страницы (только для публичных страниц, не для админки)
     */
    protected function logPageVisit()
    {
        // Не логируем AJAX-запросы и POST-запросы
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') return;
        // Не логируем админку
        if (strpos($_SERVER['REQUEST_URI'], '/admin/') === 0) return;
        
        require_once 'app/models/StatisticModel.php';
        $stat = new StatisticModel();
        $stat->page_url = $_SERVER['REQUEST_URI'];
        $stat->ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $stat->host_name = gethostbyaddr($stat->ip_address);
        $stat->browser_name = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $stat->save();
    }
}