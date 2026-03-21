<?php
function my_strtolower($str) {
    $lower = [
        'А' => 'а', 'Б' => 'б', 'В' => 'в', 'Г' => 'г', 'Д' => 'д',
        'Е' => 'е', 'Ё' => 'ё', 'Ж' => 'ж', 'З' => 'з', 'И' => 'и',
        'Й' => 'й', 'К' => 'к', 'Л' => 'л', 'М' => 'м', 'Н' => 'н',
        'О' => 'о', 'П' => 'п', 'Р' => 'р', 'С' => 'с', 'Т' => 'т',
        'У' => 'у', 'Ф' => 'ф', 'Х' => 'х', 'Ц' => 'ц', 'Ч' => 'ч',
        'Ш' => 'ш', 'Щ' => 'щ', 'Ъ' => 'ъ', 'Ы' => 'ы', 'Ь' => 'ь',
        'Э' => 'э', 'Ю' => 'ю', 'Я' => 'я'
    ];
    return strtr($str, $lower);
}

// Добавить функцию для преобразования даты
function formatDateForMySQL($dateString) {
    if (empty($dateString)) {
        return date('Y-m-d');
    }
    
    // Пробуем преобразовать DD.MM.YYYY в YYYY-MM-DD
    $parts = explode('.', $dateString);
    if (count($parts) == 3) {
        $day = $parts[0];
        $month = $parts[1];
        $year = $parts[2];
        
        // Проверяем, что все части - числа
        if (is_numeric($day) && is_numeric($month) && is_numeric($year)) {
            return "$year-$month-$day";
        }
    }
    
    // Если не удалось преобразовать, возвращаем текущую дату
    return date('Y-m-d');
}