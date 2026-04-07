<?php
class Router
{
    public static function route()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $request_uri = strtok($request_uri, '?');
        $url = trim($request_uri, '/');
        $segments = $url === '' ? [] : explode('/', $url);

        $admin_area = false;

        if (!empty($segments[0]) && $segments[0] === 'admin') {
            $admin_area = true;
            array_shift($segments);
        }

        if (empty($segments[0])) {
            $controller_name = $admin_area ? 'blog' : 'index';
            $method_name = $admin_area ? 'editor' : 'index';
        } else {
            $controller_name = $segments[0];
            $method_name = $segments[1] ?? 'index';
        }

       if ($admin_area) {
            $controller_file = 'app/admin/controllers/admin_' . $controller_name . '_controller.php';
            $controller_class = 'Admin' . ucfirst($controller_name) . 'Controller';
        } else {
            $controller_file = 'app/controllers/' . $controller_name . '_controller.php';
            $controller_class = ucfirst($controller_name) . 'Controller';
        }
        if (!file_exists($controller_file)) {
            http_response_code(404);
            die("404 – Контроллер не найден: " . $controller_file);
        }

        require_once $controller_file;

        if (!class_exists($controller_class)) {
            http_response_code(404);
            die("404 – Класс контроллера не найден: " . $controller_class);
        }

        $controller = new $controller_class();

        if (!method_exists($controller, $method_name)) {
            http_response_code(404);
            die("404 – Метод не найден: " . $method_name);
        }

        if ($admin_area) {
            // ВАЖНО: вызываем setter, а не лезем в private-свойство
            $controller->view->setAdminPrefix('admin/');
        }

        $controller->{$method_name}();
    }
}