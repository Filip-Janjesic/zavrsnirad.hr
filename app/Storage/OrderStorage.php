<?php

namespace Storage;

use Core\Database;
use Model\OrderProduct;

class OrderStorage
{
    public static function findAll()
    {
        $db = Database::getInstance();

        $statement = $db->prepare('

            SELECT 
                orders_product.id AS order_product_id,
                orders_product.amount AS order_product_amount,
                product.id AS product_id,
                product.name AS product_name,
                product.price AS product_price,
                orders.id AS order_id,
                orders.address AS order_address,
                orders.date AS order_date,
                user.name AS order_user_name,
                user.surname AS order_user_surname,
                orders.total_price AS order_total_price,
                orders_status.status AS order_status
                
            FROM orders_product 
                
            INNER JOIN product ON orders_product.product = product.id
            INNER JOIN orders ON orders_product.orders = orders.id 
            INNER JOIN user ON orders.user = user.id
            INNER JOIN orders_status ON orders.status = orders_status.id
        ');

        $statement->execute();

        return $statement->fetchAll();
    }

    public static function findOneById($id)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('
            SELECT * FROM orders
            WHERE id =:id
        ');
        $statement->execute([
            'id' => $id
        ]);
        return $statement->fetchObject();
    }

    public static function checkOrder($id)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

		SELECT * FROM orders WHERE ordered=false AND user=:userId;

        ');
        $statement->execute([
            'userId' => $id
        ]);
        return $statement->fetchObject();
    }

    public static function create($user)
    {
        $db = Database::getInstance();

        $statement = $db->prepare('

            INSERT INTO orders (user, address) VALUES (:userId, :userAddress)
            
        ');
        $statement->execute([
            'userId' => $user->id,
            'userAddress' => $user->address
        ]);
    }

    public static function addToCart(OrderProduct $orderProduct)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            INSERT INTO orders_product(orders, product, amount)
            VALUES(:orders, :product, :amount);

        ');
        $statement->execute([
            'orders' => $orderProduct->getOrder(),
            'product' => $orderProduct->getProduct(),
            'amount' => $orderProduct->getAmount(),
        ]);
    }

    public static function findAllFromLoggedInUser($ordered = false)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('
        
            SELECT id FROM orders WHERE user = :userId;
        
        ');

        $statement->execute([
            'userId' => $_SESSION['user']->id
        ]);

        $order = $statement->fetchObject();

        $statement = $db->prepare('

            SELECT 
                orders_product.id AS order_product_id,
                orders_product.amount AS order_product_amount,
                product.id AS product_id,
                product.name AS product_name,
                product.price AS product_price,
                orders.id AS order_id,
                orders.address AS order_address,
                orders.date AS order_date,
                user.name AS order_user_name,
                user.surname AS order_user_surname
                
            FROM orders_product 
                
            INNER JOIN product ON orders_product.product = product.id
            INNER JOIN orders ON orders_product.orders = orders.id 
            INNER JOIN user ON orders.user = user.id
            
            WHERE 
                orders.id = :orderId 
                AND
                orders.ordered = :ordered;

        ');

        $statement->execute([
            'orderId' => $order->id,
            'ordered' => $ordered
        ]);

        return $statement->fetchAll();
    }

    public static function removeFromCart($orderProductId)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            DELETE FROM orders_product WHERE id = :id;

        ');

        $statement->execute([
            'id' => $orderProductId
        ]);
    }

    public static function getStatusByName($status)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('
        
            SELECT * FROM orders_status WHERE status = :status;
        
        ');

        $statement->execute([
            'status' => $status
        ]);

        return $statement->fetchObject();
    }

    public static function getTotalItemsOfOrder($orderId)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT
                COUNT(orders_product.id) AS total_items
            FROM orders_product

            WHERE orders = :orderId;

        ');

        $statement->execute([
            'orderId' => $orderId
        ]);

        return $statement->fetchColumn();
    }


    public static function getTotalPriceOfOrder($orderId)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT
                SUM(product.price * orders_product.amount) AS total_price
            FROM orders_product

            INNER JOIN product ON orders_product.product = product.id
            WHERE orders = :orderId;

        ');

        $statement->execute([
            'orderId' => $orderId
        ]);

        return $statement->fetchColumn();
    }

    public static function finishOrder($orderId)
    {
        $totalPrice = self::getTotalPriceOfOrder($orderId);

        $db = Database::getInstance();
        $statement = $db->prepare('

            UPDATE orders 
            SET ordered = :ordered, 
                status = :status,
                total_price = :total_price
            WHERE id = :id

        ');

        $status = self::getStatusByName('Received');

        $statement->execute([
            'ordered' => true,
            'status' => $status->id,
            'total_price' => $totalPrice,
            'id' => $orderId
        ]);
    }

    public static function findStatusByOrderId($orderId)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT
                orders_status.status AS order_status
            FROM orders
            
            INNER JOIN orders_status ON orders.status = orders_status.id

            WHERE orders.id = :orderId;

        ');

        $statement->execute([
            'orderId' => $orderId
        ]);

        return $statement->fetch();
    }
}
