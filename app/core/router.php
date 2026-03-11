<?php
class Router {
    public static function route() {
        $request_uri = $_SERVER['REQUEST_URI'];
        $request_uri = strtok($request_uri, '?');
        $url = trim($request_uri, '/');
        $segments = explode('/', $url);

        // Определяем имя контроллера
        if (empty($segments[0])) {
            $controller_name = 'index';
        } else {
            $controller_name = $segments[0];
        }
         if (empty($segments[1])) {
            $method_name = 'index';
        } else {
            $method_name = $segments[1];
        }
        $controller_file = 'app/controllers/' . $controller_name . '_controller.php';
        $controller_class = ucfirst($controller_name) . 'Controller';

        if (file_exists($controller_file)) {
            require_once $controller_file;
            if (class_exists($controller_class)) {
                $controller = new $controller_class();
                // Всегда вызываем метод index 
                if (method_exists($controller_class, $method_name)) {
                    $controller->{$method_name}();
                } else {
                    die("404 – метод index не найден в контроллере $controller_class");
                }
            } else {
                die("404 – класс контроллера $controller_class не найден");
            }
        } else {
            die("404 – файл контроллера $controller_file не найден");
        }
    }
}