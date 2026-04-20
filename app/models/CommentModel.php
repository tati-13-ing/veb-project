<?php
require_once 'app/core/BaseActiveRecord.php';

class CommentModel extends BaseActiveRecord
{
    protected static $tablename = 'blog_comments';
    protected static $dbfields = [];

    public $id;
    public $blog_post_id;
    public $user_id;
    public $author_name;
    public $message;
    public $created_at;

    public function getFormattedDate()
    {
        if (!$this->created_at) {
            return '';
        }
        return date('d.m.Y H:i', strtotime($this->created_at));
    }

    public static function findForPosts(array $postIds)
    {
        if (empty($postIds)) {
            return [];
        }

        static::setupConnection();

        $postIds = array_values(array_unique(array_map('intval', $postIds)));
        $placeholders = implode(',', array_fill(0, count($postIds), '?'));

        $sql = 'SELECT * FROM ' . static::$tablename .
               " WHERE blog_post_id IN ($placeholders) ORDER BY created_at ASC, id ASC";

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($postIds);
        $rows = $stmt->fetchAll();

        $grouped = [];

        foreach ($rows as $row) {
            $item = new static();
            foreach ($row as $key => $value) {
                if (property_exists($item, $key)) {
                    $item->$key = $value;
                }
            }
            $grouped[$item->blog_post_id][] = $item;
        }

        return $grouped;
    }
}