<?php
$localhost= false;
if($_SERVER['SERVER_ADDR'] === '127.0.0.1'){
    $url = 'http://webshop.hr/';
    $localhost= true;

}

return [
    'localhost' => $localhost,
    'siteTitle' => 'Web shop',
    'url' => 'http://webshop.hr/',
    'database' => [
        'server' => 'localhost',
        'name' => 'webshop',
        'user' => 'jupiter_filip',
        'password' => 't84durft1994'
    ]
];
