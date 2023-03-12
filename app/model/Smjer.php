<?php

class Smjer
{
    // CRUD operacije

    public static function read()
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from smjer
            order by naziv asc
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function readOne($sifra)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select * from smjer
            where sifra=:sifra
        
        ');
        $izraz->execute([
            'sifra'=>$sifra
        ]);
        return $izraz->fetch();
    }

    public static function create($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            insert into smjer(naziv,cijena,upisnina,
            trajanje,certificiran) values
            (:naziv,:cijena,:upisnina,
            :trajanje,:certificiran);
        
        ');
        $izraz->execute($parametri);
    }

    public static function update($parametri)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            update smjer set
            naziv=:naziv,
            cijena=:cijena,
            upisnina=:upisnina,
            trajanje=:trajanje,
            certificiran=:certificiran
            where sifra=:sifra
        
        ');
        $izraz->execute($parametri);
    }

    public static function postojiIstiNazivUBazi($s)
    {
        $veza = DB::getInstance();
        $izraz = $veza->prepare('
        
            select sifra from smjer
            where naziv=:naziv
        
        ');
        $izraz->execute([
            'naziv'=>$s
        ]);
        $sifra=$izraz->fetchColumn();
        return $sifra>0;
    }

}