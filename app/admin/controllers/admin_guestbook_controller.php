<?php
require_once 'app/admin/controllers/admin_controller.php';
require_once 'app/models/GuestbookModel.php';

class AdminGuestbookController extends AdminController
{
    public function upload()
    {
        $this->view->render('pages/guestbook_upload.php', 'Загрузка сообщений гостевой книги', [
            'errors' => $_SESSION['gb_errors'] ?? [],
            'success' => $_SESSION['gb_success'] ?? null
        ]);
        unset($_SESSION['gb_errors'], $_SESSION['gb_success']);
    }
    
    public function processUpload()
    {
        if (!isset($_FILES['messages_file']) || $_FILES['messages_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['gb_errors'] = ['Ошибка загрузки файла'];
            header('Location: /admin/guestbook/upload');
            exit;
        }
        
        $file = $_FILES['messages_file']['tmp_name'];
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            $_SESSION['gb_errors'] = ['Файл пуст или не удалось прочитать'];
            header('Location: /admin/guestbook/upload');
            exit;
        }
        
        $count = 0;
        $errors = [];
        foreach ($lines as $line) {
            $parts = explode(';', $line);
            if (count($parts) < 4) {
                $errors[] = 'Пропущена строка: недостаточно полей';
                continue;
            }
            $date = trim($parts[0]);
            $fullName = trim($parts[1]);
            $email = trim($parts[2]);
            $message = trim($parts[3]);
            
            // Разбор ФИО
            $nameParts = explode(' ', $fullName);
            $lastName = $nameParts[0] ?? '';
            $firstName = $nameParts[1] ?? '';
            $middleName = $nameParts[2] ?? '';
            
            if (empty($lastName) || empty($firstName) || empty($email) || empty($message)) {
                $errors[] = 'Пропущена строка: обязательные поля пусты';
                continue;
            }
            
            $entry = new GuestbookModel();
            $entry->date_added = formatDateForMySQL($date);
            $entry->last_name = $lastName;
            $entry->first_name = $firstName;
            $entry->middle_name = $middleName;
            $entry->email = $email;
            $entry->message = $message;
            $entry->save();
            $count++;
        }
        
        $_SESSION['gb_success'] = $count;
        if (!empty($errors)) $_SESSION['gb_errors'] = $errors;
        header('Location: /admin/guestbook/upload');
        exit;
    }
}