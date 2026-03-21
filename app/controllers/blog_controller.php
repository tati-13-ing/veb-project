<?php

class BlogController extends Controller
{
    // Страница "Мой блог" - для пользователей (только просмотр)
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Количество записей на странице (по вашему варианту)
        $perPage = 5;
        
        $offset = ($page - 1) * $perPage;
        $posts = BlogPostModel::findPaginated($offset, $perPage, 'created_at', 'DESC');
        $total = BlogPostModel::count();
        $totalPages = ceil($total / $perPage);
        
        $this->view->render('pages/blog.php', 'Мой блог', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $total,
            'isAdmin' => false  // флаг для отличия режимов
        ]);
    }
    
    // Страница "Редактор блога" - для администратора
    public function editor()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10; // в админке больше записей на странице
        
        $offset = ($page - 1) * $perPage;
        $posts = BlogPostModel::findPaginated($offset, $perPage, 'created_at', 'DESC');
        $total = BlogPostModel::count();
        $totalPages = ceil($total / $perPage);
        
        $this->view->render('pages/blog_editor.php', 'Редактор блога', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $total,
            'isAdmin' => true  // флаг для отличия режимов
        ]);
    }
    
    // Сохранение новой записи
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /blog/editor');
            exit;
        }
        
        // Валидация с использованием FormValidation
        $validator = new FormValidation();
        $validator->setRule('title', 'isNotEmpty');
        $validator->setRule('message', 'isNotEmpty');
        
        if (!$validator->validate($_POST)) {
            $errors = $validator->getErrors();
            $posts = BlogPostModel::findAll('created_at', 'DESC');
            $total = BlogPostModel::count();
            $totalPages = ceil($total / 10);
            
            $this->view->render('pages/blog_editor.php', 'Редактор блога', [
                'posts' => $posts,
                'errors' => $errors,
                'oldData' => $_POST,
                'currentPage' => 1,
                'totalPages' => $totalPages,
                'totalPosts' => $total,
                'isAdmin' => true
            ]);
            return;
        }
        
        // Создаем новую запись
        $post = new BlogPostModel();
        $post->title = $_POST['title'];
        $post->message = $_POST['message'];
        $post->author = $_POST['author'] ?? 'Администратор';
        
        // Обработка загрузки изображения
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/blog/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extension, $allowed)) {
                $filename = uniqid() . '.' . $extension;
                $uploadPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $post->image_path = 'uploads/blog/' . $filename;
                }
            }
        }
        
        $post->save();
        
        header('Location: /blog/editor?success=1');
        exit;
    }
    
    // Редактирование записи
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            header('Location: /blog/editor');
            exit;
        }
        
        $post = BlogPostModel::find($id);
        
        if (!$post) {
            header('Location: /blog/editor');
            exit;
        }
        
        $this->view->render('pages/blog_edit.php', 'Редактирование записи', [
            'post' => $post
        ]);
    }
    
    // Обновление записи
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /blog/editor');
            exit;
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            header('Location: /blog/editor');
            exit;
        }
        
        // Валидация
        $validator = new FormValidation();
        $validator->setRule('title', 'isNotEmpty');
        $validator->setRule('message', 'isNotEmpty');
        
        if (!$validator->validate($_POST)) {
            $errors = $validator->getErrors();
            $post = BlogPostModel::find($id);
            $this->view->render('pages/blog_edit.php', 'Редактирование записи', [
                'post' => $post,
                'errors' => $errors
            ]);
            return;
        }
        
        $post = BlogPostModel::find($id);
        
        if ($post) {
            $post->title = $_POST['title'];
            $post->message = $_POST['message'];
            $post->author = $_POST['author'] ?? $post->author;
            
            // Обработка нового изображения
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/blog/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($extension, $allowed)) {
                    // Удаляем старое изображение
                    if (!empty($post->image_path) && file_exists('public/' . $post->image_path)) {
                        unlink('public/' . $post->image_path);
                    }
                    
                    $filename = uniqid() . '.' . $extension;
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $post->image_path = 'uploads/blog/' . $filename;
                    }
                }
            }
            
            $post->save();
        }
        
        header('Location: /blog/editor?updated=1');
        exit;
    }
    
    // Удаление записи
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id > 0) {
            $post = BlogPostModel::find($id);
            
            if ($post) {
                // Удаляем изображение
                if (!empty($post->image_path) && file_exists('public/' . $post->image_path)) {
                    unlink('public/' . $post->image_path);
                }
                
                $post->delete();
            }
        }
        
        header('Location: /blog/editor?deleted=1');
        exit;
    }
    // Добавьте эти методы в конец класса BlogController в файле app/controllers/blog_controller.php

// Страница загрузки CSV файла для блога
public function upload()
{
    $this->view->render('pages/blog_upload.php', 'Загрузка записей блога', [
        'errors' => [],
        'success' => null
    ]);
}

// Обработка загрузки CSV файла
public function processUpload()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /blog/upload');
        exit;
    }
    
    $errors = [];
    $success = 0;
    
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Ошибка загрузки файла.';
    } else {
        $file = $_FILES['csv_file'];
        $filePath = $file['tmp_name'];
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension !== 'csv') {
            $errors[] = 'Допустим только формат .csv';
        } else {
            $content = file_get_contents($filePath);
            // Поддержка разных разделителей: запятая или точка с запятой
            $lines = explode("\n", $content);
            $firstLine = true;
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }
                
                // Пропускаем заголовок
                if ($firstLine && (strpos($line, 'title') !== false || strpos($line, 'заголовок') !== false)) {
                    $firstLine = false;
                    continue;
                }
                $firstLine = false;
                
                // Определяем разделитель
                $delimiter = (strpos($line, ';') !== false) ? ';' : ',';
                $parts = str_getcsv($line, $delimiter);
                
                if (count($parts) >= 2) {
                    $title = trim($parts[0]);
                    $message = trim($parts[1]);
                    $author = isset($parts[2]) ? trim($parts[2]) : 'Администратор';
                    $created_at = isset($parts[3]) ? trim($parts[3]) : null;
                    
                    try {
                        $post = new BlogPostModel();
                        $post->title = $title;
                        $post->message = $message;
                        $post->author = $author;
                        
                        if ($created_at) {
                            // Сохраняем created_at если указан
                            // Для этого нужно добавить поле в свойства модели
                        }
                        
                        $post->save();
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
    
    $this->view->render('pages/blog_upload.php', 'Загрузка записей блога', [
        'errors' => $errors,
        'success' => $success
    ]);
}
}