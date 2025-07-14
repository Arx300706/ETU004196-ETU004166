DROP DATABASE IF EXISTS Examenfinal;
CREATE DATABASE IF NOT EXISTS Examenfinal;
USE Examenfinal;


CREATE TABLE Exam_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre VARCHAR(10),
    email VARCHAR(100),
    ville VARCHAR(50),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

CREATE TABLE Exam_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50)
);

CREATE TABLE Exam_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE Exam_images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);

CREATE TABLE Exam_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

-- Insertion des membres
INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1990-01-01', 'F', 'alice@email.com', 'Paris', 'pass1', 'alice.jpg'),
('Bob', '1985-05-12', 'M', 'bob@email.com', 'Lyon', 'pass2', 'bob.jpg'),
('Charlie', '1992-07-23', 'M', 'charlie@email.com', 'Marseille', 'pass3', 'charlie.jpg'),
('Diane', '1988-11-30', 'F', 'diane@email.com', 'Toulouse', 'pass4', 'diane.jpg');

-- Insertion des catégories
INSERT INTO categorie_objet (nom_categorie) VALUES
('esthétique'),
('bricolage'),
('mécanique'),
('cuisine');

-- Insertion des objets (10 par membre, répartis sur les catégories)
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Objet1_Alice', 1, 1), ('Objet2_Alice', 2, 1), ('Objet3_Alice', 3, 1), ('Objet4_Alice', 4, 1), ('Objet5_Alice', 1, 1),
('Objet6_Alice', 2, 1), ('Objet7_Alice', 3, 1), ('Objet8_Alice', 4, 1), ('Objet9_Alice', 1, 1), ('Objet10_Alice', 2, 1),
('Objet1_Bob', 1, 2), ('Objet2_Bob', 2, 2), ('Objet3_Bob', 3, 2), ('Objet4_Bob', 4, 2), ('Objet5_Bob', 1, 2),
('Objet6_Bob', 2, 2), ('Objet7_Bob', 3, 2), ('Objet8_Bob', 4, 2), ('Objet9_Bob', 1, 2), ('Objet10_Bob', 2, 2),
('Objet1_Charlie', 1, 3), ('Objet2_Charlie', 2, 3), ('Objet3_Charlie', 3, 3), ('Objet4_Charlie', 4, 3), ('Objet5_Charlie', 1, 3),
('Objet6_Charlie', 2, 3), ('Objet7_Charlie', 3, 3), ('Objet8_Charlie', 4, 3), ('Objet9_Charlie', 1, 3), ('Objet10_Charlie', 2, 3),
('Objet1_Diane', 1, 4), ('Objet2_Diane', 2, 4), ('Objet3_Diane', 3, 4), ('Objet4_Diane', 4, 4), ('Objet5_Diane', 1, 4),
('Objet6_Diane', 2, 4), ('Objet7_Diane', 3, 4), ('Objet8_Diane', 4, 4), ('Objet9_Diane', 1, 4), ('Objet10_Diane', 2, 4);

-- Insertion des images_objet (1 image par objet)
INSERT INTO images_objet (id_objet, nom_image) VALUES
(1, 'img1.jpg'), (2, 'img2.jpg'), (3, 'img3.jpg'), (4, 'img4.jpg'), (5, 'img5.jpg'),
(6, 'img6.jpg'), (7, 'img7.jpg'), (8, 'img8.jpg'), (9, 'img9.jpg'), (10, 'img10.jpg'),
(11, 'img11.jpg'), (12, 'img12.jpg'), (13, 'img13.jpg'), (14, 'img14.jpg'), (15, 'img15.jpg'),
(16, 'img16.jpg'), (17, 'img17.jpg'), (18, 'img18.jpg'), (19, 'img19.jpg'), (20, 'img20.jpg'),
(21, 'img21.jpg'), (22, 'img22.jpg'), (23, 'img23.jpg'), (24, 'img24.jpg'), (25, 'img25.jpg'),
(26, 'img26.jpg'), (27, 'img27.jpg'), (28, 'img28.jpg'), (29, 'img29.jpg'), (30, 'img30.jpg'),
(31, 'img31.jpg'), (32, 'img32.jpg'), (33, 'img33.jpg'), (34, 'img34.jpg'), (35, 'img35.jpg'),
(36, 'img36.jpg'), (37, 'img37.jpg'), (38, 'img38.jpg'), (39, 'img39.jpg'), (40, 'img40.jpg');

-- Insertion des emprunts (10 emprunts)
INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-10'),
(5, 3, '2025-07-02', '2025-07-11'),
(10, 4, '2025-07-03', '2025-07-12'),
(15, 1, '2025-07-04', '2025-07-13'),
(20, 2, '2025-07-05', '2025-07-14'),
(25, 3, '2025-07-06', '2025-07-15'),
(30, 4, '2025-07-07', '2025-07-16'),
(35, 1, '2025-07-08', '2025-07-17'),
(40, 2, '2025-07-09', '2025-07-18'),
(12, 3, '2025-07-10', '2025-07-19');

