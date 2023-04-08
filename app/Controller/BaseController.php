<?php

namespace Controller;

use Core\View;

abstract class BaseController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }
}