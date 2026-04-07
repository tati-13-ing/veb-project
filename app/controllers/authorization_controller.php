<?php
require_once 'app/models/UserModel.php';

class AuthorizationController extends Controller
{
    public function index()
    {
        $this->logPageVisit();
        // Если уже авторизован, перенаправляем на главную
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        $this->view->render('pages/authorization.php', 'Вход', [
            'error' => $_SESSION['auth_error'] ?? null
        ]);
        unset($_SESSION['auth_error']);
    }
    
    public function login()
    {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $user = UserModel::findByLogin($login);
        if ($user && $user->verifyPassword($password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->full_name;
            header('Location: /');
            exit;
        } else {
            $_SESSION['auth_error'] = 'Неверный логин или пароль';
            header('Location: /authorization');
            exit;
        }
    }
    
   public function logout()
    {
        unset($_SESSION['user_id'], $_SESSION['user_name']);
        header('Location: /');
        exit;
    }
}