<?php

require_once 'app/core/BaseActiveRecord.php';

class GuestbookModel extends BaseActiveRecord
{
    protected static $tablename = 'guestbook';
    
    // Свойства, соответствующие полям таблицы
    public $id;
    public $date_added;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $email;
    public $message;
    public $created_at;
    
    /**
     * Получает ФИО в формате "Фамилия Имя Отчество"
     */
    public function getFullName()
    {
        $parts = [$this->last_name, $this->first_name];
        if (!empty($this->middle_name)) {
            $parts[] = $this->middle_name;
        }
        return implode(' ', $parts);
    }
    
    /**
     * Форматирует дату добавления
     */
    public function getFormattedDate()
    {
        $date = $this->date_added ?: $this->created_at;
        if ($date) {
            return date('d.m.Y', strtotime($date));
        }
        return '';
    }
}