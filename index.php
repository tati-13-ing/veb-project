<?php
// Автозагрузка классов
spl_autoload_register(function ($class) {
    $paths = [
        'app/core/',
        'app/controllers/',
        'app/models/',
        'app/models/validators/'
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Подключаем вспомогательные функции
require_once 'app/core/helpers.php';

// Запуск роутера
require_once 'app/core/Router.php';
Router::route();