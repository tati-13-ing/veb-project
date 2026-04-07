<?php
require_once 'app/admin/controllers/admin_controller.php';

class AdminAuthController extends Controller
{
    public function index()
    {
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
            header('Location: /admin/blog/editor');
            exit;
        }
        $this->view->render('admin_login.php', 'Вход в панель администратора');
    }
    
    public function login()
    {
        
        $login = trim($_POST['login'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Для отладки: раскомментировать, чтобы увидеть хеш пароля
        // echo md5($password); exit;
        
        if ($login === 'admin@gmail.com' && md5($password) === 'd8578edf8458ce06fbc5bb76a58c5ca4') {
            $_SESSION['isAdmin'] = true;
            header('Location: /admin/blog/editor');
            exit;
        } else {
            $_SESSION['admin_login_error'] = 'Неверный логин или пароль';
            header('Location: /admin/auth');  // ← исправлено: было /admin/login
            exit;
        }
    }
    
    public function logout()
    {
        unset($_SESSION['isAdmin']);
        header('Location: /');
        exit;
    }
}