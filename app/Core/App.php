<?php

namespace Core;

use Controller\Controller404;

class App
{
    public static function start()
    {
        $route = Request::getRoute();
        $routeParts = explode('/', $route);

        if (!isset($routeParts[1]) || $routeParts[1] === '') {
            $class = 'Home';
        } else {
            $class = ucfirst($routeParts[1]);
        }
        $class .= 'Controller';

        if (!isset($routeParts[2]) || $routeParts[2] === '') {
            $method = 'index';
        } else {
            $method = $routeParts[2];
        }

        $classController = 'Controller\\' . $class;
        if (class_exists($classController) && method_exists($classController, $method)) {
            $classController = new $classController();
            if (isset($method)) {
                $controllerMethods = get_class_methods($classController);
                if (in_array($method, $controllerMethods)) {
                    $classController->$method();
                } else {
                    return new Controller404;
                }
            }
        } else {
            return new Controller404;
        }
    }

    public static function config($key)
    {
        $config = include BP_APP . '../config.php';
        return $config[$key];
    }

    public static function isAuthorized()
    {
        return (isset($_SESSION) && isset($_SESSION['user']));
    }

    public static function isAdmin()
    {
        return $_SESSION['user']->role === "admin";
    }
}