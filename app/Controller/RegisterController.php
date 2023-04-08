<?php

namespace Controller;

use Model\User;
use Storage\UserStorage;

class RegisterController extends BaseController
{
    private $error_message = null;

    public function index()
    {
        if (isset($_SESSION) && isset($_SESSION['user']))
        {
            header('Location: /');
        }

        echo $this->view->render('base', [
            'content' => $this->view->render('register/index')
        ]);
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_POST['submit_register']))
            {
                if(!$this->checkIfInputsAreCorrect($_POST))
                {
                    $_SESSION['error_message'] = $this->error_message;
                    header('Location: /register');
                } else {
                    $user = new User();
                    $user->setName($_POST['name']);
                    $user->setSurname($_POST['surname']);
                    $user->setAddress($_POST['address']);
                    $user->setTelephone($_POST['telephone']);
                    $user->setEmail($_POST['email']);
                    $user->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));

                    UserStorage::insert($user);

                    header('Location: /user/login');
                }
            }
        }
    }

    private function checkName($name)
    {
        if (strlen(trim($name)) > 50) {
            $this->error_message = 'The name cannot be longer than 50 characters.';
        }
    }

    private function checkSurname($surname)
    {
        if (strlen(trim($surname)) > 50) {
            $this->error_message = 'The surname cannot be longer than 50 characters.';
        }
    }

    private function checkEmail($email)
    {
        $userAlreadyExist = UserStorage::findOneByEmail($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error_message = 'Invalid email format.';
        } else if ($userAlreadyExist) {
            $this->error_message = 'User with this email already exists.';
        }
    }

    private function checkTelephone($telephone)
    {
        if(strlen(trim($telephone)) > 15){
            $this->error_message = 'Phone number cannot be longer than 15 characters.';
        }
    }

    private function checkAddress($address)
    {
        if(strlen(trim($address)) > 50){
            $this->error_message = 'The address cannot be longer than 50 characters.';
        }
    }

    private function checkPassword($password)
    {
        if (strlen(trim($password)) < 6) {
            $this->error_message = 'Password must contain min 6 characters.';
        }
    }

    private function checkIfPasswordAndRepeatPasswordAreIdentical($password, $repeatPassword)
    {
        if ($password !== $repeatPassword) {
            $this->error_message = 'Passwords do not match.';
        }
    }

    private function checkIfInputsAreCorrect($params)
    {
        $this->checkName($params['name']);
        $this->checkSurname($params['surname']);
        $this->checkEmail($params['email']);
        $this->checkTelephone($params['telephone']);
        $this->checkAddress($params['address']);
        $this->checkPassword($params['password']);
        $this->checkIfPasswordAndRepeatPasswordAreIdentical(
            $params['password'], $params['repeat_password']
        );

        return is_null($this->error_message);
    }
}

