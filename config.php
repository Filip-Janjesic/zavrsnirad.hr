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
        'password' => 'Jupiter54316'
    ];
}

return [
    'dev' => $dev,
    'url' => $url,
    'appTitle' => 'Zavrsni rad',
    'database' => $database
];
