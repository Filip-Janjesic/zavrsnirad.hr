<?php

class App
{
    // Ova metoda ima zadatak saznati što želiš i to pokrenuti
    public static function start()
    {
        $ruta = Request::getRuta();
        $djelovi = explode('/',substr($ruta,1));
        $controller='';
        if(!isset($djelovi[0]) || $djelovi[0]===''){
            $controller='IndexController';
        }else{
            $controller = ucfirst($djelovi[0]) . 'Controller';
        }
        $metoda='';
        if(!isset($djelovi[1]) || $djelovi[1]==='' ){
            $metoda='index';
        }else{
            $metoda=$djelovi[1];
        }
        if(!(class_exists($controller) && method_exists($controller,$metoda))){
            echo 'Ne postoji ' . $controller . '-&gt;' . $metoda;
            return;
        }
        $instanca = new $controller();
        $instanca->$metoda();
    }

    public static function config($kljuc)
    {
        $configFile = BP_APP . 'konfiguracija.php';

        if(!file_exists($configFile)){
            return 'Konfiguracijka datoteka ne postoji';
        }

        $config = require $configFile;

        if(!isset($config[$kljuc])){
            return 'Ključ ' . $kljuc . ' nije postavljen u konfiguraciji';
        }

        return $config[$kljuc];

    }

}
