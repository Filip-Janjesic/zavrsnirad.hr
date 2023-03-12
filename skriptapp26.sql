
# c:\xampp\mysql\bin\mysql -uroot --default_character_set=utf8 < C:\Users\Filip\Documents\Edunova-2022\Edunovapp26\www\app.hr\skriptapp26.sql

drop database if exists edunovapp26;
create database edunovapp26 default charset utf8mb4;
use edunovapp26;

# samo za cpanel
alter database cesar_edunovapp26 charset utf8mb4;

create table operater(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    email varchar(50) not null,
    lozinka char(61) not null,
    uloga varchar(20) not null
);

insert into operater (ime,prezime,email,lozinka,uloga)
values ('Edunova','Operater','oper@edunova.hr',
'$2y$10$m8IvoBWyKZSqv149xoB//eEd/nPB56JGlRYM0Vann7X2cPUMKvXc2',
'oper'
);

insert into operater (ime,prezime,email,lozinka,uloga)
values ('Admin','Operater','admin@edunova.hr',
'$2y$10$m8IvoBWyKZSqv149xoB//eEd/nPB56JGlRYM0Vann7X2cPUMKvXc2',
'admin'
);


create table smjer(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    cijena decimal(18,2),
    upisnina decimal(18,2),
    trajanje int not null,
    certificiran boolean not null
);

create table grupa(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    maksimalnopolaznika int not null,
    datumpocetka datetime null, # ovaj null i ne treba pisati
    smjer int not null,
    predavac int
);

create table osoba(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    email varchar(50) not null,
    oib char(11)
);

create table polaznik(
    sifra int not null primary key auto_increment,
    brojugovora varchar(20),
    osoba int not null
);

create table predavac(
    sifra int not null primary key auto_increment,
    iban varchar(50),
    osoba int not null
);

create table clan(
    grupa int not null,
    polaznik int null
);


alter table grupa add foreign key (smjer) references smjer(sifra);
alter table grupa add foreign key (predavac) references predavac(sifra);

alter table clan add foreign key (grupa) references grupa(sifra);
alter table clan add foreign key (polaznik) references polaznik(sifra);

alter table polaznik add foreign key (osoba) references osoba(sifra);
alter table predavac add foreign key (osoba) references osoba(sifra);



# najlošija verzija
# 1 - primarni ključ koji će biti vanjski u tablici grupa
insert into smjer 
values (null,'PHP programiranje',
5999.99,500,130,false);

# bolja verzija
# 2
insert into smjer (naziv,trajanje,
certificiran)
values ('Java programiranje',130,true);

# verzija po principu najbolja praksa
# 3
insert into smjer (sifra,naziv,cijena,
upisnina, trajanje, certificiran)
values
(null,'Serviser',null,null,180,false);


# 1
insert into grupa 
(naziv,maksimalnopolaznika,smjer)
values 
('PP26',20,1);

# 2
insert into grupa 
(naziv,maksimalnopolaznika,smjer)
values ('JP27',20,2);


# 1 - 19
insert into osoba (ime,prezime,email)
values
('Boris','Bukovec','botaosijek@gmail.com'),
('Tonko','Vorgić','tonko85@gmail.com'),
('Domagoj','Culi','domo.culi@gmail.com'),
('Nemanja','Đurić','nemanja.duric92@gmail.com'),
('Adam','Vicković','vickovic2203@gmail.com'),
('Marko','Pavlović','markopavlovic316@gmail.com'),
('Darijan','Petrač','darijan.petrac@gmail.com'),
('Stjepan','Abramović','stjepanabramovic1@gmail.com'),
('Jakov','Begić','jakovbeg@gmail.com'),
('Marko','Mikulić','marko_mikulic08@hotmail.com'),
('Darija','Dumančić','darija.zdarilek@gmail.com'),
('Lobel','Špehar','lobel.spehar.os@gmail.com'),
('Ivan','Sambol','ivan.sambol@skole.hr'),
('Sven','Ostojić','sven.ostojic@outlook.com'),
('Lovrić','Kristijan','klovric991@gmail.com'),
('Luka','Agić','agic.luke@gmail.com'),
('Iris','Matokić','irismatokic@gmail.com'),
('Filip','Janješić','filip.janjesic@gmail.com'),
('Tomislav','Ružičić','truzicic@gmail.com');





# 1 - 19
insert into polaznik (osoba)
values (1),(2),(3),(4),(5),(6),(7),(8),(9),(10),
(11),(12),(13),(14),(15),(16),(17),(18),(19);


# 20
insert into osoba (ime,prezime,email)
values ('Tomislav','Jakopec','tjakopec@gmail.com');

# 1
insert into predavac (osoba) values (20);


insert into clan(grupa,polaznik)
values
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),
(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),
(1,16),(1,17),(1,18),(1,19);
