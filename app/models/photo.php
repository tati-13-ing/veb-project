<?php
class Photo extends Model
{
    // Константа с массивом фотографий (имя файла и подпись)
    const PHOTOS = [
        ['src' => 'photo_01.png', 'caption' => 'Профайлер'],
        ['src' => 'photo_02.png', 'caption' => 'Убийство в Восточном экспрессе'],
        ['src' => 'photo_03.png', 'caption' => 'Убийство на поле для гольфа'],
        ['src' => 'photo_04.png', 'caption' => 'Занавес: Последнее дело Пуаро'],
        ['src' => 'photo_05.png', 'caption' => 'Смерть на Ниле'],
        ['src' => 'photo_06.png', 'caption' => 'Преследуя Аделин'],
        ['src' => 'photo_07.png', 'caption' => 'Школония'],
        ['src' => 'photo_08.png', 'caption' => 'Гибрид'],
        ['src' => 'photo_09.png', 'caption' => 'Лисья нора'],
        ['src' => 'photo_10.png', 'caption' => 'Город, в котором меня нет'],
        ['src' => 'photo_11.png', 'caption' => 'Антидемон'],
        ['src' => 'photo_12.png', 'caption' => 'Академия стихий'],
        ['src' => 'photo_13.png', 'caption' => 'Класс превосходства'],
        ['src' => 'photo_14.png', 'caption' => 'Поднятие уровня в одиночку'],
        ['src' => 'photo_15.png', 'caption' => 'Игра друзей'],
    ];
}