<?php

namespace Controller;

use Model\User;
use Storage\OrderStorage;
use Storage\UserStorage;

class UserController extends BaseController
{
    private $viewDir = 'user' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $user = UserStorage::findOneByEmail($_SESSION['user']->email);
        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'profile', [
                'user' => $user
            ])
        ]);
    }

    public function login()
    {
        if (isset($_SESSION) && isset($_SESSION['user']))
        {
            header('Location: /');
        }

        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'login')
        ]);
    }

    public function authorize()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_POST['submit_login']))
            {
                $user = UserStorage::findOneByEmail($_POST['email']);
                if(!$user)
                {
                    $_SESSION['error_message'] = 'User with this email address does not exist!';
                    header('Location: /user/login');
                } else {
                    if(password_verify($_POST['password'], $user->password)){
                        $_SESSION['user'] = $user;
                        header('Location: /home');
                    } else {
                        $_SESSION['error_message'] = 'Invalid password!';
                        header('Location: /user/login');
                    }
                }
            }
        }
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_POST['submit_update'])) {
                $user = new User();
                $user->setEmail($_SESSION['user']->email);
                $user->setName($_POST['name']);
                $user->setSurname($_POST['surname']);
                $user->setAddress($_POST['address']);
                $user->setTelephone($_POST['telephone']);

                UserStorage::update($user);

                header('Location: /user');
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
    }

    public function history()
    {
        $orders = OrderStorage::findAllFromLoggedInUser(true);

        $totalPrice = $status = null;
        foreach($orders as $order) {
            $totalPrice = OrderStorage::getTotalPriceOfOrder($order->order_id);
            $status = OrderStorage::findStatusByOrderId($order->order_id);
            break;
        }

        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'history', [
                'orders' => $orders,
                'totalPrice' => $totalPrice,
                'status' => $status
            ])
        ]);
    }
}
