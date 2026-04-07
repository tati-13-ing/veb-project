<?php

class GuestbookController extends Controller
{
public function index()
{
    $this->logPageVisit();
    // Сортируем сначала по created_at, затем по id (новые сверху)
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
        header('Location: /admin/guestbook/upload');
        exit;
    }
    
    
  
}