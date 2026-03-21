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
        
        // Определяем метод (action)
        if (isset($segments[1]) && !empty($segments[1])) {
            $method_name = $segments[1];
        } else {
            $method_name = 'index';
        }
        
        // Путь к файлу контроллера
        $controller_file = 'app/controllers/' . $controller_name . '_controller.php';
        $controller_class = ucfirst($controller_name) . 'Controller';

        // Проверяем существование файла контроллера
        if (!file_exists($controller_file)) {
            http_response_code(404);
            die("404 – Файл контроллера $controller_file не найден");
        }
        
        // Подключаем файл контроллера
        require_once $controller_file;
        
        // Проверяем существование класса
        if (!class_exists($controller_class)) {
            http_response_code(404);
            die("404 – Класс контроллера $controller_class не найден");
        }
        
        // Создаем экземпляр контроллера
        $controller = new $controller_class();
        
        // Проверяем существование метода
        if (!method_exists($controller, $method_name)) {
            http_response_code(404);
            die("404 – Метод $method_name не найден в контроллере $controller_class");
        }
        
        // Вызываем метод контроллера
        $controller->{$method_name}();
    }
}