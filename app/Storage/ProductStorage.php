<?php

namespace Storage;

use Core\Database;
use Model\Product;
use PDO;

class ProductStorage
{
    public static function findAll()
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM product
            ORDER BY id DESC

        ');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert(Product $product)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            INSERT INTO product (name, description, price, type)
            VALUES (:name, :description, :price, :type);

        ');

        $statement->execute([
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'type' => $product->getType()->getId()
        ]);
    }

    public static function findOneByProductTypeName($type_id)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM product
            WHERE type=:id

        ');
        $statement->execute([
            'id' => $type_id
        ]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findOneById($id)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM product
            WHERE id=:id

        ');
        $statement->execute([
            'id' => $id
        ]);
        return $statement->fetch(PDO::FETCH_OBJ);
    }


    public static function update(Product $product)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            UPDATE product SET
                name=:name,
                description=:description,
                price=:price,
                type=:type
            WHERE id=:id;

        ');

        $statement->execute([
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'type' => $product->getType()->getId(),
            'id' => $product->getId()
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            DELETE FROM product 
            WHERE id=:id;

        ');

        $statement->execute([
            'id' => $id
        ]);
    }
}