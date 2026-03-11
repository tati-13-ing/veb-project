<?php
class View {
    public function render($content_view, $title, $data = []) {
        extract($data); // превращает ключи массива в переменные
        include 'app/views/layout.php';
    }
}