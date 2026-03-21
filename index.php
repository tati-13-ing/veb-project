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
        // Сначала пробуем с исходным именем класса
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        } 
        
        // Если не нашли, пробуем с маленькой буквы
        $file = $path . strtolower($class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
  
// Подключаем вспомогательные функции
require_once 'app/core/helpers.php';

// Запуск роутера
require_once 'app/core/router.php';
Router::route();