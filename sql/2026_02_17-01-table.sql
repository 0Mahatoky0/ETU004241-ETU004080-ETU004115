DROP DATABASE IF EXISTS bngrc;

CREATE DATABASE IF NOT EXISTS bngrc;

USE bngrc;

CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255)
);

CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT NOT NULL,
    libelle VARCHAR(255),
    FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE categorie_besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10),
    libelle VARCHAR(255)
);

CREATE TABLE status_besoin_sinistre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10),
    libelle VARCHAR(255)
);


CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    libelle VARCHAR(255),
    prix_unitaire DECIMAL(10,2),
    FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id)
);


CREATE TABLE besoin_sinistre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite INT,
    montant DECIMAL(10,2),
    id_status_besoin_sinistre INT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id),
    FOREIGN KEY (id_status_besoin_sinistre) REFERENCES status_besoin_sinistre(id)
);


CREATE TABLE dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite INT,
    source VARCHAR(255),
    date DATETIME,
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
);


CREATE TABLE mvt_dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dons INT NOT NULL,
    entrer INT,
    sortie INT,
    id_besoin_sinistre INT,
    date DATETIME,
    FOREIGN KEY (id_dons) REFERENCES dons(id),
    FOREIGN KEY (id_besoin_sinistre) REFERENCES besoin_sinistre(id)
);
