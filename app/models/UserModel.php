<?php
require_once 'app/core/BaseActiveRecord.php';

class UserModel extends BaseActiveRecord
{
    protected static $tablename = 'users';
    protected static $dbfields = [];
    public $id;
    public $full_name;
    public $email;
    public $login;
    public $password_hash;
    public $created_at;
    
    /**
     * Хеширует пароль и сохраняет пользователя
     */
    public function setPassword($plainPassword)
    {
        $this->password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);
    }
    
    /**
     * Проверяет пароль
     */
    public function verifyPassword($plainPassword)
    {
        return password_verify($plainPassword, $this->password_hash);
    }
    
    /**
     * Находит пользователя по логину
     */
    public static function findByLogin($login)
    {
        static::setupConnection();
        $sql = "SELECT * FROM " . static::$tablename . " WHERE login = :login LIMIT 1";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute([':login' => $login]);
        $row = $stmt->fetch();
        if (!$row) return null;
        
        $user = new static();
        foreach ($row as $key => $value) {
            if (property_exists($user, $key)) {
                $user->$key = $value;
            }
        }
        return $user;
    }
    
    /**
     * Проверяет, существует ли логин
     */
    public static function loginExists($login)
    {
        $user = self::findByLogin($login);
        return $user !== null;
    }
}