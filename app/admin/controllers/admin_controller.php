<?php
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->view->setAdminPrefix('admin/');
        $this->authenticate();
    }

    protected function authenticate()
    {
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
            header('Location: /admin/auth');
            exit;
        }
    }
}