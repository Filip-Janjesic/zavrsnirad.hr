<?php

namespace Storage;

use Core\Database;
use PDO;

class ProductTypeStorage
{
    public static function findAll()
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM product_type

        ');

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findOneByName($name)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM product_type
            WHERE name=:name

        ');

        $statement->execute([
            'name' => $name
        ]);
        return $statement->fetchObject();
    }
}