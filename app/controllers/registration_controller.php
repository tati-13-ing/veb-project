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
    public function checklogin()
    {
        header('Content-Type: application/json; charset=utf-8');

        $login = trim($_POST['login'] ?? '');

        $result = [
            'available' => false,
            'message' => 'Введите логин для проверки.'
        ];

        if ($login !== '') {
            if (mb_strlen($login) < 3) {
                $result['message'] = 'Логин должен содержать минимум 3 символа.';
            } else {
                $exists = UserModel::loginExists($login);
                $result['available'] = !$exists;
                $result['message'] = $exists
                    ? 'Этот логин уже занят.'
                    : 'Логин свободен.';
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
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