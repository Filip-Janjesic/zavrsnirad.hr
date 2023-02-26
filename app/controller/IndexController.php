<?php

class IndexController extends Controller
{

    public function index()
    {

        $this->view->render('index',[
            'iznos'=>12,
            'podaci'=>[
                2,4,5,6,15,4,4
            ]
        ]);
        
    }

    public function kontakt()
    {
        $this->view->render('kontakt');
        
    }

    public function api()
    {
        $this->view->api([
            'podaci'=>[
                'id'=>2,
                'osoba'=>[
                    'ime'=>'Pero',
                    'prezime'=>'Perić'
                ]
            ]
        ]);
        
    }

}