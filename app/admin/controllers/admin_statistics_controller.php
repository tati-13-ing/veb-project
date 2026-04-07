<?php
require_once 'app/admin/controllers/admin_controller.php';
require_once 'app/models/StatisticModel.php';

class AdminStatisticsController extends AdminController
{
    private $itemsPerPage = 20;
    
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $this->itemsPerPage;
        
        $totalRecords = StatisticModel::count();
        $totalPages = ceil($totalRecords / $this->itemsPerPage);
        
        $stats = StatisticModel::findPaginated($offset, $this->itemsPerPage, 'visit_datetime', 'DESC');
        
        $this->view->render('admin_statistics.php', 'Статистика посещений', [
            'stats' => $stats,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }
}