
CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    genre ENUM('M', 'F') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    ville VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255) DEFAULT NULL
);

CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);

CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);

CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour DATE DEFAULT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Andry', '1990-01-15', 'M', 'andry@example.com', 'Antananarivo', 1234, NULL),
('Miora', '1985-05-20', 'F', 'miora@example.com', 'Fianarantsoa', 1234, NULL),
('Hery', '1992-07-10', 'M', 'hery@example.com', 'Toamasina', 1234, NULL),
('Lalao', '1988-12-05', 'F', 'lalao@example.com', 'Antsirabe', 1234, NULL);


INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');


INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Crème visage', 1, 1),
('Pinceau maquillage', 1, 1),
('Perceuse', 2, 1),
('Tournevis', 2, 1),
('Clé à molette', 3, 1),
('Huile moteur', 3, 1),
('Poêle', 4, 1),
('Casserole', 4, 1),
('Fourchette', 4, 1),
('Couteau', 4, 1),

('Fond de teint', 1, 2),
('Vernis à ongles', 1, 2),
('Marteau', 2, 2),
('Scie', 2, 2),
('Clé anglaise', 3, 2),
('Batterie voiture', 3, 2),
('Mixeur', 4, 2),
('Cafetière', 4, 2),
('Cuillère', 4, 2),
('Saladier', 4, 2),

('Roues', 3, 3),
('Fil de fer', 2, 3),
('Pince', 2, 3),
('Crème solaire', 1, 3),
('Gel douche', 1, 3),
('Clé dynamométrique', 3, 3),
('Poêle à frire', 4, 3),
('Four', 4, 3),
('Spatule', 4, 3),
('Tasse', 4, 3),

('Parfum', 1, 4),
('Lime à ongles', 1, 4),
('Perceuse sans fil', 2, 4),
('Niveau à bulle', 2, 4),
('Filtre à huile', 3, 4),
('Pneu', 3, 4),
('Robot culinaire', 4, 4),
('Grille-pain', 4, 4),
('Verre', 4, 4),
('Assiette', 4, 4);


INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2024-05-01', '2024-05-10'),
(5, 3, '2024-05-02', NULL),
(12, 1, '2024-05-03', '2024-05-15'),
(20, 4, '2024-05-04', NULL),
(25, 1, '2024-05-05', '2024-05-12'),
(30, 2, '2024-05-06', NULL),
(35, 3, '2024-05-07', '2024-05-14'),
(40, 4, '2024-05-08', NULL),
(3, 1, '2024-05-09', '2024-05-16'),
(8, 2, '2024-05-10', NULL);

ALTER TABLE objet ADD COLUMN etat VARCHAR(10) DEFAULT 'bon';