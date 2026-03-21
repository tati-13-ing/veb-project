<?php

class GuestbookController extends Controller
{
    public function index()
    {
        // Получаем все сообщения, отсортированные по дате добавления (новые сверху)
        $messages = GuestbookModel::findAll('created_at', 'DESC');
        
        $this->view->render('pages/guestbook.php', 'Гостевая книга', [
            'messages' => $messages
        ]);
    }
    
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /guestbook');
            exit;
        }
        
        $guestbook = new GuestbookModel();
        $guestbook->date_added = date('Y-m-d'); // MySQL формат YYYY-MM-DD
        $guestbook->last_name = $_POST['last_name'] ?? '';
        $guestbook->first_name = $_POST['first_name'] ?? '';
        $guestbook->middle_name = $_POST['middle_name'] ?? '';
        $guestbook->email = $_POST['email'] ?? '';
        $guestbook->message = $_POST['message'] ?? '';
        $guestbook->save();
        
        header('Location: /guestbook?success=1');
        exit;
    }
        public function upload()
    {
        $this->view->render('pages/guestbook_upload.php', 'Загрузка сообщений гостевой книги');
    }
    
    public function processUpload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /guestbook/upload');
            exit;
        }
        
        $errors = [];
        $success = 0;
        
        if (!isset($_FILES['messages_file']) || $_FILES['messages_file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Ошибка загрузки файла.';
        } else {
            $file = $_FILES['messages_file'];
            $filePath = $file['tmp_name'];
            
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($extension !== 'inc') {
                $errors[] = 'Допустим только формат .inc';
            } else {
                $content = file_get_contents($filePath);
                $lines = explode("\n", $content);
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) {
                        continue;
                    }
                    
                    $parts = explode(';', $line);
                    if (count($parts) >= 4) {
                        $date = trim($parts[0]);
                        $fullName = trim($parts[1]);
                        $email = trim($parts[2]);
                        $message = trim($parts[3]);
                        
                        // ПРЕОБРАЗОВАНИЕ ДАТЫ из DD.MM.YYYY в YYYY-MM-DD
                        $dateFormatted = '';
                        if (!empty($date)) {
                            $dateParts = explode('.', $date);
                            if (count($dateParts) == 3) {
                                $day = $dateParts[0];
                                $month = $dateParts[1];
                                $year = $dateParts[2];
                                $dateFormatted = "$year-$month-$day";
                            } else {
                                $dateFormatted = date('Y-m-d');
                            }
                        } else {
                            $dateFormatted = date('Y-m-d');
                        }
                        
                        // Разбираем ФИО
                        $nameParts = explode(' ', $fullName);
                        $lastName = $nameParts[0] ?? '';
                        $firstName = $nameParts[1] ?? '';
                        $middleName = $nameParts[2] ?? '';
                        
                        try {
                            $guestbook = new GuestbookModel();
                            $guestbook->date_added = $dateFormatted; // Используем преобразованную дату
                            $guestbook->last_name = $lastName;
                            $guestbook->first_name = $firstName;
                            $guestbook->middle_name = $middleName;
                            $guestbook->email = $email;
                            $guestbook->message = $message;
                            $guestbook->save();
                            $success++;
                        } catch (Exception $e) {
                            $errors[] = "Ошибка при сохранении: " . $e->getMessage();
                        }
                    } else {
                        $errors[] = "Некорректный формат строки: " . htmlspecialchars($line);
                    }
                }
            }
    }
        
        $this->view->render('pages/guestbook_upload.php', 'Загрузка сообщений гостевой книги', [
            'errors' => $errors,
            'success' => $success
        ]);
    }
  
}