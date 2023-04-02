<?php

$dev = $_SERVER['SERVER_ADDR']==='127.0.0.1' ? true : false;


if($dev){
    return [
        'dev'=>$dev,
        'formatBroja'=>'###,##0.00',
        'url'=>'http://zavrsnirad.hr/',
        'nazivApp'=>'Zavrsni rad',
        'brps'=>10,
        'baza'=>[
            'dsn'=>'mysql:host=localhost;dbname=zavrsnirad;charset=utf8mb4',
            'user'=>'root',
            'password'=>''
        ]
    ];
}else{
    return [
        'dev'=>$dev,
        'formatBroja'=>'###,##0.00',
        'url'=>'https://polaznik40.edunova.hr/',
        'nazivApp'=>'Zavrsni rad',
        'brps'=>10,
        'baza'=>[
            'dsn'=>'mysql:host=localhost;dbname=jupiter_zavrsnirad;charset=utf8mb4',
            'user'=>'jupiter_filip',
            'password'=>'$2y$10$IdhJ/FE88d.4WCKGtI2L0exu67zUKigMsUD9lzauYblv7dNWF9gdO'
        ]
    ];
}

