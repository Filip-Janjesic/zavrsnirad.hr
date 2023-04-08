<?php

namespace Controller;

class HomeController extends BaseController
{
    public function index()
    {
        echo $this->view->render('base', [
            'content' => $this->view->render('index')
        ]);
    }
}