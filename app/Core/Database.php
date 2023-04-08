<?php

namespace Core;

use PDO;

class Database extends PDO
{
    private static $instance = null;

    public function __construct($db)
    {
        $dsn = 'mysql:host=' . $db['server'] . ';dbname=' . $db['database'] . ';charset=utf8mb4';
        parent::__construct($dsn, $db['user'], $db['password']);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self(App::config('database'));
        }
        return self::$instance;
    }
}
