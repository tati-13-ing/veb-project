<?php

class BlogController extends Controller
{
    // Страница "Мой блог" - для пользователей (только просмотр)
    public function index()
    {
        $this->logPageVisit();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 5;
        
        $offset = ($page - 1) * $perPage;
        // Сортируем по id в порядке убывания (новые записи имеют больший id)
        $posts = BlogPostModel::findPaginated($offset, $perPage, 'id', 'DESC');
        $total = BlogPostModel::count();
        $totalPages = ceil($total / $perPage);
        
        $this->view->render('pages/blog.php', 'Мой блог', [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $total,
            'isAdmin' => false
        ]);
    }

}