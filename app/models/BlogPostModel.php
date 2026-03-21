<?php

require_once 'app/core/BaseActiveRecord.php';

class BlogPostModel extends BaseActiveRecord
{
    protected static $tablename = 'blog_posts';
    
    public $id;
    public $title;
    public $message;
    public $image_path;
    public $author;
    public $created_at;
    public $updated_at;
    
    /**
     * Получает URL изображения или путь по умолчанию
     */
    public function getImageUrl()
    {
        if (!empty($this->image_path) && file_exists('public/' . $this->image_path)) {
            return '/' . $this->image_path;
        }
        return '/public/assets/img/no-image.png';
    }
    
    /**
     * Получает сокращенный текст для предпросмотра
     */
    public function getExcerpt($length = 200)
    {
        $text = strip_tags($this->message);
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . '...';
    }
    
    /**
     * Получает форматированную дату создания
     */
    public function getFormattedDate()
    {
        if ($this->created_at) {
            return date('d.m.Y H:i', strtotime($this->created_at));
        }
        return '';
    }
}