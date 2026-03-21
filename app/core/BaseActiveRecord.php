<?php

class BaseActiveRecord
{
    /**
     * @var PDO Экземпляр PDO для подключения к БД
     */
    protected static $pdo = null;
    
    /**
     * @var string Имя таблицы в БД (должно быть переопределено в дочернем классе)
     */
    protected static $tablename = '';
    
    /**
     * @var array Поля таблицы с их типами
     */
    protected static $dbfields = [];
    
    /**
     * @var int ID записи (первичный ключ)
     */
    public $id;
    
    /**
     * Конструктор - инициализирует подключение к БД и получает структуру таблицы
     */
    public function __construct()
    {
        if (!static::$tablename) {
            return;
        }
        
        static::setupConnection();
        static::getFields();
    }
    
    /**
     * Устанавливает подключение к базе данных (если еще не установлено)
     */
    protected static function setupConnection()
    {
        if (!isset(static::$pdo)) {
            try {
                $dsn = 'mysql:host=localhost;dbname=personal_site;charset=utf8mb4';
                $username = 'site_user';
                $password = 'site_password';
                
                static::$pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Ошибка подключения к базе данных: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Получает структуру таблицы (поля и их типы)
     */
    protected static function getFields()
    {
        if (empty(static::$dbfields)) {
            try {
                $stmt = static::$pdo->query("DESCRIBE " . static::$tablename);
                while ($row = $stmt->fetch()) {
                    static::$dbfields[$row['Field']] = $row['Type'];
                }
            } catch (PDOException $e) {
                // Таблица может не существовать - игнорируем
            }
        }
    }
    
    /**
     * Сохраняет текущую запись в базу данных
     * 
     * @return BaseActiveRecord Возвращает текущий объект для цепочки вызовов
     */
    public function save()
    {
        if (!static::$tablename) {
            return $this;
        }
        
        $fields = [];
        $values = [];
        $params = [];
        
        foreach (static::$dbfields as $field => $type) {
            if ($field === 'id') {
                continue; // Пропускаем автоинкрементное поле
            }
            
            if (property_exists($this, $field)) {
                $fields[] = $field;
                $values[] = ":$field";
                $params[":$field"] = $this->$field;
            }
        }
        
        if (empty($fields)) {
            return $this;
        }
        
        if (empty($this->id)) {
            // INSERT - новая запись
            $sql = "INSERT INTO " . static::$tablename . " (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $values) . ")";
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute($params);
            $this->id = static::$pdo->lastInsertId();
        } else {
            // UPDATE - существующая запись
            $setParts = [];
            foreach ($fields as $field) {
                $setParts[] = "$field = :$field";
            }
            $sql = "UPDATE " . static::$tablename . " 
                    SET " . implode(', ', $setParts) . " 
                    WHERE id = :id";
            $params[':id'] = $this->id;
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute($params);
        }
        
        return $this;
    }
    
    /**
     * Удаляет текущую запись из базы данных
     * 
     * @return bool true в случае успеха, false при ошибке
     */
    public function delete()
    {
        if (empty($this->id) || !static::$tablename) {
            return false;
        }
        
        try {
            $sql = "DELETE FROM " . static::$tablename . " WHERE id = :id";
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute([':id' => $this->id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Находит запись по ID
     * 
     * @param int $id ID записи
     * @return BaseActiveRecord|null Объект записи или null, если не найдена
     */
    public static function find($id)
    {
        if (!static::$tablename) {
            return null;
        }
        
        static::setupConnection();
        
        try {
            $sql = "SELECT * FROM " . static::$tablename . " WHERE id = :id LIMIT 1";
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            
            if (!$row) {
                return null;
            }
            
            $obj = new static();
            foreach ($row as $key => $value) {
                if (property_exists($obj, $key)) {
                    $obj->$key = $value;
                }
            }
            return $obj;
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Находит все записи в таблице
     * 
     * @param string $orderBy Поле для сортировки (опционально)
     * @param string $direction Направление сортировки (ASC/DESC)
     * @return array Массив объектов
     */
    public static function findAll($orderBy = null, $direction = 'ASC')
    {
        if (!static::$tablename) {
            return [];
        }
        
        static::setupConnection();
        
        $sql = "SELECT * FROM " . static::$tablename;
        if ($orderBy) {
            $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY $orderBy $direction";
        }
        
        $stmt = static::$pdo->query($sql);
        $rows = $stmt->fetchAll();
        
        $results = [];
        foreach ($rows as $row) {
            $obj = new static();
            foreach ($row as $key => $value) {
                if (property_exists($obj, $key)) {
                    $obj->$key = $value;
                }
            }
            $results[] = $obj;
        }
        
        return $results;
    }
    
    /**
     * Находит записи с пагинацией
     * 
     * @param int $offset Смещение
     * @param int $limit Количество записей
     * @param string $orderBy Поле для сортировки
     * @param string $direction Направление сортировки
     * @return array Массив объектов
     */
    public static function findPaginated($offset, $limit, $orderBy = 'id', $direction = 'DESC')
    {
        if (!static::$tablename) {
            return [];
        }
        
        static::setupConnection();
        
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM " . static::$tablename . " 
                ORDER BY $orderBy $direction 
                LIMIT :offset, :limit";
        
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        $results = [];
        foreach ($rows as $row) {
            $obj = new static();
            foreach ($row as $key => $value) {
                if (property_exists($obj, $key)) {
                    $obj->$key = $value;
                }
            }
            $results[] = $obj;
        }
        
        return $results;
    }
    
    /**
     * Получает общее количество записей в таблице
     * 
     * @return int Количество записей
     */
    public static function count()
    {
        if (!static::$tablename) {
            return 0;
        }
        
        static::setupConnection();
        
        $sql = "SELECT COUNT(*) as total FROM " . static::$tablename;
        $stmt = static::$pdo->query($sql);
        $row = $stmt->fetch();
        
        return (int)$row['total'];
    }
    
    /**
     * Удаляет все записи из таблицы
     * 
     * @return bool
     */
    public static function truncate()
    {
        if (!static::$tablename) {
            return false;
        }
        
        try {
            static::setupConnection();
            $sql = "TRUNCATE TABLE " . static::$tablename;
            static::$pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}