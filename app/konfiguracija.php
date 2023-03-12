<?php

$dev = $_SERVER['SERVER_ADDR']==='127.0.0.1' ? true : false;


if($dev){
    return [
        'dev'=>$dev,
        'formatBroja'=>'###,##0.00',
        'url'=>'http://app.hr/',
        'nazivApp'=>'Edunova APP',
        'baza'=>[
            'dsn'=>'mysql:host=localhost;dbname=edunovapp26;charset=utf8mb4',
            'user'=>'root',
            'password'=>''
        ]
    ];
}else{
    return [
        'dev'=>$dev,
        'formatBroja'=>'###,##0.00',
        'url'=>'https://predavac01.edunova.hr/',
        'nazivApp'=>'Edunova APP',
        'baza'=>[
            'dsn'=>'mysql:host=localhost;dbname=cesar_edunovapp26;charset=utf8mb4',
            'user'=>'cesar_edunova',
            'password'=>'wide.!fhuIUJ'
        ]
    ];
}

