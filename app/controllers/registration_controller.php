<?php
require_once 'app/models/UserModel.php';

class RegistrationController extends Controller
{
    public function index()
    {
        $this->logPageVisit();
        $this->view->render('pages/registration.php', 'Регистрация', [
            'errors' => $_SESSION['reg_errors'] ?? [],
            'old' => $_SESSION['reg_old'] ?? []
        ]);
        unset($_SESSION['reg_errors'], $_SESSION['reg_old']);
    }
    
    public function register()
    {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        $errors = [];
        
        if (empty($fullName)) $errors[] = 'ФИО обязательно';
        if (empty($email)) $errors[] = 'E-mail обязателен';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный E-mail';
        if (empty($login)) $errors[] = 'Логин обязателен';
        if (empty($password)) $errors[] = 'Пароль обязателен';
        if ($password !== $passwordConfirm) $errors[] = 'Пароли не совпадают';
        if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
        
        // Проверка уникальности логина
        if (UserModel::loginExists($login)) {
            $errors[] = 'Логин уже занят';
        }
        
        if (!empty($errors)) {
            $_SESSION['reg_errors'] = $errors;
            $_SESSION['reg_old'] = ['full_name' => $fullName, 'email' => $email, 'login' => $login];
            header('Location: /registration');
            exit;
        }
        
        $user = new UserModel();
        $user->full_name = $fullName;
        $user->email = $email;
        $user->login = $login;
        $user->setPassword($password);
        $user->save();
        
        $_SESSION['reg_success'] = true;
        header('Location: /authorization');
        exit;
    }
}