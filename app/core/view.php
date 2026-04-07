<?php
class View
{
    private $adminPrefix = '';

    public function render($content_view, $title, $data = [], $layout = 'layout.php', $prefix = '')
    {
        extract($data);

        $isAdmin = ($prefix === 'admin/' || $this->adminPrefix === 'admin/');

        if ($isAdmin) {
            $layout_file = 'app/admin/views/admin_layout.php';
            $content_file = 'app/admin/views/' . $content_view;
        } else {
            $layout_file = 'app/views/' . $layout;
            $content_file = 'app/views/' . $content_view;
        }

        if (!file_exists($layout_file)) {
            die("Layout не найден: " . $layout_file);
        }

        if (!file_exists($content_file)) {
            die("Представление не найдено: " . $content_file);
        }

        include $layout_file;
    }

    public function setAdminPrefix($prefix)
    {
        $this->adminPrefix = $prefix;
    }
}