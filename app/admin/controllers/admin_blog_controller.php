<?php
require_once 'app/admin/controllers/admin_controller.php';
require_once 'app/models/BlogPostModel.php';
require_once 'app/models/validators/FormValidation.php';
class AdminBlogController extends AdminController
{
    private $itemsPerPage = 10;
    
    public function editor()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $this->itemsPerPage;
        
        $totalPosts = BlogPostModel::count();
        $totalPages = ceil($totalPosts / $this->itemsPerPage);
        
        $posts = BlogPostModel::findPaginated($offset, $this->itemsPerPage, 'created_at', 'DESC');
        
        $this->view->render('pages/blog_editor.php', 'Редактор блога', [
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'errors' => $_SESSION['blog_errors'] ?? [],
            'oldData' => $_SESSION['blog_old'] ?? []
        ]);
        
        unset($_SESSION['blog_errors'], $_SESSION['blog_old']);
    }
    
    public function save()
    {
        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $author = trim($_POST['author'] ?? 'Администратор');
        
        if (empty($title)) $errors[] = 'Тема сообщения обязательна';
        if (empty($message)) $errors[] = 'Текст сообщения обязателен';
        
        if (!empty($errors)) {
            $_SESSION['blog_errors'] = $errors;
            $_SESSION['blog_old'] = ['title' => $title, 'message' => $message, 'author' => $author];
            header('Location: /admin/blog/editor');
            exit;
        }
        
        $post = new BlogPostModel();
        $post->title = $title;
        $post->message = $message;
        $post->author = $author;
        
        // Обработка загрузки изображения
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/blog/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $post->image_path = 'uploads/blog/' . $filename;
            }
        }
        
        $post->save();
        header('Location: /admin/blog/editor?success=1');
        exit;
    }
    
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = BlogPostModel::find($id);
        if (!$post) {
            header('Location: /admin/blog/editor');
            exit;
        }
        
        $this->view->render('pages/blog_edit.php', 'Редактирование записи', [
            'post' => $post,
            'errors' => $_SESSION['blog_errors'] ?? []
        ]);
        unset($_SESSION['blog_errors']);
    }
    
    public function update()
    {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $post = BlogPostModel::find($id);
        if (!$post) {
            header('Location: /admin/blog/editor');
            exit;
        }
        
        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $author = trim($_POST['author'] ?? 'Администратор');
        
        if (empty($title)) $errors[] = 'Тема сообщения обязательна';
        if (empty($message)) $errors[] = 'Текст сообщения обязателен';
        
        if (!empty($errors)) {
            $_SESSION['blog_errors'] = $errors;
            header('Location: /admin/blog/edit?id=' . $id);
            exit;
        }
        
        $post->title = $title;
        $post->message = $message;
        $post->author = $author;
        
        // Обновление изображения
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/blog/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $destination = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                // Удаляем старое изображение, если есть
                if (!empty($post->image_path) && file_exists('public/' . $post->image_path)) {
                    unlink('public/' . $post->image_path);
                }
                $post->image_path = 'uploads/blog/' . $filename;
            }
        }
        
        $post->save();
        header('Location: /admin/blog/editor?updated=1');
        exit;
    }
    
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = BlogPostModel::find($id);
        if ($post) {
            if (!empty($post->image_path) && file_exists('public/' . $post->image_path)) {
                unlink('public/' . $post->image_path);
            }
            $post->delete();
        }
        header('Location: /admin/blog/editor?deleted=1');
        exit;
    }
    
    public function upload()
    {
        $this->view->render('pages/blog_upload.php', 'Загрузка записей блога из CSV', [
            'errors' => $_SESSION['csv_errors'] ?? [],
            'success' => $_SESSION['csv_success'] ?? null
        ]);
        unset($_SESSION['csv_errors'], $_SESSION['csv_success']);
    }
    
     public function processUpload()
    {
        $errors = [];

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['csv_errors'] = ['Не удалось загрузить CSV-файл.'];
            header('Location: /admin/blog/upload');
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $_SESSION['csv_errors'] = ['Разрешен только файл формата CSV.'];
            header('Location: /admin/blog/upload');
            exit;
        }

        $tmpFile = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($tmpFile, 'r');

        if (!$handle) {
            $_SESSION['csv_errors'] = ['Не удалось открыть загруженный файл.'];
            header('Location: /admin/blog/upload');
            exit;
        }

        // Автоопределение разделителя
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $headers = fgetcsv($handle, 0, $delimiter);
        $headers = array_map(function ($item) {
            return trim(mb_strtolower($item));
        }, $headers ?: []);

        $expectedHeaders = ['title', 'message', 'author', 'created_at'];

        if ($headers !== $expectedHeaders) {
            fclose($handle);
            $_SESSION['csv_errors'] = [
                'Неверный заголовок CSV. Ожидается: title,message,author,created_at'
            ];
            header('Location: /admin/blog/upload');
            exit;
        }

        // Валидация через FormValidation
        $validator = new FormValidation();
        $validator->setRule('title', 'isNotEmpty');
        $validator->setRule('message', 'isNotEmpty');
        $validator->setRule('author', 'isNotEmpty');
        $validator->setRule('created_at', 'isNotEmpty');

        // Prepared statement по заданию
        $pdo = BlogPostModel::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO blog_posts (title, message, author, created_at)
            VALUES (:title, :message, :author, :created_at)
        ");

        $imported = 0;
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;

            // пропускаем пустые строки
            if ($row === [null] || empty(array_filter($row, fn($v) => trim((string)$v) !== ''))) {
                continue;
            }

            if (count($row) !== 4) {
                $errors[] = "Строка {$rowNumber}: должно быть 4 поля.";
                continue;
            }

            $data = array_combine($expectedHeaders, $row);
            $data = array_map(function ($item) {
                return trim($item);
            }, $data);

            $validator->validate($data);
            $rowErrors = $validator->getErrors();

            if (!empty($rowErrors)) {
                foreach ($rowErrors as $error) {
                    $errors[] = "Строка {$rowNumber}: {$error}";
                }
                continue;
            }

            $timestamp = strtotime($data['created_at']);
            if ($timestamp === false) {
                $errors[] = "Строка {$rowNumber}: неверный формат даты created_at.";
                continue;
            }

            try {
                $stmt->execute([
                    ':title'      => $data['title'],
                    ':message'    => $data['message'],
                    ':author'     => $data['author'],
                    ':created_at' => date('Y-m-d H:i:s', $timestamp),
                ]);
                $imported++;
            } catch (PDOException $e) {
                $errors[] = "Строка {$rowNumber}: ошибка записи в БД.";
            }
        }

        fclose($handle);

        $_SESSION['csv_success'] = $imported;

        if (!empty($errors)) {
            $_SESSION['csv_errors'] = $errors;
        }

        header('Location: /admin/blog/upload');
        exit;
    }
    
    public function ajaxupdate()
{
    header('Content-Type: text/html; charset=utf-8');

    $id = (int)($_POST['id'] ?? 0);
    $post = BlogPostModel::find($id);

    if (!$post) {
        http_response_code(404);
        echo '<div class="form-errors">Запись не найдена.</div>';
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if ($title === '') {
        $errors[] = 'Тема сообщения обязательна.';
    }

    if ($message === '') {
        $errors[] = 'Текст сообщения обязателен.';
    }

    if (!empty($errors)) {
        http_response_code(422);
        echo '<div class="form-errors"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        echo '</ul></div>';
        exit;
    }

    $post->title = $title;
    $post->message = $message;
    $post->save();

    include 'app/views/pages/_blog_post_item.php';
    exit;
}
}