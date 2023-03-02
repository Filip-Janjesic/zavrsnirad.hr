<?php

// ova datoteka će definirati temeljne preduvjete
// i napraviti autoloading

session_start();

define('BP',__DIR__ . DIRECTORY_SEPARATOR);
define('BP_APP', BP . 'app' . DIRECTORY_SEPARATOR);

$zaAutoload=[
    BP_APP . 'controller',
    BP_APP . 'core',
    BP_APP . 'model'
]; // view ne ide u autoload jer su u njemu phtml datoteke


$putanje = implode(PATH_SEPARATOR,$zaAutoload);

set_include_path($putanje);

spl_autoload_register(function($klasa){
    //echo 'u spl_autoload, tražim klasu ' . $klasa . '<br>';
    $putanje = explode(PATH_SEPARATOR,get_include_path());
    foreach($putanje as $putanja){
        //echo $putanja . '<br>';
        $datoteka = $putanja . DIRECTORY_SEPARATOR .
                        $klasa . '.php';
        //echo $datoteka, '<br>';
        if(file_exists($datoteka)){
            require_once $datoteka;
            break;
        }
    }
});

App::start();

//$o = new Osoba();
//echo $o->getIme();