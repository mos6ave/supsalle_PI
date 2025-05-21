CREATE DATABASE IF NOT EXISTS gestion_salles;
USE gestion_salles;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    role VARCHAR(50)
);

CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    capacité INT,
    équipements TEXT
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_salle INT,
    id_utilisateur INT,
    date DATE,
    heure_debut TIME,
    heure_fin TIME,
    statut VARCHAR(50),
    FOREIGN KEY (id_salle) REFERENCES salles(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);

INSERT INTO salles (nom, capacité, équipements) VALUES
('Salle A', 30, 'Projecteur, Tableau'),
('Salle B', 20, 'Tableau blanc'),
('Salle C', 50, 'Projecteur, Sonorisation');
