CREATE DATABASE Examenfinal;
USE Examenfinal;


CREATE TABLE Membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre VARCHAR(10),
    email VARCHAR(100),
    ville VARCHAR(50),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

CREATE TABLE Categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50)
);

CREATE TABLE Objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE Images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);

CREATE TABLE Emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE Profil (
    id_profil INT AUTO_INCREMENT PRIMARY KEY,
    id_membre INT,
    bio TEXT,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

INSERT INTO membre (nom, date_naissance, genre, gmail, ville, mdp, image_profil) VALUES
('Armando', '2006-07-30', 'M', 'armando@email.com', 'Antanimena', 'pass1', 'armando.jpg'),
('Tiavina', '2006-06-18', 'M', 'tiavina@email.com', 'Antsirabe', 'pass2', 'tiavina.jpg'),
('Kevin', '2006-06-18', 'M', 'kevin@email.com', 'Mandromena', 'pass3', 'kevin.jpg'),
('Diane', '1988-11-30', 'F', 'diane@email.com', 'Toulouse', 'pass4', 'diane.jpg');

INSERT INTO categorie_objet (nom_categorie) VALUES
('esthetique'),
('bricolage'),
('mecanique'),
('cuisine');

INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Appartement Armando', 1, 1), ('Maison Armando', 2, 1), ('Studio Armando', 3, 1), ('Villa Armando', 4, 1), ('Garage Armando', 1, 1),
('Appartement Tiavina', 1, 2), ('Maison Tiavina', 2, 2), ('Studio Tiavina', 3, 2), ('Villa Tiavina', 4, 2), ('Garage Tiavina', 1, 2),
('Appartement Kevin', 1, 3), ('Maison Kevin', 2, 3), ('Studio Kevin', 3, 3), ('Villa Kevin', 4, 3), ('Garage Kevin', 1, 3),
('Appartement Diane', 1, 4), ('Maison Diane', 2, 4), ('Studio Diane', 3, 4), ('Villa Diane', 4, 4), ('Garage Diane', 1, 4);

INSERT INTO images_objet (id_objet, nom_image) VALUES
(1, 'img1.jpg'), (2, 'img2.jpg'), (3, 'img3.jpg'), (4, 'img4.jpg'), (5, 'img5.jpg'),
(6, 'img6.jpg'), (7, 'img7.jpg'), (8, 'img8.jpg'), (9, 'img9.jpg'), (10, 'img10.jpg'),
(11, 'img11.jpg'), (12, 'img12.jpg'), (13, 'img13.jpg'), (14, 'img14.jpg'), (15, 'img15.jpg'),
(16, 'img16.jpg'), (17, 'img17.jpg'), (18, 'img18.jpg'), (19, 'img19.jpg'), (20, 'img20.jpg'),
(21, 'img21.jpg'), (22, 'img22.jpg'), (23, 'img23.jpg'), (24, 'img24.jpg'), (25, 'img25.jpg'),
(26, 'img26.jpg'), (27, 'img27.jpg'), (28, 'img28.jpg'), (29, 'img29.jpg'), (30, 'img30.jpg'),
(31, 'img31.jpg'), (32, 'img32.jpg'), (33, 'img33.jpg'), (34, 'img34.jpg'), (35, 'img35.jpg'),
(36, 'img36.jpg'), (37, 'img37.jpg'), (38, 'img38.jpg'), (39, 'img39.jpg'), (40, 'img40.jpg');

INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-10'),
(2, 3, '2025-07-02', '2025-07-11'),
(3, 4, '2025-07-03', '2025-07-12'),
(4, 1, '2025-07-04', '2025-07-13'),
(5, 2, '2025-07-05', '2025-07-14'),
(6, 3, '2025-07-06', '2025-07-15'),
(7, 4, '2025-07-07', '2025-07-16'),
(8, 1, '2025-07-08', '2025-07-17'),
(9, 2, '2025-07-09', '2025-07-18'),
(10, 3, '2025-07-10', '2025-07-19');


