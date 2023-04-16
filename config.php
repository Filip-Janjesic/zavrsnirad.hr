<?php

if ($_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    $url = 'http://zavrsnirad.hr/';
    $dev = true;
    $database = [
        'server' => 'localhost',
        'database' => 'zavrsnirad',
        'user' => 'root',
        'password' => ''
    ];
} else {
    $url = 'https://polaznik40.edunova.hr/';
    $dev = false;
    $database = [
        'server' => 'localhost',
        'database' => 'jupiter_zavrsnirad',
        'user' => 'jupiter_filip',
        'password' => '$2y$10$IdhJ/FE88d.4WCKGtI2L0exu67zUKigMsUD9lzauYblv7dNWF9gdO'
    ];
}

return [
    'dev' => $dev,
    'url' => $url,
    'appTitle' => 'Zavrsni rad',
    'database' => $database
];
