<?php

namespace Controller;

class Controller404 extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        echo $this->view->render('error404');
    }
}