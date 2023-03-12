<?php

class SmjerController extends AutorizacijaController
{

    private $viewPutanja = 'privatno' . 
    DIRECTORY_SEPARATOR . 'smjerovi' . 
    DIRECTORY_SEPARATOR;
    private $nf;
    private $e;
    private $poruka='';

    public function __construct()
    {
        parent::__construct();
        $this->nf = new NumberFormatter('hr-HR',NumberFormatter::DECIMAL);
        $this->nf->setPattern(App::config('formatBroja'));
    }

    public function index()
    {        
     $this->view->render($this->viewPutanja . 
            'index',[
                'podaci'=>$this->prilagodiPodatke(Smjer::read()),
                'css'=>'smjer.css'
            ]);   
    }

    public function novi()
    {
       if($_SERVER['REQUEST_METHOD']==='GET'){
        $this->pozoviView([
            'e'=>$this->pocetniPodaci(),
            'poruka'=>$this->poruka
        ]);
        return;
       } //ovdje sam siguran da nije GET, za nas je onda POST
       $this->pripremiZaView();
       if(!$this->kontrolaNovi()){// kontrolirati podatke, ako nešto ne valja vratiti na view s porukom 
        $this->pozoviView([
            'e'=>$this->e,
            'poruka'=>$this->poruka
        ]);
        return;
       }
       $this->pripremiZaBazu(); // priprema za bazu
       Smjer::create((array)$this->e);  //ako je sve OK spremiti u bazu
       $this->pozoviView([
            'e'=>$this->pocetniPodaci(),
            'poruka'=>'Uspješno spremljeno'
        ]);
    }

    public function promjena($sifra='')
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(strlen(trim($sifra))===0){
                header('location: ' . App::config('url') . 'index/odjava');
                return;
            }

            $sifra=(int)$sifra;
            if($sifra===0){
                header('location: ' . App::config('url') . 'index/odjava');
                return;
            }

            $this->e = Smjer::readOne($sifra);

            if($this->e==null){
                header('location: ' . App::config('url') . 'index/odjava');
                return;
            }

            $this->e->cijena=$this->nf->format($this->e->cijena);
            $this->e->upisnina=$this->nf->format($this->e->upisnina);

            $this->view->render($this->viewPutanja . 
            'promjena',[
                'e'=>$this->e,
                'poruka'=>'Promjenite podatke po želji'
            ]);  
            return;
        }

        // ovdje je POST
        $this->pripremiZaView();
        if(!$this->kontrolaPromjena()){// kontrolirati podatke, ako nešto ne valja vratiti na view s porukom 
            $this->view->render($this->viewPutanja . 
            'promjena',[
                'e'=>$this->e,
                'poruka'=>$this->poruka
            ]);  
         return;
        }

        $this->e->sifra=$sifra;
        $this->pripremiZaBazu(); // priprema za bazu
        Smjer::update((array)$this->e);
        $this->view->render($this->viewPutanja . 
        'promjena',[
            'e'=>$this->e,
            'poruka'=>'Uspješno promjenjeno'
        ]);  


    }

    private function pozoviView($parametri)
    {
        $this->view->render($this->viewPutanja . 
       'novi',$parametri);  
    }

    private function pripremiZaView()
    {
        $this->e = (object)$_POST;
        $this->e->certificiran = $this->e->certificiran==='true' ? true : false;
    }

    private function pripremiZaBazu()
    {
        $this->e->cijena = $this->nf->parse($this->e->cijena);
       $this->e->upisnina = $this->nf->parse($this->e->upisnina);
       $this->e->trajanje = $this->nf->parse($this->e->trajanje);

    }

    private function kontrolaNovi()
    {
        return $this->kontrolaNaziv() && $this->kontrolaCijena();
    }

    private function kontrolaPromjena()
    {
        return  $this->kontrolaCijena();
    }

    private function kontrolaNaziv()
    {
        $s = $this->e->naziv;
        if(strlen(trim($s))===0){
            $this->poruka='Naziv obavezno';
            return false;
        }

        if(strlen(trim($s))>50){
            $this->poruka='Naziv ne smije imati više od 50 znakova';
            return false;
        }


        if(Smjer::postojiIstiNazivUBazi($s)){
            $this->poruka='Isti naziv postoji u bazi';
            return false; 
        }

        return true;
    }

    private function kontrolaCijena()
    {

        if(strlen(trim($this->e->cijena))===0){
            $this->poruka='Cijena obavezno';
            return false;
        }

        $cijena = $this->nf->parse($this->e->cijena);
        //Log::info($cijena);
        if(!$cijena){
            $this->poruka='Cijena nije u dobrom formatu';
            return false;
        }

        if($cijena<=0){
            $this->poruka='Cijena mora biti veća od nule';
            return false;  
        }

        if($cijena>3000){
            $this->poruka='Cijena ne smije biti veća od 3000';
            return false;  
        }


        return true;
    }

    private function pocetniPodaci()
    {
        $e = new stdClass();
        $e->naziv='';
        $e->cijena='';
        $e->upisnina='';
        $e->trajanje='';
        $e->certificiran=false;
        return $e;
    }

    private function prilagodiPodatke($smjerovi)
    {
        foreach($smjerovi as $s){
            $s->cijena=$this->formatIznosa($s->cijena);
            $s->upisnina=$this->formatIznosa($s->upisnina);
            $s->certificiran=$s->certificiran ? 'check' : 'x';
            $s->title=$s->naziv;
            if(strlen($s->naziv)>20){
                $s->naziv = substr($s->naziv,0,15) . '...' . substr($s->naziv,strlen($s->naziv)-5);
            }
        }
        return $smjerovi;
    }

    private function formatIznosa($broj)
    {
        if($broj==null){
            return $this->nf->format(0);
        }
        return $this->nf->format($broj); 
    }
}