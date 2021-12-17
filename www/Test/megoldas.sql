CREATE DATABASE Tanfolyam;

USE Tanfolyam;

CREATE TABLE tanulok (
    id int AUTO_INCREMENT,
    nev varchar(100) NOT NULL,
    telefonszam varchar(20),
    szuletesiido DATE NOT NULL,
    email varchar(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (email)
);

CREATE TABLE tantargyak (
    id int AUTO_INCREMENT,
    megnevezes varchar(100) NOT NULL,
    tanar varchar(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (megnevezes)
);

CREATE TABLE ertekelesek (
    id int AUTO_INCREMENT,
    tanuloid int NOT NULL,
    tantargyid int NOT NULL,
    jegy int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (tanuloid) REFERENCES tanulok(id),
    FOREIGN KEY (tantargyid) REFERENCES tantargyak(id)
);

INSERT INTO tantargyak (megnevezes, tanar) VALUES ('Angol nyelv', 'Nemes Angéla');
INSERT INTO tantargyak (megnevezes, tanar) VALUES ('Informatika', 'Kis Ede');

INSERT INTO tanulok (nev, szuletesiido, email) VALUES ('Kovács Elek', '1991.02.28', 'elek0228@email.com');
INSERT INTO tanulok (nev, telefonszam, szuletesiido, email) VALUES ('Nagy Béla', '+36-55-335223', '1999.12.31', 'nagy.bela@drotposta.com');
INSERT INTO tanulok (nev, telefonszam, szuletesiido, email) VALUES ('Tóth Emil', '+36-55-475319', '1987.06.16', 'emil@e-level.com');

INSERT INTO ertekelesek (tanuloid, tantargyid, jegy) VALUES ('1', '1', '3');

INSERT INTO ertekelesek (tanuloid, tantargyid, jegy) VALUES ('1', '2', '5');
INSERT INTO ertekelesek (tanuloid, tantargyid, jegy) VALUES ('2', '2', '5');
INSERT INTO ertekelesek (tanuloid, tantargyid, jegy) VALUES ('3', '2', '5');

INSERT INTO ertekelesek (tanuloid, tantargyid, jegy) VALUES ('1', '1', '5');

SELECT tantargyak.megnevezes, ertekelesek.jegy
FROM ((ertekelesek
INNER JOIN tantargyak ON ertekelesek.tantargyid = tantargyak.id)
INNER JOIN tanulok ON ertekelesek.tanuloid = tanulok.id)
WHERE tanulok.nev = 'Kovács Elek';

SELECT tantargyak.megnevezes, AVG(ertekelesek.jegy)
FROM (ertekelesek
INNER JOIN tantargyak ON ertekelesek.tantargyid = tantargyak.id)
GROUP BY tantargyak.megnevezes;

SELECT tanulok.nev
FROM (tanulok
INNER JOIN ertekelesek ON tanulok.id = ertekelesek.tanuloid)
GROUP BY tanulok.nev
HAVING COUNT(ertekelesek.jegy) = 0;

SELECT AVG(YEAR(CURRENT_TIMESTAMP) - YEAR(szuletesiido)) AS atlageletkor
FROM tanulok;