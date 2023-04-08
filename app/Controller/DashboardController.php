<?php

namespace Controller;

class DashboardController extends BaseController
{
    private $viewDir = 'dashboard' . DIRECTORY_SEPARATOR;

    public function index()
    {
        echo $this->view->render('base', [
            'content' => $this->view->render($this->viewDir . 'index')
        ]);
    }
}