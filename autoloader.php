<?php
spl_autoload_register(function ($class) {       //dopušta automatsko učitavanje funkcija odjednom i prolazi kroz svaku redosljedom kako su definirani
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . "/app/{$class}.php";
    if (file_exists($file)) {
        require_once $file;
    }
});