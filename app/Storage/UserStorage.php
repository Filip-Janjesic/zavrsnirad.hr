<?php

namespace Storage;

use Core\Database;
use Model\User;
use PDO;

class UserStorage
{
    public static function findOneByEmail($email)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            SELECT * FROM user WHERE email=:email;

        ');

        $statement->execute([
            'email' => $email
        ]);

        $statement->setFetchMode(PDO::FETCH_OBJ);
        return $statement->fetch();
    }

    public static function insert(User $user)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('

            INSERT INTO user (name, surname, address, telephone, email, password, role)
            VALUES (:name, :surname, :address, :telephone, :email, :password, :role)

        ');

        $statement->execute([
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'address' => $user->getAddress(),
            'telephone' => $user->getTelephone(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => 'customer'
        ]);
    }

    public static function update(User $user)
    {
        $db = Database::getInstance();
        $statement = $db->prepare('
        
            UPDATE user SET name=:name, surname=:surname,
                            address=:address, telephone=:telephone
            WHERE email=:email

        ');

        $statement->execute([
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'address' => $user->getAddress(),
            'telephone' => $user->getTelephone(),
            'email' => $user->getEmail()
        ]);
    }
}
